<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
  /**
   * Generate XML sitemap for products
   */
  public function index()
  {
    $products = Product::where('is_active', true)
      ->with('category')
      ->orderBy('updated_at', 'desc')
      ->get();

    $categories = Category::where('is_active', true)
      ->orderBy('updated_at', 'desc')
      ->get();

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    // Add homepage
    $xml .= $this->generateUrlElement(config('app.url'), '1.0', 'daily', now()->format('Y-m-d\TH:i:s\Z'));

    // Add products listing page
    $xml .= $this->generateUrlElement(route('user.products.index'), '0.8', 'weekly', now()->format('Y-m-d\TH:i:s\Z'));

    // Add product pages
    foreach ($products as $product) {
      $xml .= $this->generateUrlElement(
        route('user.products.show', $product),
        '0.9',
        'weekly',
        $product->updated_at->format('Y-m-d\TH:i:s\Z')
      );
    }

    // Add category pages (if you have category routes)
    foreach ($categories as $category) {
      $xml .= $this->generateUrlElement(
        route('user.products.index') . '?category=' . $category->id,
        '0.7',
        'weekly',
        $category->updated_at->format('Y-m-d\TH:i:s\Z')
      );
    }

    $xml .= '</urlset>';

    return response($xml, 200)
      ->header('Content-Type', 'application/xml');
  }

  /**
   * Generate a single URL element for sitemap
   */
  private function generateUrlElement($url, $priority, $changefreq, $lastmod)
  {
    return "  <url>\n" .
      "    <loc>{$url}</loc>\n" .
      "    <lastmod>{$lastmod}</lastmod>\n" .
      "    <changefreq>{$changefreq}</changefreq>\n" .
      "    <priority>{$priority}</priority>\n" .
      "  </url>\n";
  }

  /**
   * Generate robots.txt
   */
  public function robots()
  {
    $robots = "User-agent: *\n";
    $robots .= "Allow: /\n";
    $robots .= "Disallow: /admin/\n";
    $robots .= "Disallow: /api/\n";
    $robots .= "Disallow: /storage/\n";
    $robots .= "Disallow: /vendor/\n";
    $robots .= "\n";
    $robots .= "Sitemap: " . route('sitemap.xml') . "\n";

    return response($robots, 200)
      ->header('Content-Type', 'text/plain');
  }
}
