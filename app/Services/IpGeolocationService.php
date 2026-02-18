<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IpGeolocationService
{
    /**
     * Get location information for an IP address
     * Uses ipapi.co free API (1000 requests per day)
     * 
     * @param string $ip
     * @return array
     */
    public function getLocation(string $ip): array
    {
        // Return default for localhost/private IPs
        if ($this->isLocalOrPrivateIp($ip)) {
            return [
                'city' => 'Local',
                'region' => 'Development',
                'country' => 'Localhost',
                'country_code' => 'LC',
            ];
        }

        // Check cache first (cache for 24 hours)
        $cacheKey = 'ip_location_' . $ip;
        
        return Cache::remember($cacheKey, 86400, function () use ($ip) {
            try {
                // Use ipapi.co free API
                $response = Http::timeout(5)->get("https://ipapi.co/{$ip}/json/");
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Check if we got valid data
                    if (isset($data['city']) && !isset($data['error'])) {
                        return [
                            'city' => $data['city'] ?? 'Unknown',
                            'region' => $data['region'] ?? 'Unknown',
                            'country' => $data['country_name'] ?? 'Unknown',
                            'country_code' => $data['country_code'] ?? 'XX',
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error('IP Geolocation failed: ' . $e->getMessage());
            }
            
            // Fallback if API fails
            return [
                'city' => 'Unknown',
                'region' => 'Unknown',
                'country' => 'Unknown',
                'country_code' => 'XX',
            ];
        });
    }

    /**
     * Check if IP is localhost or private
     * 
     * @param string $ip
     * @return bool
     */
    private function isLocalOrPrivateIp(string $ip): bool
    {
        // Localhost
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return true;
        }

        // Private IP ranges
        $private_ranges = [
            '10.0.0.0|10.255.255.255',
            '172.16.0.0|172.31.255.255',
            '192.168.0.0|192.168.255.255',
        ];

        $long_ip = ip2long($ip);
        if ($long_ip === false) {
            return true; // Invalid IP, treat as local
        }

        foreach ($private_ranges as $range) {
            list($start, $end) = explode('|', $range);
            if ($long_ip >= ip2long($start) && $long_ip <= ip2long($end)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parse user agent to get browser and OS information
     * 
     * @param string $userAgent
     * @return array
     */
    public function parseUserAgent(string $userAgent): array
    {
        $browser = $this->getBrowser($userAgent);
        $os = $this->getOperatingSystem($userAgent);
        $device = $this->getDeviceType($userAgent);

        return [
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
        ];
    }

    /**
     * Get browser name from user agent
     * 
     * @param string $userAgent
     * @return string
     */
    private function getBrowser(string $userAgent): string
    {
        $browsers = [
            'Edge' => 'Edg',
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
            'Opera' => 'OPR',
            'Internet Explorer' => 'MSIE|Trident',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match("/{$pattern}/i", $userAgent)) {
                return $name;
            }
        }

        return 'Unknown Browser';
    }

    /**
     * Get operating system from user agent
     * 
     * @param string $userAgent
     * @return string
     */
    private function getOperatingSystem(string $userAgent): string
    {
        $os_array = [
            'Windows 11' => 'Windows NT 10.0.*Win64',
            'Windows 10' => 'Windows NT 10.0',
            'Windows 8.1' => 'Windows NT 6.3',
            'Windows 8' => 'Windows NT 6.2',
            'Windows 7' => 'Windows NT 6.1',
            'Mac OS X' => 'Mac OS X',
            'macOS' => 'Macintosh',
            'Linux' => 'Linux',
            'Ubuntu' => 'Ubuntu',
            'iPhone' => 'iPhone',
            'iPad' => 'iPad',
            'Android' => 'Android',
        ];

        foreach ($os_array as $name => $pattern) {
            if (preg_match("/{$pattern}/i", $userAgent)) {
                return $name;
            }
        }

        return 'Unknown OS';
    }

    /**
     * Get device type from user agent
     * 
     * @param string $userAgent
     * @return string
     */
    private function getDeviceType(string $userAgent): string
    {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $userAgent)) {
            return 'Tablet';
        }

        if (preg_match('/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile/', $userAgent)) {
            return 'Mobile';
        }

        return 'Desktop';
    }
}
