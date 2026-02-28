<?php
/**
 * Storage Symlink Creator for Shared Hosting
 *
 * Run this script ONCE after deployment to create the storage symlink.
 * Access it via: https://yourdomain.com/create-symlink.php
 *
 * IMPORTANT: Delete this file after running it successfully!
 */

// Security check - only allow from specific IPs or with a secret key
$secretKey = 'dubai2026-symlink-create'; // Change this to your own secret
$providedKey = $_GET['key'] ?? '';

if ($providedKey !== $secretKey) {
    die('Access denied. Please provide the correct key: ?key=your-secret-key');
}

echo "<h2>Laravel Storage Symlink Creator</h2>";
echo "<hr>";

// Define paths
$publicPath = __DIR__;
$storagePath = dirname(__DIR__) . '/storage/app/public';
$linkPath = $publicPath . '/storage';

echo "<p><strong>Public Path:</strong> {$publicPath}</p>";
echo "<p><strong>Storage Path:</strong> {$storagePath}</p>";
echo "<p><strong>Link Path:</strong> {$linkPath}</p>";
echo "<hr>";

// Check if storage directory exists
if (!is_dir($storagePath)) {
    echo "<p style='color: red;'>❌ ERROR: Storage directory does not exist at: {$storagePath}</p>";
    echo "<p>Please make sure to upload the complete storage/app/public directory.</p>";
    exit;
}

echo "<p style='color: green;'>✓ Storage directory exists</p>";

// Check if symlink or directory already exists
if (file_exists($linkPath)) {
    if (is_link($linkPath)) {
        echo "<p style='color: orange;'>⚠️ Symlink already exists at: {$linkPath}</p>";

        // Check if it points to correct location
        $currentTarget = readlink($linkPath);
        echo "<p>Current symlink target: {$currentTarget}</p>";

        if (realpath($currentTarget) === realpath($storagePath)) {
            echo "<p style='color: green;'>✓ Symlink is pointing to correct location!</p>";
        } else {
            echo "<p style='color: red;'>❌ Symlink is pointing to wrong location. Removing and recreating...</p>";
            unlink($linkPath);
        }
    } else if (is_dir($linkPath)) {
        echo "<p style='color: orange;'>⚠️ A directory (not symlink) exists at: {$linkPath}</p>";
        echo "<p>This might be from a failed symlink or manual upload. Consider removing it.</p>";

        // Option to remove and recreate
        echo "<p><a href='?key={$secretKey}&remove=1'>Click here to remove the directory and create symlink</a></p>";

        if (isset($_GET['remove']) && $_GET['remove'] == '1') {
            // Remove directory recursively
            function rrmdir($dir) {
                if (is_dir($dir)) {
                    $objects = scandir($dir);
                    foreach ($objects as $object) {
                        if ($object != "." && $object != "..") {
                            if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object)) {
                                rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                            } else {
                                unlink($dir . DIRECTORY_SEPARATOR . $object);
                            }
                        }
                    }
                    rmdir($dir);
                }
            }

            rrmdir($linkPath);
            echo "<p style='color: green;'>✓ Directory removed</p>";
        } else {
            exit;
        }
    }
}

