<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\URL;

class SeoService
{
    /**
     * Generate meta tags for a product
     */
    public static function generateProductMetaTags(Product $product)
    {
        return [
            'title' => $product->seo_title,
            'description' => $product->seo_description,
            'keywords' => $product->seo_keywords,
            'canonical' => $product->canonical_url,
            'og_title' => $product->og_title,
            'og_description' => $product->og_description,
            'og_image' => $product->og_image,
            'og_type' => 'product',
            'og_url' => $product->canonical_url,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $product->seo_title,
            'twitter_description' => $product->seo_description,
            'twitter_image' => $product->og_image,
            'robots' => $product->is_active ? 'index, follow' : 'noindex, nofollow',
            'structured_data' => $product->structured_data
        ];
    }

    /**
     * Generate meta tags for products listing page
     */
    public static function generateProductsListingMetaTags($search = null, $category = null)
    {
        $title = 'Products';
        $description = 'Browse our collection of high-quality products. Find the perfect items for your needs.';
        $keywords = 'products, shop, buy, quality, collection';

        if ($search) {
            $title = "Search Results for: {$search}";
            $description = "Search results for '{$search}'. Find the products you're looking for.";
            $keywords .= ", {$search}";
        }

        if ($category) {
            $title = "{$category->name} Products";
            $description = "Browse our collection of {$category->name} products. High-quality items for your needs.";
            $keywords .= ", {$category->name}";
        }

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'canonical' => route('user.products.index'),
            'og_title' => $title,
            'og_description' => $description,
            'og_type' => 'website',
            'og_url' => route('user.products.index'),
            'twitter_card' => 'summary',
            'twitter_title' => $title,
            'twitter_description' => $description,
            'robots' => 'index, follow'
        ];
    }

    /**
     * Generate breadcrumb structured data
     */
    public static function generateBreadcrumbStructuredData($items)
    {
        $breadcrumbList = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];

        foreach ($items as $index => $item) {
            $breadcrumbList['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }

        return $breadcrumbList;
    }

    /**
     * Generate organization structured data
     */
    public static function generateOrganizationStructuredData()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name', 'Velorena'),
            'url' => config('app.url'),
            'logo' => asset('assets/imgs/logo.png'),
            'description' => 'High-quality printing and design services',
            'sameAs' => [
                // Add social media URLs here
            ]
        ];
    }

    /**
     * Generate website structured data
     */
    public static function generateWebsiteStructuredData()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('app.name', 'Velorena'),
            'url' => config('app.url'),
            'description' => 'High-quality printing and design services',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('user.products.index') . '?search={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    /**
     * Generate FAQ structured data
     */
    public static function generateFaqStructuredData($faqs)
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => []
        ];

        foreach ($faqs as $faq) {
            $structuredData['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer']
                ]
            ];
        }

        return $structuredData;
    }

    /**
     * Generate local business structured data
     */
    public static function generateLocalBusinessStructuredData()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => config('app.name', 'Velorena'),
            'description' => 'High-quality printing and design services',
            'url' => config('app.url'),
            'telephone' => '+1-555-123-4567', // Update with actual phone
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '123 Main Street',
                'addressLocality' => 'City',
                'addressRegion' => 'State',
                'postalCode' => '12345',
                'addressCountry' => 'US'
            ],
            'openingHours' => 'Mo-Fr 09:00-17:00',
            'priceRange' => '$$'
        ];
    }

    /**
     * Generate JSON-LD structured data script
     */
    public static function generateJsonLdScript($structuredData)
    {
        return '<script type="application/ld+json">' . json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }

    /**
     * Generate multiple JSON-LD scripts
     */
    public static function generateMultipleJsonLdScripts($structuredDataArray)
    {
        $scripts = '';
        foreach ($structuredDataArray as $data) {
            $scripts .= self::generateJsonLdScript($data);
        }
        return $scripts;
    }
}
