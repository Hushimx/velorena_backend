# Admin Guide: Category Image Upload

## Quick Start Guide

When creating or editing a category, you now have **three image upload options**:

### 1. 🎠 Slider Image (Required for Sliders)
**Purpose:** Displayed in category sliders on:
- Website home page
- Mobile app home page

**Best Practices:**
- Recommended size: 328x482 pixels (or 164x241 for smaller displays)
- Format: PNG, JPG, WEBP, GIF
- Max size: 2MB
- Should be visually appealing for carousel display

---

### 2. 📱 Main Image (Required for Categories Screen)
**Purpose:** Displayed in:
- Mobile app Categories screen (dedicated page)

**Best Practices:**
- Recommended size: Square aspect ratio (e.g., 400x400 pixels)
- Format: PNG, JPG, WEBP, GIF
- Max size: 2MB
- Should work well with text below it

---

### 3. 🖼️ Legacy Image (Optional)
**Purpose:** Backward compatibility
- Only used if Slider Image or Main Image are not uploaded
- Can be left empty if you upload the other two images

---

## Upload Instructions

### Creating a New Category

1. Go to **Admin → Categories → Create Category**
2. Fill in the required fields (Name, Description)
3. **Upload Slider Image:**
   - Click on "Slider Image" field
   - Select image for sliders
4. **Upload Main Image:**
   - Click on "Main Image" field
   - Select image for mobile categories screen
5. (Optional) Upload legacy image
6. Click **Create Category**

### Editing an Existing Category

1. Go to **Admin → Categories**
2. Click **Edit** on the category you want to update
3. You'll see:
   - Current Slider Image (if exists)
   - Current Main Image (if exists)
   - Current Legacy Image (if exists)
4. Upload new images if you want to replace them
5. Click **Update Category**

---

## Image Display Reference

| Screen | Image Used | Fallback Order |
|--------|-----------|----------------|
| **Web Home Slider** | Slider Image | Legacy Image → Default |
| **Mobile Home Slider** | Slider Image | Legacy Image → Default |
| **Mobile Categories Screen** | Main Image | Legacy Image → Default |

---

## Tips for Best Results

✅ **DO:**
- Upload both Slider Image AND Main Image for best results
- Use high-quality images
- Keep file sizes reasonable (under 500KB ideally)
- Test how images look on both web and mobile

❌ **DON'T:**
- Upload extremely large files (over 2MB will be rejected)
- Use images with important text that might be hard to read when small
- Forget to upload at least one image type

---

## Common Questions

**Q: Do I need to upload all three images?**
A: No! Upload Slider Image + Main Image for best results. Legacy Image is optional.

**Q: What happens if I only upload one image?**
A: The system will use that image for all locations (with fallbacks).

**Q: Can I use the same image for both Slider and Main?**
A: Yes! But for best results, use different images optimized for each purpose.

**Q: What if I don't upload any images?**
A: A default placeholder image will be used.

---

## Need Help?

Contact your system administrator if you encounter any issues with image uploads.
