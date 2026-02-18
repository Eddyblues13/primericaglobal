<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NewsService
{
    /**
     * Fetch market news from RSS feeds (completely free, no API key)
     */
    public function getMarketNews($limit = 6)
    {
        // Try cache first
        $cached = Cache::get('market_news');
        if ($cached && count($cached) > 0) {
            return $cached;
        }
        
        // Try to fetch from RSS
        try {
            $news = $this->getYahooFinanceNews($limit);
            
            if (count($news) >= $limit) {
                Cache::put('market_news', $news, 3600);
                return $news;
            }
        } catch (\Exception $e) {
            Log::error("Market news fetch failed: " . $e->getMessage());
        }
        
        // Always return mock news as fallback
        $mockNews = $this->getMockNews($limit);
        Cache::put('market_news', $mockNews, 3600);
        return $mockNews;
    }

    /**
     * Fetch news from Yahoo Finance RSS feed
     */
    private function getYahooFinanceNews($limit = 6)
    {
        try {
            $rssUrl = 'https://feeds.finance.yahoo.com/rss/2.0/headline';
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($rssUrl);
            
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                if ($xml && isset($xml->channel->item)) {
                    $news = [];
                    $count = 0;
                    foreach ($xml->channel->item as $item) {
                        if ($count >= $limit) break;
                        
                        $title = (string)$item->title;
                        $description = (string)($item->description ?? '');
                        $link = (string)$item->link;
                        $pubDate = (string)($item->pubDate ?? now()->toRssString());
                        
                        // Extract ticker from title if possible
                        $ticker = null;
                        if (preg_match('/\b([A-Z]{2,5})\b/', $title, $matches)) {
                            $ticker = $matches[1];
                        }
                        
                        $news[] = [
                            'title' => $title,
                            'description' => strip_tags($description),
                            'url' => $link,
                            'source' => 'Yahoo Finance',
                            'published_at' => $pubDate,
                            'image' => null,
                            'sentiment' => $this->determineSentiment($title . ' ' . $description),
                            'ticker' => $ticker,
                        ];
                        $count++;
                    }
                    return $news;
                }
            }
        } catch (\Exception $e) {
            Log::error("Yahoo Finance RSS fetch failed: " . $e->getMessage());
        }
        
        return [];
    }

    /**
     * Get mock news as fallback
     */
    private function getMockNews($limit = 6)
    {
        $mockNews = [
            [
                'title' => 'Tesla Announces Record-Breaking Q4 Deliveries',
                'description' => 'Tesla delivered over 1.8 million vehicles in Q4 2025, marking another record quarter and demonstrating strong global demand for electric vehicles.',
                'url' => '#',
                'source' => 'Market Watch',
                'published_at' => now()->subHours(2)->toIso8601String(),
                'image' => asset('images/news-1.jpg'),
                'sentiment' => 'Very Positive',
                'ticker' => 'TSLA',
            ],
            [
                'title' => 'Apple Reports Strong iPhone Sales Despite Market Challenges',
                'description' => 'Apple Inc. continues to show resilience in the smartphone market with strong iPhone 15 Pro sales driving revenue growth.',
                'url' => '#',
                'source' => 'Tech News',
                'published_at' => now()->subHours(5)->toIso8601String(),
                'image' => asset('images/news-2.jpg'),
                'sentiment' => 'Positive',
                'ticker' => 'AAPL',
            ],
            [
                'title' => 'Microsoft Cloud Services See Massive Growth',
                'description' => 'Microsoft Azure and Office 365 subscriptions continue to drive significant revenue growth for the tech giant.',
                'url' => '#',
                'source' => 'Business Insider',
                'published_at' => now()->subHours(8)->toIso8601String(),
                'image' => asset('images/news-3.jpg'),
                'sentiment' => 'Very Positive',
                'ticker' => 'MSFT',
            ],
            [
                'title' => 'NVIDIA AI Chips in High Demand',
                'description' => 'NVIDIA continues to dominate the AI chip market with record-breaking sales of its latest GPU technology.',
                'url' => '#',
                'source' => 'Financial Times',
                'published_at' => now()->subHours(12)->toIso8601String(),
                'image' => asset('images/news-4.jpg'),
                'sentiment' => 'Very Positive',
                'ticker' => 'NVDA',
            ],
            [
                'title' => 'Amazon Expands Logistics Network Globally',
                'description' => 'Amazon announces major expansion of its fulfillment centers and delivery infrastructure across multiple continents.',
                'url' => '#',
                'source' => 'Reuters',
                'published_at' => now()->subHours(15)->toIso8601String(),
                'image' => asset('images/news-1.jpg'),
                'sentiment' => 'Positive',
                'ticker' => 'AMZN',
            ],
            [
                'title' => 'Meta Platforms Invests Heavily in VR Technology',
                'description' => 'Meta continues its commitment to the metaverse with significant investments in virtual and augmented reality development.',
                'url' => '#',
                'source' => 'The Verge',
                'published_at' => now()->subHours(18)->toIso8601String(),
                'image' => asset('images/news-2.jpg'),
                'sentiment' => 'Neutral',
                'ticker' => 'META',
            ],
        ];
        
        return array_slice($mockNews, 0, $limit);
    }

    /**
     * Format NewsAPI articles
     */
    private function formatNewsArticles(array $articles)
    {
        $formatted = [];
        foreach ($articles as $article) {
            if (isset($article['title']) && isset($article['url'])) {
                $formatted[] = [
                    'title' => $article['title'],
                    'description' => $article['description'] ?? $article['content'] ?? '',
                    'url' => $article['url'],
                    'source' => $article['source']['name'] ?? 'Unknown',
                    'published_at' => $article['publishedAt'] ?? now()->toIso8601String(),
                    'image' => $article['urlToImage'] ?? null,
                    'sentiment' => $this->determineSentiment($article['title'] . ' ' . ($article['description'] ?? '')),
                ];
            }
        }
        return $formatted;
    }


    /**
     * Simple sentiment analysis based on keywords
     */
    private function determineSentiment($text)
    {
        $positive = ['positive', 'gain', 'rise', 'up', 'bullish', 'growth', 'profit', 'success', 'strong'];
        $negative = ['negative', 'loss', 'fall', 'down', 'bearish', 'decline', 'loss', 'weak', 'drop'];
        
        $text = strtolower($text);
        $positiveCount = 0;
        $negativeCount = 0;
        
        foreach ($positive as $word) {
            if (strpos($text, $word) !== false) {
                $positiveCount++;
            }
        }
        
        foreach ($negative as $word) {
            if (strpos($text, $word) !== false) {
                $negativeCount++;
            }
        }
        
        if ($positiveCount > $negativeCount + 1) {
            return 'Very Positive';
        } elseif ($positiveCount > $negativeCount) {
            return 'Positive';
        } elseif ($negativeCount > $positiveCount + 1) {
            return 'Very Negative';
        } elseif ($negativeCount > $positiveCount) {
            return 'Negative';
        }
        
        return 'Neutral';
    }
}
