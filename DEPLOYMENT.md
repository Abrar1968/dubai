# Turbo Hosting Deployment Guide

## Files Created for Deployment

### 1. `.htaccess` (Root)
Redirects all requests to the `public` folder. Use this if:
- Your hosting points to the root directory instead of `public`
- You want clean URLs without `/public/` in the path

### 2. `public/.htaccess` (Updated)
- Handles Laravel routing  
- Includes fallback for storage symlink if symlinks don't work
- Sets proper MIME types for images
- Enables FollowSymLinks

### 3. `storage/app/public/.htaccess`
- Allows direct access to storage files
- Sets caching headers
- Enables CORS for images

### 4. `public/create-symlink.php`
One-time script to create storage symlink on shared hosting.

---

## Deployment Steps

### Step 1: Upload Files
Upload all project files to your hosting via FTP/cPanel File Manager.

### Step 2: Set Document Root
In your hosting control panel, set the **Document Root** to point to the `public` folder:
```
/home/yourusername/public_html/public
```
OR if using subdomain/addon domain, set it accordingly.

### Step 3: Create Storage Symlink
Visit this URL in your browser:
```
https://yourdomain.com/create-symlink.php?key=dubai2026-symlink-create
```

⚠️ **Change the secret key** in the file before uploading for security!

### Step 4: Delete the Symlink Script
After successful symlink creation, delete `create-symlink.php` from public folder.

### Step 5: Set Permissions
```bash
# Via SSH if available
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 644 .env
```

Or via cPanel File Manager, right-click folders and set permissions.

### Step 6: Configure .env
Make sure your `.env` file has:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

FILESYSTEM_DISK=public
```

---

## Troubleshooting

### Images Still Not Loading?

1. **Check storage symlink exists:**
   - Look for `public/storage` folder
   - Should point to `../storage/app/public`

2. **Check file permissions:**
   - `storage/app/public` should be readable (755)
   - Uploaded images should be readable (644)

3. **Check .htaccess is working:**
   - Create a test file in `storage/app/public/test.txt`
   - Try accessing `https://yourdomain.com/storage/test.txt`

4. **If symlinks don't work:**
   The `create-symlink.php` script will automatically create a fallback with:
   - A `public/storage/index.php` passthrough script
   - A `.htaccess` rewrite rule

5. **Contact hosting support:**
   Ask them to enable `FollowSymLinks` in Apache configuration.

### Alternative: Hardcoded Storage Path

If nothing works, you can modify your Laravel config to store files directly in public:

```php
// config/filesystems.php
'public' => [
    'driver' => 'local',
    'root' => public_path('uploads'), // Change from storage_path
    'url' => env('APP_URL').'/uploads',
    'visibility' => 'public',
],
```

---

## Files to Delete After Deployment

- `public/create-symlink.php` (after running it)
- `DEPLOYMENT.md` (this file, if you don't need it)
- Any `.git` folder (for security)

---

## Quick Commands (If SSH Available)

```bash
# Navigate to project
cd /home/yourusername/public_html

# Create symlink manually
ln -sf ../storage/app/public public/storage

# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
