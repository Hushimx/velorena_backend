# Image Loading Troubleshooting Guide

If images are not appearing in the design selector cards, follow this troubleshooting guide.

## 🔍 **Quick Diagnosis**

### **1. Check Debug Information**

If you have `APP_DEBUG=true` in your `.env` file, you'll see a yellow debug box showing:

-   Total designs count
-   Current page
-   Designs with images count
-   Sample image URL

### **2. Test Image URLs**

Run the image testing command:

```bash
php artisan designs:test-images --limit=5
```

This will test all image URLs and show which ones are working.

## 🚨 **Common Issues & Solutions**

### **Issue 1: No Images at All**

**Symptoms:** All design cards show placeholder icons instead of images

**Possible Causes:**

-   Database is empty
-   Seeder hasn't been run
-   Image URLs are invalid

**Solutions:**

```bash
# 1. Check if designs exist
php artisan tinker
>>> App\Models\Design::count();

# 2. If count is 0, run the seeder
php artisan db:seed --class=DesignSeeder

# 3. Test the images
php artisan designs:test-images
```

### **Issue 2: Some Images Work, Others Don't**

**Symptoms:** Some design cards show images, others show placeholders

**Possible Causes:**

-   Network connectivity issues
-   Some image URLs are blocked
-   CORS policy restrictions

**Solutions:**

```bash
# Test specific images
php artisan designs:test-images --limit=10

# Check network connectivity
curl -I "https://picsum.photos/300/300?random=1"
```

### **Issue 3: Images Load Slowly or Timeout**

**Symptoms:** Images take a long time to load or never appear

**Possible Causes:**

-   Slow internet connection
-   Image service is slow
-   Server timeout settings

**Solutions:**

-   Check your internet connection
-   Try refreshing the page
-   Consider using local images instead

## 🛠️ **Testing Commands**

### **Test Database Content**

```bash
# Check design count
php artisan tinker
>>> App\Models\Design::count();

# Check specific design
>>> App\Models\Design::first();

# Check image URLs
>>> App\Models\Design::pluck('thumbnail_url');
```

### **Test Image URLs**

```bash
# Test all images
php artisan designs:test-images

# Test specific number
php artisan designs:test-images --limit=3

# Test with verbose output
php artisan designs:test-images --limit=1
```

### **Test API Endpoints**

```bash
# Test design API
curl http://localhost:8000/api/designs

# Test specific design
curl http://localhost:8000/api/designs/1
```

## 🖼️ **Image Service Alternatives**

If the current image service isn't working, try these alternatives:

### **Option 1: Picsum Photos (Current)**

```
https://picsum.photos/300/300?random=1
https://picsum.photos/800/600?random=1
```

### **Option 2: Placeholder.com**

```
https://via.placeholder.com/300x300/4F46E5/FFFFFF?text=Design+1
https://via.placeholder.com/800x600/4F46E5/FFFFFF?text=Design+1
```

### **Option 3: Local Images**

Store images in `public/images/designs/` and use:

```
/images/designs/business-card.jpg
/images/designs/wedding-invitation.jpg
```

### **Option 4: Your Own Image Service**

Update the seeder with your own image URLs:

```php
'image_url' => 'https://your-domain.com/images/design1.jpg',
'thumbnail_url' => 'https://your-domain.com/images/design1-thumb.jpg',
```

## 🔧 **Code-Level Debugging**

### **Check Browser Console**

1. Open browser developer tools (F12)
2. Go to Console tab
3. Look for image loading errors
4. Check Network tab for failed requests

### **Check Laravel Logs**

```bash
# View recent logs
tail -f storage/logs/laravel.log

# Search for image-related errors
grep -i "image" storage/logs/laravel.log
```

### **Add More Debugging**

In the design selector view, you can add more debug info:

```php
@if(config('app.debug'))
    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
        <h4 class="font-medium text-red-800 mb-2">Image Debug:</h4>
        @foreach($designs->take(3) as $design)
            <div class="text-sm text-red-700">
                <p><strong>{{ $design->title }}:</strong></p>
                <p>Thumbnail: {{ $design->thumbnail_url }}</p>
                <p>Main: {{ $design->image_url }}</p>
            </div>
        @endforeach
    </div>
@endif
```

## 📱 **Mobile-Specific Issues**

### **Issue: Images work on desktop but not mobile**

**Possible Causes:**

-   Mobile network restrictions
-   Different user agent handling
-   Touch device specific issues

**Solutions:**

-   Test on different mobile devices
-   Check mobile network settings
-   Use responsive image loading

## 🌐 **Network-Level Issues**

### **Check Firewall/Proxy Settings**

-   Corporate firewalls may block image services
-   VPN connections might interfere
-   Browser extensions could block images

### **Test Network Connectivity**

```bash
# Test basic connectivity
ping picsum.photos

# Test HTTP connectivity
curl -I https://picsum.photos

# Test from different network
# Try from mobile hotspot or different WiFi
```

## ✅ **Verification Steps**

After implementing fixes, verify:

1. **Database has designs:**

    ```bash
    php artisan tinker
    >>> App\Models\Design::count(); // Should be > 0
    ```

2. **Images are accessible:**

    ```bash
    php artisan designs:test-images --limit=3
    # Should show ✅ OK for all images
    ```

3. **Frontend displays images:**

    - Visit `/designs/demo`
    - Check if design cards show images
    - Look for any console errors

4. **Appointment form works:**
    - Visit `/appointments/create`
    - Complete steps 1-2
    - Check if Step 3 shows design selector with images

## 🆘 **Still Having Issues?**

If none of the above solutions work:

1. **Check system requirements:**

    - PHP version (8.0+)
    - Laravel version (9+)
    - Livewire version (3+)

2. **Verify file permissions:**

    ```bash
    chmod -R 755 storage/
    chmod -R 755 bootstrap/cache/
    ```

3. **Clear all caches:**

    ```bash
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    ```

4. **Check for conflicts:**

    - Other image-related packages
    - Custom middleware
    - Server configuration

5. **Create a minimal test:**

    ```php
    // In a simple route
    Route::get('/test-image', function() {
        $design = App\Models\Design::first();
        return view('test-image', compact('design'));
    });
    ```

    ```php
    <!-- resources/views/test-image.blade.php -->
    <img src="{{ $design->thumbnail_url }}" alt="Test">
    ```

---

**Remember:** The most common cause is that the seeder hasn't been run or the image URLs are blocked by network policies. Start with the basic database checks and work your way up to network-level troubleshooting.