// Create symlink if it doesn't exist
if (!file_exists($linkPath)) {
    echo "<p>Creating symlink...</p>";

    // Try different methods
    $success = false;
    $error = '';

    // Method 1: Standard symlink
    if (@symlink($storagePath, $linkPath)) {
        $success = true;
        echo "<p style='color: green;'>✓ Symlink created successfully using symlink() function!</p>";
    } else {
        $error = error_get_last()['message'] ?? 'Unknown error';
        echo "<p style='color: orange;'>⚠️ symlink() failed: {$error}</p>";

        // Method 2: Try relative path symlink
        $relativePath = '../storage/app/public';
        $currentDir = getcwd();
        chdir($publicPath);

        if (@symlink($relativePath, 'storage')) {
            $success = true;
            echo "<p style='color: green;'>✓ Symlink created successfully using relative path!</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Relative symlink() also failed</p>";
        }

        chdir($currentDir);
    }

    // If symlink failed, try alternative approach
    if (!$success) {
        echo "<hr>";
        echo "<h3>Alternative Solution (if symlink doesn't work):</h3>";
        echo "<p>Your hosting may not support symlinks. Here are alternatives:</p>";
        echo "<ol>";
        echo "<li><strong>Contact your hosting provider</strong> to enable symlinks</li>";
        echo "<li><strong>Use .htaccess rewrite</strong> (already configured in your .htaccess)</li>";
        echo "<li><strong>Manually copy files</strong> from storage/app/public to public/storage after each upload</li>";
        echo "<li><strong>Change storage disk</strong> to use public folder directly (not recommended)</li>";
        echo "</ol>";

        echo "<hr>";
        echo "<h3>Creating storage directory with .htaccess redirect:</h3>";

        // Create directory if it doesn't exist
        if (!is_dir($linkPath)) {
            mkdir($linkPath, 0755, true);
            echo "<p style='color: green;'>✓ Created public/storage directory</p>";
        }

        // Create .htaccess to redirect to actual storage
        $htaccessContent = <<<HTACCESS
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /storage/

    # Redirect all requests to actual storage location
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /storage/app/public/$1 [L,NC]
</IfModule>

# If above doesn't work, try PHP passthrough
<IfModule !mod_rewrite.c>
    DirectoryIndex index.php
</IfModule>
HTACCESS;

        file_put_contents($linkPath . '/.htaccess', $htaccessContent);
        echo "<p style='color: green;'>✓ Created .htaccess in public/storage</p>";

        // Create index.php passthrough
        $indexPhpContent = <<<'PHP'
<?php
/**
 * Storage file passthrough for hosts without symlink support
 */

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($requestUri, PHP_URL_PATH);
$path = str_replace('/storage/', '', $path);
$path = ltrim($path, '/');

$storagePath = dirname(__DIR__) . '/storage/app/public/' . $path;

if (file_exists($storagePath) && is_file($storagePath)) {
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    $ext = strtolower(pathinfo($storagePath, PATHINFO_EXTENSION));
    $mimeType = $mimeTypes[$ext] ?? mime_content_type($storagePath);

    header('Content-Type: ' . $mimeType);
    header('Content-Length: ' . filesize($storagePath));
    header('Cache-Control: public, max-age=31536000');

    readfile($storagePath);
    exit;
}

http_response_code(404);
echo "File not found";
PHP;

        file_put_contents($linkPath . '/index.php', $indexPhpContent);
        echo "<p style='color: green;'>✓ Created index.php passthrough in public/storage</p>";

        echo "<p style='color: blue;'><strong>Alternative solution implemented!</strong> Your images should now work.</p>";
    }
}

// Verify the symlink works
echo "<hr>";
echo "<h3>Verification:</h3>";

if (is_link($linkPath)) {
    $target = readlink($linkPath);
    echo "<p>Symlink target: {$target}</p>";

    if (is_dir($linkPath)) {
        echo "<p style='color: green;'>✓ Symlink is accessible as directory</p>";

        // List files to verify
        $files = scandir($linkPath);
        $fileCount = count($files) - 2; // Exclude . and ..
        echo "<p>Files in storage: {$fileCount}</p>";
    } else {
        echo "<p style='color: red;'>❌ Symlink target is not accessible</p>";
    }
} else if (is_dir($linkPath)) {
    echo "<p style='color: green;'>✓ Storage directory exists (using fallback method)</p>";
}

echo "<hr>";
echo "<p style='color: red; font-weight: bold;'>⚠️ IMPORTANT: Delete this file (create-symlink.php) after successful setup!</p>";
echo "<p><a href='?key={$secretKey}&delete=1'>Click here to delete this file</a></p>";

if (isset($_GET['delete']) && $_GET['delete'] == '1') {
    if (unlink(__FILE__)) {
        echo "<p style='color: green;'>✓ File deleted successfully! You will be redirected...</p>";
        echo "<script>setTimeout(function(){ window.location.href = '/'; }, 2000);</script>";
    } else {
        echo "<p style='color: red;'>❌ Could not delete file. Please delete manually.</p>";
    }
}
