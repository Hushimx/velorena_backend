<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Design;

class DesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designs = [
            [
                'external_id' => 'sample_001',
                'title' => 'Modern Business Card Design',
                'description' => 'Clean and professional business card design with modern typography',
                'image_url' => 'https://picsum.photos/800/600?random=1',
                'thumbnail_url' => 'https://picsum.photos/300/300?random=1',
                'category' => 'business',
                'tags' => 'business,card,modern,professional',
                'metadata' => [
                    'style' => 'modern',
                    'colors' => ['blue', 'white'],
                    'format' => 'business_card'
                ],
                'is_active' => true,
            ],
            [
                'external_id' => 'sample_002',
                'title' => 'Elegant Wedding Invitation',
                'description' => 'Beautiful wedding invitation with floral elements and elegant typography',
                'image_url' => 'https://picsum.photos/800/600?random=2',
                'thumbnail_url' => 'https://picsum.photos/300/300?random=2',
                'category' => 'wedding',
                'tags' => 'wedding,invitation,elegant,floral',
                'metadata' => [
                    'style' => 'elegant',
                    'colors' => ['gold', 'white'],
                    'format' => 'invitation'
                ],
                'is_active' => true,
            ],
            [
                'external_id' => 'sample_003',
                'title' => 'Restaurant Menu Design',
                'description' => 'Creative restaurant menu with modern layout and appetizing visuals',
                'image_url' => 'https://picsum.photos/800/600?random=3',
                'thumbnail_url' => 'https://picsum.photos/300/300?random=3',
                'category' => 'restaurant',
                'tags' => 'restaurant,menu,food,creative',
                'metadata' => [
                    'style' => 'creative',
                    'colors' => ['green', 'white'],
                    'format' => 'menu'
                ],
                'is_active' => true,
            ],
            [
                'external_id' => 'sample_004',
                'title' => 'Tech Company Logo',
                'description' => 'Modern and minimalist logo design for technology companies',
                'image_url' => 'https://picsum.photos/800/600?random=4',
                'thumbnail_url' => 'https://picsum.photos/300/300?random=4',
                'category' => 'logo',
                'tags' => 'logo,tech,minimalist,modern',
                'metadata' => [
                    'style' => 'minimalist',
                    'colors' => ['indigo', 'white'],
                    'format' => 'logo'
                ],
                'is_active' => true,
            ],
            [
                'external_id' => 'sample_005',
                'title' => 'Product Packaging Design',
                'description' => 'Eye-catching product packaging with bold colors and modern graphics',
                'image_url' => 'https://picsum.photos/800/600?random=5',
                'thumbnail_url' => 'https://picsum.photos/300/300?random=5',
                'category' => 'packaging',
                'tags' => 'packaging,product,bold,modern',
                'metadata' => [
                    'style' => 'bold',
                    'colors' => ['red', 'white'],
                    'format' => 'packaging'
                ],
                'is_active' => true,
            ],
        ];

        foreach ($designs as $design) {
            Design::updateOrCreate(
                ['external_id' => $design['external_id']],
                $design
            );
        }

        $this->command->info('Designs seeded successfully!');
    }
}
