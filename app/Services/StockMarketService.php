<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class StockMarketService
{
    /**
     * Fetch stock quote using Yahoo Finance (unofficial, no API key needed)
     */
    public function getStockQuote($symbol)
    {
        // Try to get from cache first
        $cached = Cache::get("stock_quote_{$symbol}");
        if ($cached) {
            return $cached;
        }
        
        // Try API with short timeout
        try {
            $url = "https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}?interval=1d&range=1d";
            
            $response = Http::timeout(3)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept' => 'application/json',
                ])
                ->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['chart']['result'][0])) {
                    $result = $data['chart']['result'][0];
                    $meta = $result['meta'] ?? [];
                    $quote = $result['indicators']['quote'][0] ?? [];
                    
                    $currentPrice = $meta['regularMarketPrice'] ?? $meta['chartPreviousClose'] ?? ($quote['close'][0] ?? 0);
                    $previousClose = $meta['previousClose'] ?? $meta['chartPreviousClose'] ?? $currentPrice;
                    $change = $currentPrice - $previousClose;
                    $changePercent = $previousClose > 0 ? ($change / $previousClose) * 100 : 0;
                    $volume = $meta['regularMarketVolume'] ?? ($quote['volume'][0] ?? 0);
                    
                    if ($currentPrice > 0) {
                        $quoteData = [
                            'symbol' => $symbol,
                            'price' => round($currentPrice, 2),
                            'change' => round($change, 2),
                            'change_percent' => round($changePercent, 2),
                            'volume' => intval($volume),
                        ];
                        Cache::put("stock_quote_{$symbol}", $quoteData, 300);
                        return $quoteData;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Stock quote fetch failed for {$symbol}: " . $e->getMessage());
        }
        
        // Return mock data immediately if API fails
        $mockData = $this->getMockStockData($symbol);
        Cache::put("stock_quote_{$symbol}", $mockData, 60);
        return $mockData;
    }

    /**
     * Get mock stock data as fallback
     */
    private function getMockStockData($symbol)
    {
        $mockData = [
            'AAPL' => ['price' => 185.50, 'change' => 2.35, 'change_percent' => 1.28, 'volume' => 45000000],
            'MSFT' => ['price' => 378.85, 'change' => -3.20, 'change_percent' => -0.84, 'volume' => 28000000],
            'GOOGL' => ['price' => 142.30, 'change' => 1.85, 'change_percent' => 1.32, 'volume' => 32000000],
            'AMZN' => ['price' => 152.45, 'change' => -2.10, 'change_percent' => -1.36, 'volume' => 38000000],
            'NVDA' => ['price' => 485.20, 'change' => 12.50, 'change_percent' => 2.64, 'volume' => 55000000],
            'META' => ['price' => 485.75, 'change' => 8.25, 'change_percent' => 1.73, 'volume' => 19000000],
            'TSLA' => ['price' => 248.50, 'change' => -5.20, 'change_percent' => -2.05, 'volume' => 85000000],
            'JPM' => ['price' => 195.30, 'change' => 1.45, 'change_percent' => 0.75, 'volume' => 12000000],
            'V' => ['price' => 275.80, 'change' => -1.20, 'change_percent' => -0.43, 'volume' => 8000000],
            'JNJ' => ['price' => 162.40, 'change' => 0.85, 'change_percent' => 0.53, 'volume' => 7000000],
            'PYPL' => ['price' => 62.30, 'change' => 1.85, 'change_percent' => 3.06, 'volume' => 15000000],
            'NFLX' => ['price' => 485.20, 'change' => 5.50, 'change_percent' => 1.15, 'volume' => 6000000],
        ];
        
        if (isset($mockData[$symbol])) {
            return array_merge(['symbol' => $symbol], $mockData[$symbol]);
        }
        
        return [
            'symbol' => $symbol,
            'price' => 100.00,
            'change' => 0.50,
            'change_percent' => 0.50,
            'volume' => 1000000,
        ];
    }


    /**
     * Get top gainers using Yahoo Finance (unofficial API)
     */
    public function getTopGainers($limit = 5)
    {
        return Cache::remember('top_gainers', 60, function () use ($limit) {
            try {
                // Popular tech and finance stocks
                $symbols = ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'NVDA', 'META', 'TSLA', 'JPM', 'V', 'JNJ', 'PYPL', 'NFLX'];
                $quotes = [];
                
                foreach ($symbols as $symbol) {
                    $quote = $this->getStockQuote($symbol);
                    if ($quote && $quote['change_percent'] > 0) {
                        $quotes[] = $quote;
                    }
                    usleep(50000); // 50ms
                }
                
                // Sort by change_percent descending
                usort($quotes, function($a, $b) {
                    return $b['change_percent'] <=> $a['change_percent'];
                });
                
                $result = array_slice($quotes, 0, $limit);
                
                // If empty, return mock gainers
                if (empty($result)) {
                    $mockGainers = [
                        $this->getMockStockData('NVDA'),
                        $this->getMockStockData('PYPL'),
                        $this->getMockStockData('META'),
                        $this->getMockStockData('NFLX'),
                        $this->getMockStockData('AAPL'),
                    ];
                    return array_slice($mockGainers, 0, $limit);
                }
                
                return $result;
            } catch (\Exception $e) {
                Log::error("Top gainers fetch failed: " . $e->getMessage());
                // Return mock data
                return [
                    $this->getMockStockData('NVDA'),
                    $this->getMockStockData('PYPL'),
                    $this->getMockStockData('META'),
                ];
            }
        });
    }

    /**
     * Get top losers
     */
    public function getTopLosers($limit = 5)
    {
        return Cache::remember('top_losers', 60, function () use ($limit) {
            try {
                $symbols = ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'NVDA', 'META', 'TSLA', 'JPM', 'V', 'JNJ', 'PYPL', 'NFLX'];
                $quotes = [];
                
                foreach ($symbols as $symbol) {
                    $quote = $this->getStockQuote($symbol);
                    if ($quote && $quote['change_percent'] < 0) {
                        $quotes[] = $quote;
                    }
                    usleep(50000); // 50ms
                }
                
                // Sort by change_percent ascending
                usort($quotes, function($a, $b) {
                    return $a['change_percent'] <=> $b['change_percent'];
                });
                
                $result = array_slice($quotes, 0, $limit);
                
                // If empty, return mock losers
                if (empty($result)) {
                    $mockLosers = [
                        array_merge($this->getMockStockData('TSLA'), ['change_percent' => -2.05]),
                        array_merge($this->getMockStockData('AMZN'), ['change_percent' => -1.36]),
                        array_merge($this->getMockStockData('MSFT'), ['change_percent' => -0.84]),
                    ];
                    return array_slice($mockLosers, 0, $limit);
                }
                
                return $result;
            } catch (\Exception $e) {
                Log::error("Top losers fetch failed: " . $e->getMessage());
                // Return mock data
                return [
                    array_merge($this->getMockStockData('TSLA'), ['change_percent' => -2.05]),
                    array_merge($this->getMockStockData('AMZN'), ['change_percent' => -1.36]),
                ];
            }
        });
    }

    /**
     * Get most active stocks
     */
    public function getMostActive($limit = 5)
    {
        return Cache::remember('most_active', 60, function () use ($limit) {
            try {
                $symbols = ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'NVDA', 'META', 'TSLA', 'JPM', 'V', 'JNJ', 'PYPL', 'NFLX'];
                $quotes = [];
                
                foreach ($symbols as $symbol) {
                    $quote = $this->getStockQuote($symbol);
                    if ($quote) {
                        $quotes[] = $quote;
                    }
                    usleep(50000); // 50ms
                }
                
                // Sort by volume descending
                usort($quotes, function($a, $b) {
                    return $b['volume'] <=> $a['volume'];
                });
                
                $result = array_slice($quotes, 0, $limit);
                
                // If empty, return mock data
                if (empty($result)) {
                    return [
                        $this->getMockStockData('TSLA'),
                        $this->getMockStockData('NVDA'),
                        $this->getMockStockData('AMZN'),
                        $this->getMockStockData('AAPL'),
                        $this->getMockStockData('MSFT'),
                    ];
                }
                
                return $result;
            } catch (\Exception $e) {
                Log::error("Most active fetch failed: " . $e->getMessage());
                // Return mock data
                return [
                    $this->getMockStockData('TSLA'),
                    $this->getMockStockData('NVDA'),
                    $this->getMockStockData('AMZN'),
                ];
            }
        });
    }

    /**
     * Get featured stocks
     */
    public function getFeaturedStocks()
    {
        return Cache::remember('featured_stocks', 60, function () {
            try {
                $symbols = ['AAPL', 'MSFT', 'GOOGL'];
                $quotes = [];
                
                foreach ($symbols as $symbol) {
                    $quote = $this->getStockQuote($symbol);
                    if ($quote) {
                        $quotes[$symbol] = $quote;
                    }
                    // Reduced delay
                    usleep(50000); // 50ms
                }
                
                // If no quotes, return mock data
                if (empty($quotes)) {
                    foreach ($symbols as $symbol) {
                        $quotes[$symbol] = $this->getMockStockData($symbol);
                    }
                }
                
                return $quotes;
            } catch (\Exception $e) {
                Log::error("Featured stocks fetch failed: " . $e->getMessage());
                // Return mock data on error
                return [
                    'AAPL' => $this->getMockStockData('AAPL'),
                    'MSFT' => $this->getMockStockData('MSFT'),
                    'GOOGL' => $this->getMockStockData('GOOGL'),
                ];
            }
        });
    }
}
