<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeslaCar;
use Carbon\Carbon;

class SeoController extends Controller
{
    /**
     * Generate sitemap.xml
     */
    public function sitemap()
    {
        $urls = [];
        
        // Homepage
        $urls[] = [
            'loc' => route('home'),
            'changefreq' => 'daily',
            'priority' => '1.0',
            'lastmod' => Carbon::now()->toDateString(),
        ];
        
        // Investment page
        $urls[] = [
            'loc' => route('invest'),
            'changefreq' => 'weekly',
            'priority' => '0.9',
        ];
        
        // Stocks page
        $urls[] = [
            'loc' => route('stocks'),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];
        
        // Inventory page
        $urls[] = [
            'loc' => route('inventory'),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];
        
        // Static pages
        $staticPages = [
            ['route' => 'about', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'help', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['route' => 'terms', 'changefreq' => 'yearly', 'priority' => '0.5'],
            ['route' => 'privacy', 'changefreq' => 'yearly', 'priority' => '0.5'],
        ];
        
        foreach ($staticPages as $page) {
            $urls[] = [
                'loc' => route($page['route']),
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ];
        }
        
        // Individual car pages
        $cars = TeslaCar::where('is_available', true)->get();
        foreach ($cars as $car) {
            $urls[] = [
                'loc' => route('inventory.show', $car),
                'changefreq' => 'weekly',
                'priority' => '0.8',
                'lastmod' => $car->updated_at->toDateString(),
            ];
        }
        
        // Generate XML
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
        
        foreach ($urls as $urlData) {
            $url = $xml->addChild('url');
            $url->addChild('loc', htmlspecialchars($urlData['loc']));
            if (isset($urlData['lastmod'])) {
                $url->addChild('lastmod', $urlData['lastmod']);
            }
            $url->addChild('changefreq', $urlData['changefreq']);
            $url->addChild('priority', $urlData['priority']);
        }
        
        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml');
    }
}
