<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class SitemapController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $baseUrl = config('app.url');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Main pages
        $pages = [
            '/',
            '/tienda',
            '/servicios'
        ];

        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . rtrim($baseUrl, '/') . $page . '</loc>';
            $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>' . ($page == '/' ? '1.0' : '0.8') . '</priority>';
            $xml .= '</url>';
        }

        // Category pages
        foreach ($categories as $category) {
            $xml .= '<url>';
            $xml .= '<loc>' . rtrim($baseUrl, '/') . '/tienda?categorySlug=' . $category->slug . '</loc>';
            $xml .= '<lastmod>' . $category->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }
}
