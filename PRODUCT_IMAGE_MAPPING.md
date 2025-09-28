# Product Image Mapping

This document shows the mapping between products and their corresponding images from the ProductImageSeeder.

## Direct Product-Image Mappings

| Product ID | Product Name (Arabic) | Image File | Description |
|------------|----------------------|------------|-------------|
| 40 | بوث معارض | `1758452511_0_بوث.jpg` | Event booth design and printing |
| 41 | طاولة دعائية | `1758453822_0_طاولة--.jpg` | Promotional table |
| 45 | لوحة بنر | `1758518421_0_بنر.png` | Banner board |
| 46 | بوب اب | `1758518679_0_بوب اب.png` | Pop-up display |
| 47 | رول اب | `1758519504_0_رول اب.png` | Roll-up banner |
| 49 | بروش اسم | `1758519638_0_بروش اســم.png` | Name badge (design 1) |
| 49 | بروش اسم | `1758519642_0_بروش اســم.png` | Name badge (design 2) |
| 50 | تشيرتات | `1758519793_0_تيشترتات.jpg` | T-shirts printing |
| 51 | كابات | `1758520853_0_كابات.png` | Caps printing |
| 52 | بطاقات تعريفية | `1758521690_0_بطائق اي دي.png` | ID cards |
| 53 | طاولة دعائية مع مضلة | `1758522227_0_طاولة دعائية مع مظلة .png` | Promotional table with umbrella |
| 54 | طاولة قابلة للطي | `1758522487_0_طاولة قـــابلـــة للطـــي .png` | Foldable table |
| 55 | طاولة دعائية مقوسة | `1758522795_0_طاولة دعائية مقوسة .png` | Curved promotional table |
| 56 | استاند باك دروب | `1758523555_0_باك دورب.png` | Backdrop stand |
| 57 | طباعة على شنط | `1758524944_0_شنطة.jpg` | Bag printing |
| 58 | كاسات سيراميك | `1758525377_0_مج سيراميك.jpg` | Ceramic mugs |
| 59 | مج ستيل | `1758525838_0_مح اسيتل.jpg` | Steel mug |

## Category-Based Image Assignments

### Restaurant/Food Category (Category ID: 2)
- Food packaging items (18 images)
- Coffee bags, meal boxes, plastic bags, etc.

### Promotional Gifts Category (Category ID: 1)
- Various promotional items (10 images)
- Notebooks, medals, necklaces, flags, etc.

### Advertising Category (Category ID: 9)
- Advertising materials (4 images)
- Car stickers, stands, stamps, etc.

### Government Sector Category (Category ID: 4)
- Official documents (7 images)
- Bonds, envelopes, invoices, coupons, magazines, etc.

## Database Structure

The `product_images` table contains:
- `id`: Primary key
- `product_id`: Foreign key to products table
- `image_path`: Path to the image file
- `alt_text`: Alternative text for accessibility
- `is_primary`: Boolean indicating if this is the primary image
- `sort_order`: Order for displaying multiple images
- `created_at`, `updated_at`: Timestamps

## Usage

To run the seeders:
```bash
php artisan db:seed --class=ProductImageSeeder
php artisan db:seed --class=AdditionalProductImageSeeder
```

Or run all seeders:
```bash
php artisan db:seed
```

## Image Storage

All images are stored in the `storage/products/` directory and referenced with the `storage/products/` prefix in the database.
