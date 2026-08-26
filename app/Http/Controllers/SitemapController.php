<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'active')->get();
        $categories = Category::all();

        $content = '<?xml version="1.0" encoding="UTF-8"?>';
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Home
        $content .= '<url>';
        $content .= '<loc>' . route('home') . '</loc>';
        $content .= '<changefreq>daily</changefreq>';
        $content .= '<priority>1.0</priority>';
        $content .= '</url>';

        // Shop / Categories
        $content .= '<url>';
        $content .= '<loc>' . route('shop') . '</loc>';
        $content .= '<changefreq>daily</changefreq>';
        $content .= '<priority>0.9</priority>';
        $content .= '</url>';

        foreach ($categories as $category) {
            $content .= '<url>';
            $content .= '<loc>' . route('shop', ['category' => $category->slug]) . '</loc>';
            $content .= '<changefreq>weekly</changefreq>';
            $content .= '<priority>0.8</priority>';
            $content .= '</url>';
        }

        // Products
        foreach ($products as $product) {
            $content .= '<url>';
            $content .= '<loc>' . route('product.detail', $product->slug) . '</loc>';
            if ($product->updated_at) {
                $content .= '<lastmod>' . $product->updated_at->tz('UTC')->toAtomString() . '</lastmod>';
            }
            $content .= '<changefreq>weekly</changefreq>';
            $content .= '<priority>0.7</priority>';
            $content .= '</url>';
        }

        $content .= '</urlset>';

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
