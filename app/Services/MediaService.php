<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaService
{
    protected string $disk = 'public';

    /**
     * Upload a single image file.
     */
    public function uploadImage(
        UploadedFile $file,
        string $directory = 'images',
        ?int $width = null,
        ?int $height = null,
        int $quality = 85
    ): string {
        $filename = $this->generateFilename($file);
        $path = $directory . '/' . $filename;

        // Process image if dimensions are specified
        if ($width || $height) {
            $image = $this->resizeImage($file, $width, $height, $quality);
            Storage::disk($this->disk)->put($path, $image);
        } else {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        }

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
        int $height = 150
    ): string {
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $thumbnailPath = $directory . '/thumbnails/' . $filename . '_thumb.' . $extension;

        $imageContent = Storage::disk($this->disk)->get($path);

        $manager = new ImageManager(new Driver());
        $image = $manager->read($imageContent);
        $image->cover($width, $height);

        Storage::disk($this->disk)->put($thumbnailPath, $image->toJpeg(85));

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
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $thumbnailPath = $directory . '/thumbnails/' . $filename . '_thumb.' . $extension;

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
     * Generate a unique filename for upload.
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        return $name . '_' . time() . '_' . Str::random(8) . '.' . $extension;
    }

    /**
     * Resize an image with optional dimensions.
     */
    protected function resizeImage(
        UploadedFile $file,
        ?int $width,
        ?int $height,
        int $quality = 85
    ): string {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getPathname());

        if ($width && $height) {
            $image->cover($width, $height);
        } elseif ($width) {
            $image->scale(width: $width);
        } elseif ($height) {
            $image->scale(height: $height);
        }

        return $image->toJpeg($quality);
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
}
