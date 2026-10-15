<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Throwable;

class GeoLocationService
{
    // Default Organization / Campus Coordinates (SETEC Institute, Phnom Penh, Cambodia)
    public const DEFAULT_LAT = 11.5454;
    public const DEFAULT_LNG = 104.8893;
    public const DEFAULT_ADDRESS = 'Sangkat Stueng Mean Chey 1, Phnom Penh';

    /**
     * Resolve location array from coordinates or IP address.
     * Always guarantees valid coordinates and human-readable address.
     */
    public static function resolveLocation(?string $ip = null, float|int|string|null $latitude = null, float|int|string|null $longitude = null): array
    {
        $lat = !empty($latitude) && is_numeric($latitude) ? (float) $latitude : null;
        $lng = !empty($longitude) && is_numeric($longitude) ? (float) $longitude : null;
        $source = 'browser_geolocation';

        // If coordinates not provided, resolve via IP or organization default
        if ($lat === null || $lng === null) {
            $isLocal = empty($ip) || in_array($ip, ['127.0.0.1', '::1', 'localhost', 'Local']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.');

            if (!$isLocal) {
                // Try resolving public IP via ip-api
                try {
                    $ipResponse = Http::timeout(2.5)->get("http://ip-api.com/json/{$ip}");
                    if ($ipResponse->successful() && $ipResponse->json('status') === 'success') {
                        $lat = (float) $ipResponse->json('lat');
                        $lng = (float) $ipResponse->json('lon');
                        $source = 'ip_geolocation';
                    }
                } catch (Throwable $e) {}
            }

            // Fallback for localhost (127.0.0.1) or failed IP lookup: default campus coordinates
            if ($lat === null || $lng === null) {
                $lat = self::DEFAULT_LAT;
                $lng = self::DEFAULT_LNG;
                $source = $isLocal ? 'local_network_resolved' : 'default_workplace';
            }
        }

        $geo = self::reverseGeocode($lat, $lng);

        return [
            'latitude' => $lat,
            'longitude' => $lng,
            'address' => $geo['short_name'] ?? self::DEFAULT_ADDRESS,
            'full_address' => $geo['full_address'] ?? self::DEFAULT_ADDRESS . ', Cambodia',
            'ip' => $ip,
            'source' => $source,
        ];
    }

    /**
     * Reverse geocode latitude and longitude to human-readable address.
     * Caches results to prevent hitting rate limits and avoid repeated network calls.
     */
    public static function reverseGeocode(float|int|string|null $latitude, float|int|string|null $longitude): ?array
    {
        if (empty($latitude) || empty($longitude)) {
            return null;
        }

        $lat = round((float) $latitude, 4);
        $lng = round((float) $longitude, 4);
        $cacheKey = "reverse_geo_{$lat}_{$lng}";

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($lat, $lng) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'NTIA-Attendance-System/1.0 (internal-hr@setec.edu.kh)',
                    'Accept-Language' => 'en,km',
                ])
                ->timeout(2.5)
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat' => $lat,
                    'lon' => $lng,
                    'format' => 'json',
                    'zoom' => 16,
                    'addressdetails' => 1,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $addressDetails = $data['address'] ?? [];

                    // Construct a clean, readable short location
                    $parts = array_filter([
                        $addressDetails['suburb'] ?? $addressDetails['village'] ?? $addressDetails['neighbourhood'] ?? null,
                        $addressDetails['city_district'] ?? $addressDetails['county'] ?? null,
                        $addressDetails['city'] ?? $addressDetails['state'] ?? 'Phnom Penh',
                    ]);

                    $shortName = !empty($parts) ? implode(', ', $parts) : ($data['name'] ?? null);

                    return [
                        'short_name' => $shortName ?: ($data['display_name'] ?? "{$lat}, {$lng}"),
                        'full_address' => $data['display_name'] ?? null,
                        'city' => $addressDetails['city'] ?? $addressDetails['state'] ?? null,
                        'country' => $addressDetails['country'] ?? 'Cambodia',
                    ];
                }
            } catch (Throwable $e) {
                // In case of timeout or offline, fail gracefully
            }

            return null;
        });
    }

    /**
     * Helper to get a short formatted address string.
     */
    public static function getShortAddress(float|int|string|null $latitude, float|int|string|null $longitude): ?string
    {
        $geo = self::reverseGeocode($latitude, $longitude);
        return $geo['short_name'] ?? null;
    }
}
