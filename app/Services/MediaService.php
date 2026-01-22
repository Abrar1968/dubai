<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    protected string $disk = 'public';

    /**
     * Supported image extensions for conversion.
     */
    protected array $supportedExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'heic', 'heif', 'tiff', 'tif', 'avif'
    ];

    /**
     * Upload a single image file and convert to WebP.
     */
    public function uploadImage(
        UploadedFile $file,
        string $directory = 'images',
        ?int $width = null,
        ?int $height = null,
        int $quality = 85
    ): string {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Generate WebP filename
        $filename = Str::slug($originalName) . '_' . time() . '_' . Str::random(8) . '.webp';
        $path = $directory . '/' . $filename;

        // Create GD image resource from uploaded file
        $sourceImage = $this->createImageFromFile($file);
        
        if ($sourceImage === false) {
            // If we can't process the image, store original file
            $fallbackFilename = $this->generateFilename($file);
            Storage::disk($this->disk)->putFileAs($directory, $file, $fallbackFilename);
            return $directory . '/' . $fallbackFilename;
        }

        // Resize if dimensions are specified
        if ($width || $height) {
            $sourceImage = $this->resizeImageGd($sourceImage, $width, $height);
        }

        // Convert to WebP and save
        $webpContent = $this->convertToWebP($sourceImage, $quality);
        imagedestroy($sourceImage);

        Storage::disk($this->disk)->put($path, $webpContent);

        return $path;
    }

    /**
     * Upload multiple images.
     *
     * @param array<UploadedFile> $files
     * @return array<string>
     */
    public function uploadMultipleImages(
        array $files,
        string $directory = 'images',
        ?int $width = null,
        ?int $height = null,
        int $quality = 85
    ): array {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadImage($file, $directory, $width, $height, $quality);
            }
        }

        return $paths;
    }

    /**
     * Generate thumbnail for an image.
     */
    public function generateThumbnail(
        string $path,
        int $width = 150,
        int $height = 150,
        int $quality = 85
    ): string {
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        $filename = pathinfo($path, PATHINFO_FILENAME);

        $thumbnailPath = $directory . '/thumbnails/' . $filename . '_thumb.webp';

        $imageContent = Storage::disk($this->disk)->get($path);
        
        // Create temp file to read with GD
        $tempFile = tempnam(sys_get_temp_dir(), 'img_');
        file_put_contents($tempFile, $imageContent);

        $sourceImage = $this->createImageFromPath($tempFile);
        unlink($tempFile);

        if ($sourceImage === false) {
            return $path; // Return original if can't process
        }

        // Resize to cover dimensions (crop to fit)
        $sourceImage = $this->coverImageGd($sourceImage, $width, $height);

        // Convert to WebP
        $webpContent = $this->convertToWebP($sourceImage, $quality);
        imagedestroy($sourceImage);

        Storage::disk($this->disk)->put($thumbnailPath, $webpContent);

        return $thumbnailPath;
    }

    /**
     * Delete an image and its thumbnail if exists.
     */
    public function deleteImage(string $path): bool
    {
        $deleted = Storage::disk($this->disk)->delete($path);

        // Try to delete thumbnail if exists
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        $filename = pathinfo($path, PATHINFO_FILENAME);

        $thumbnailPath = $directory . '/thumbnails/' . $filename . '_thumb.webp';

        if (Storage::disk($this->disk)->exists($thumbnailPath)) {
            Storage::disk($this->disk)->delete($thumbnailPath);
        }

        return $deleted;
    }

    /**
     * Delete multiple images.
     *
     * @param array<string> $paths
     */
    public function deleteMultipleImages(array $paths): bool
    {
        foreach ($paths as $path) {
            $this->deleteImage($path);
        }

        return true;
    }

    /**
     * Get the public URL for an image.
     */
    public function getUrl(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Check if an image exists.
     */
    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Get file size in bytes.
     */
    public function getSize(string $path): int
    {
        return Storage::disk($this->disk)->size($path);
    }

    /**
     * Get human-readable file size.
     */
    public function getHumanSize(string $path): string
    {
        $bytes = $this->getSize($path);
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Move an image to a new location.
     */
    public function moveImage(string $from, string $to): bool
    {
        return Storage::disk($this->disk)->move($from, $to);
    }

    /**
     * Copy an image to a new location.
     */
    public function copyImage(string $from, string $to): bool
    {
        return Storage::disk($this->disk)->copy($from, $to);
    }

    /**
     * Upload a file (non-image).
     */
    public function uploadFile(UploadedFile $file, string $directory = 'files'): string
    {
        $filename = $this->generateFilename($file);

        Storage::disk($this->disk)->putFileAs($directory, $file, $filename);

        return $directory . '/' . $filename;
    }

    /**
     * Delete a file.
     */
    public function deleteFile(string $path): bool
    {
        return Storage::disk($this->disk)->delete($path);
    }

    /**
     * Set the storage disk.
     */
    public function setDisk(string $disk): self
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Generate a unique filename for upload.
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        return $name . '_' . time() . '_' . Str::random(8) . '.' . $extension;
    }

    /**
     * Create GD image resource from uploaded file.
     * Supports: JPEG, PNG, GIF, WebP, BMP, AVIF, HEIC/HEIF (via imagick CLI)
     *
     * @return \GdImage|false
     */
    protected function createImageFromFile(UploadedFile $file): \GdImage|false
    {
        $path = $file->getPathname();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        return $this->createImageResource($path, $extension, $mimeType);
    }

    /**
     * Create GD image resource from file path.
     *
     * @return \GdImage|false
     */
    protected function createImageFromPath(string $path): \GdImage|false
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimeType = mime_content_type($path) ?: '';

        return $this->createImageResource($path, $extension, $mimeType);
    }

    /**
     * Create image resource based on file type.
     *
     * @return \GdImage|false
     */
    protected function createImageResource(string $path, string $extension, string $mimeType): \GdImage|false
    {
        // Try based on mime type first, then extension
        $type = $mimeType ?: $extension;

        // Handle HEIC/HEIF format (requires ImageMagick convert CLI tool)
        if (in_array($extension, ['heic', 'heif']) || str_contains($mimeType, 'heic') || str_contains($mimeType, 'heif')) {
            return $this->convertHeicToGd($path);
        }

        // Handle AVIF format
        if ($extension === 'avif' || str_contains($mimeType, 'avif')) {
            if (function_exists('imagecreatefromavif')) {
                return @imagecreatefromavif($path);
            }
            return $this->convertWithImageMagick($path);
        }

        // Standard GD formats
        return match (true) {
            str_contains($type, 'jpeg'), str_contains($type, 'jpg') => @imagecreatefromjpeg($path),
            str_contains($type, 'png') => $this->createFromPngWithAlpha($path),
            str_contains($type, 'gif') => @imagecreatefromgif($path),
            str_contains($type, 'webp') => @imagecreatefromwebp($path),
            str_contains($type, 'bmp') => @imagecreatefrombmp($path),
            str_contains($type, 'tiff'), str_contains($type, 'tif') => $this->convertWithImageMagick($path),
            default => $this->tryAutoDetect($path),
        };
    }

    /**
     * Create PNG image preserving alpha channel.
     *
     * @return \GdImage|false
     */
    protected function createFromPngWithAlpha(string $path): \GdImage|false
    {
        $image = @imagecreatefrompng($path);
        
        if ($image === false) {
            return false;
        }

        // Preserve transparency
        imagealphablending($image, true);
        imagesavealpha($image, true);

        return $image;
    }

    /**
     * Try auto-detect image type using getimagesize.
     *
     * @return \GdImage|false
     */
    protected function tryAutoDetect(string $path): \GdImage|false
    {
        $info = @getimagesize($path);
        
        if ($info === false) {
            return false;
        }

        return match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => $this->createFromPngWithAlpha($path),
            IMAGETYPE_GIF => @imagecreatefromgif($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            IMAGETYPE_BMP => @imagecreatefrombmp($path),
            default => false,
        };
    }

    /**
     * Convert HEIC/HEIF to GD using ImageMagick CLI.
     *
     * @return \GdImage|false
     */
    protected function convertHeicToGd(string $path): \GdImage|false
    {
        return $this->convertWithImageMagick($path);
    }

    /**
     * Convert unsupported formats using ImageMagick CLI (fallback).
     *
     * @return \GdImage|false
     */
    protected function convertWithImageMagick(string $path): \GdImage|false
    {
        // Check if ImageMagick is available
        $convertPath = $this->findImageMagickConvert();
        
        if ($convertPath === null) {
            // Try using PHP's Imagick extension if available
            if (extension_loaded('imagick')) {
                return $this->convertWithImagickExtension($path);
            }
            return false;
        }

        // Convert to PNG temp file using ImageMagick CLI
        $tempOutput = tempnam(sys_get_temp_dir(), 'converted_') . '.png';
        $escapedPath = escapeshellarg($path);
        $escapedOutput = escapeshellarg($tempOutput);

        $command = "{$convertPath} {$escapedPath}[0] {$escapedOutput}";
        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || !file_exists($tempOutput)) {
            @unlink($tempOutput);
            return false;
        }

        $image = @imagecreatefrompng($tempOutput);
        @unlink($tempOutput);

        return $image;
    }

    /**
     * Convert using PHP Imagick extension.
     *
     * @return \GdImage|false
     */
    protected function convertWithImagickExtension(string $path): \GdImage|false
    {
        try {
            $imagick = new \Imagick($path);
            $imagick->setImageFormat('png');
            
            $tempOutput = tempnam(sys_get_temp_dir(), 'converted_') . '.png';
            $imagick->writeImage($tempOutput);
            $imagick->destroy();

            $image = @imagecreatefrompng($tempOutput);
            @unlink($tempOutput);

            return $image;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Find ImageMagick convert binary.
     */
    protected function findImageMagickConvert(): ?string
    {
        $possiblePaths = [
            'convert',
            '/usr/bin/convert',
            '/usr/local/bin/convert',
            'C:\Program Files\ImageMagick-7.1.1-Q16-HDRI\convert.exe',
            'C:\Program Files\ImageMagick-7.1.1-Q16\convert.exe',
        ];

        foreach ($possiblePaths as $convertPath) {
            $command = PHP_OS_FAMILY === 'Windows' 
                ? "where " . escapeshellarg(basename($convertPath)) . " 2>nul"
                : "which " . escapeshellarg($convertPath) . " 2>/dev/null";
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 || file_exists($convertPath)) {
                return $convertPath;
            }
        }

        return null;
    }

    /**
     * Resize image using GD (scale to fit within dimensions).
     *
     * @return \GdImage
     */
    protected function resizeImageGd(\GdImage $image, ?int $width, ?int $height): \GdImage
    {
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        // Calculate new dimensions maintaining aspect ratio
        if ($width && $height) {
            $ratio = min($width / $originalWidth, $height / $originalHeight);
            $newWidth = (int) round($originalWidth * $ratio);
            $newHeight = (int) round($originalHeight * $ratio);
        } elseif ($width) {
            $ratio = $width / $originalWidth;
            $newWidth = $width;
            $newHeight = (int) round($originalHeight * $ratio);
        } elseif ($height) {
            $ratio = $height / $originalHeight;
            $newWidth = (int) round($originalWidth * $ratio);
            $newHeight = $height;
        } else {
            return $image;
        }

        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
        imagefill($newImage, 0, 0, $transparent);

        // Resize
        imagecopyresampled(
            $newImage, $image,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );

        imagedestroy($image);

        return $newImage;
    }

    /**
     * Cover/crop image to exact dimensions (center crop).
     *
     * @return \GdImage
     */
    protected function coverImageGd(\GdImage $image, int $width, int $height): \GdImage
    {
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        // Calculate the ratio
        $ratio = max($width / $originalWidth, $height / $originalHeight);
        
        $newWidth = (int) round($originalWidth * $ratio);
        $newHeight = (int) round($originalHeight * $ratio);

        // First resize to cover
        $tempImage = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($tempImage, false);
        imagesavealpha($tempImage, true);

        imagecopyresampled(
            $tempImage, $image,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );

        // Then crop to exact dimensions from center
        $x = (int) round(($newWidth - $width) / 2);
        $y = (int) round(($newHeight - $height) / 2);

        $finalImage = imagecreatetruecolor($width, $height);
        imagealphablending($finalImage, false);
        imagesavealpha($finalImage, true);

        imagecopy($finalImage, $tempImage, 0, 0, $x, $y, $width, $height);

        imagedestroy($image);
        imagedestroy($tempImage);

        return $finalImage;
    }

    /**
     * Convert GD image to WebP format.
     */
    protected function convertToWebP(\GdImage $image, int $quality = 85): string
    {
        // Start output buffering
        ob_start();
        
        // Handle transparency - create white background for non-transparent images
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Create output image
        $outputImage = imagecreatetruecolor($width, $height);
        
        // WebP supports transparency, so preserve it
        imagealphablending($outputImage, false);
        imagesavealpha($outputImage, true);
        
        // Copy with alpha
        imagecopy($outputImage, $image, 0, 0, 0, 0, $width, $height);
        
        // Output as WebP
        imagewebp($outputImage, null, $quality);
        
        $content = ob_get_clean();
        
        imagedestroy($outputImage);
        
        return $content;
    }

    /**
     * Check if file is an image.
     */
    public function isImage(UploadedFile $file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($extension, $this->supportedExtensions);
    }

    /**
     * Get supported image extensions.
     */
    public function getSupportedExtensions(): array
    {
        return $this->supportedExtensions;
    }
}
