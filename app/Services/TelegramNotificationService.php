<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    /**
     * Get the Telegram Bot token from config or env.
     */
    public static function getToken(): ?string
    {
        return config('services.telegram.token') 
            ?? config('services.telegram.client_secret') 
            ?? env('TELEGRAM_BOT_TOKEN') 
            ?? env('TELEGRAM_TOKEN');
    }

    /**
     * Get the primary Telegram Chat ID (Admin or Channel) from config or env.
     */
    public static function getChatId(): ?string
    {
        return config('services.telegram.chat_id') ?? env('TELEGRAM_CHAT_ID');
    }

    /**
     * Send a general text message to a specific chat ID.
     */
    public static function sendMessage(?string $chatId, string $message, string $parseMode = 'HTML'): bool
    {
        $token = self::getToken();
        $targetChatId = $chatId ?: self::getChatId();

        if (empty($token) || empty($targetChatId)) {
            Log::info('Telegram notification skipped: Bot token or Chat ID is not configured in .env.');
            return false;
        }

        try {
            $response = Http::timeout(5)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $targetChatId,
                'text' => $message,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => true,
            ]);

            if ($response->successful() && $response->json('ok') === true) {
                return true;
            }

            Log::warning('Telegram send failed: ' . $response->body());
            return false;
        } catch (\Throwable $e) {
            Log::error('Telegram notification exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Resolve IP address and/or device GPS coordinates to real physical location.
     */
    public static function resolveLocationInfo(?string $ip, float|int|string|null $clientLat = null, float|int|string|null $clientLng = null): array
    {
        // 1. Prioritize real device GPS coordinates if provided by client browser/device
        if (!empty($clientLat) && !empty($clientLng) && is_numeric($clientLat) && is_numeric($clientLng) && (float)$clientLat != 0.0) {
            $lat = round((float) $clientLat, 5);
            $lng = round((float) $clientLng, 5);
            $geo = GeoLocationService::reverseGeocode($lat, $lng);
            $address = $geo['short_name'] ?? ($geo['full_address'] ?? "{$lat}, {$lng}");

            return [
                'ip' => $ip ?: '127.0.0.1',
                'real_ip' => $ip ?: '127.0.0.1',
                'latitude' => $lat,
                'longitude' => $lng,
                'address' => $address,
                'map_url' => "https://www.google.com/maps?q={$lat},{$lng}",
                'source' => '📱 Real Device GPS',
                'isp' => null,
            ];
        }

        // 2. Resolve via Real Public WAN IP
        $rawIp = $ip ?: '127.0.0.1';
        $isLocal = empty($ip) 
            || in_array($ip, ['127.0.0.1', '::1', 'localhost']) 
            || str_starts_with($ip, '192.168.') 
            || str_starts_with($ip, '10.');

        $targetIp = $rawIp;
        $wanIp = null;

        if ($isLocal) {
            try {
                $wanIp = \Illuminate\Support\Facades\Cache::remember('real_wan_ip', now()->addHours(6), function () {
                    $resp = Http::timeout(2.5)->get('https://api.ipify.org?format=json');
                    return $resp->successful() ? $resp->json('ip') : null;
                });
                if ($wanIp) {
                    $targetIp = $wanIp;
                }
            } catch (\Throwable $e) {}
        }

        // Query real IP geolocation API
        $lat = null;
        $lng = null;
        $city = null;
        $country = null;
        $isp = null;

        try {
            $ipResp = Http::timeout(2.5)->get("http://ip-api.com/json/{$targetIp}");
            if ($ipResp->successful() && $ipResp->json('status') === 'success') {
                $data = $ipResp->json();
                $lat = (float) $data['lat'];
                $lng = (float) $data['lon'];
                $city = $data['city'] ?? 'Phnom Penh';
                $country = $data['country'] ?? 'Cambodia';
                $isp = $data['isp'] ?? ($data['org'] ?? null);
            }
        } catch (\Throwable $e) {}

        if ($lat === null) {
            try {
                $ipInfoResp = Http::timeout(2.5)->get("https://ipinfo.io/{$targetIp}/json");
                if ($ipInfoResp->successful() && $ipInfoResp->json('loc')) {
                    $locParts = explode(',', $ipInfoResp->json('loc'));
                    if (count($locParts) === 2) {
                        $lat = (float) $locParts[0];
                        $lng = (float) $locParts[1];
                        $city = $ipInfoResp->json('city');
                        $country = $ipInfoResp->json('country');
                        $isp = $ipInfoResp->json('org');
                    }
                }
            } catch (\Throwable $e) {}
        }

        if ($lat !== null && $lng !== null) {
            $geo = GeoLocationService::reverseGeocode($lat, $lng);
            $detailedAddress = $geo['short_name'] ?? ($geo['full_address'] ?? null);
            $address = $detailedAddress ?: trim("{$city}, {$country}");

            return [
                'ip' => $wanIp ? "{$targetIp} (Local: {$rawIp})" : $targetIp,
                'real_ip' => $targetIp,
                'latitude' => $lat,
                'longitude' => $lng,
                'address' => $address,
                'map_url' => "https://www.google.com/maps?q={$lat},{$lng}",
                'source' => '🌐 Real Network Geolocation',
                'isp' => $isp,
            ];
        }

        // Fallback Phnom Penh real coordinates
        return [
            'ip' => $targetIp,
            'real_ip' => $targetIp,
            'latitude' => 11.5583,
            'longitude' => 104.9121,
            'address' => 'Phnom Penh, Cambodia',
            'map_url' => 'https://www.google.com/maps?q=11.5583,104.9121',
            'source' => '🌐 Phnom Penh, Cambodia',
            'isp' => null,
        ];
    }

    /**
     * Push a login notification to Telegram when an employee or admin logs in.
     */
    public static function sendLoginNotification(User $user, ?Request $request = null): void
    {
        try {
            $ip = $request ? $request->ip() : request()->ip();
            $userAgent = $request ? $request->userAgent() : request()->userAgent();
            $device = self::parseUserAgent($userAgent);
            $roleLabel = $user->isAdmin() ? '👑 <b>Administrator</b>' : '👤 <b>Employee</b>';
            $now = now()->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i:s T');

            $appName = config('app.name', 'AttendX');
            $clientLat = $request ? $request->input('latitude') : null;
            $clientLng = $request ? $request->input('longitude') : null;
            $loc = self::resolveLocationInfo($ip, $clientLat, $clientLng);

            $text = "🔐 🟢 <b>[{$appName}] User Login Alert</b>\n\n"
                  . "👤 <b>Name:</b> " . htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') . "\n"
                  . "📧 <b>Email:</b> <code>" . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . "</code>\n"
                  . "🛡️ <b>Role:</b> {$roleLabel}\n"
                  . "🕒 <b>Time:</b> {$now}\n"
                  . "🌐 <b>IP Address:</b> <code>" . htmlspecialchars($loc['ip'], ENT_QUOTES, 'UTF-8') . "</code>\n"
                  . ($loc['isp'] ? "📡 <b>ISP / Network:</b> " . htmlspecialchars($loc['isp'], ENT_QUOTES, 'UTF-8') . "\n" : "")
                  . "📍 <b>Real Location:</b> <a href=\"{$loc['map_url']}\">" . htmlspecialchars($loc['address'], ENT_QUOTES, 'UTF-8') . "</a>\n"
                  . "🧭 <b>Location Source:</b> " . htmlspecialchars($loc['source'], ENT_QUOTES, 'UTF-8') . "\n"
                  . "🗺️ <b>Live Map:</b> <a href=\"{$loc['map_url']}\">Open in Google Maps ↗</a>\n"
                  . "💻 <b>Device:</b> " . htmlspecialchars($device, ENT_QUOTES, 'UTF-8') . "\n\n"
                  . "✅ <i>Session started successfully.</i>";

            // 1. Send to primary admin chat / channel if configured
            $primaryChatId = self::getChatId();
            if (!empty($primaryChatId)) {
                self::sendMessage($primaryChatId, $text);
            }

            // 2. Also send to user's personal Telegram if they linked their account
            if (!empty($user->telegram_id) && $user->telegram_id !== $primaryChatId) {
                $userText = "👋 <b>Security Notice</b>\n\n"
                          . "You have logged into <b>{$appName}</b> on {$now}.\n"
                          . "🌐 <b>IP:</b> <code>" . htmlspecialchars($loc['ip'], ENT_QUOTES, 'UTF-8') . "</code>\n"
                          . "📍 <b>Location:</b> <a href=\"{$loc['map_url']}\">" . htmlspecialchars($loc['address'], ENT_QUOTES, 'UTF-8') . "</a>\n"
                          . "💻 <b>Device:</b> " . htmlspecialchars($device, ENT_QUOTES, 'UTF-8') . "\n\n"
                          . "If this was not you, please contact your administrator immediately.";
                self::sendMessage($user->telegram_id, $userText);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed sending Telegram login alert: ' . $e->getMessage());
        }
    }

    /**
     * Push a notification when a new user registers and awaits approval.
     */
    public static function sendRegistrationNotification(User $user, ?Request $request = null): void
    {
        try {
            $ip = $request ? $request->ip() : request()->ip();
            $userAgent = $request ? $request->userAgent() : request()->userAgent();
            $device = self::parseUserAgent($userAgent);
            $clientLat = $request ? $request->input('latitude') : null;
            $clientLng = $request ? $request->input('longitude') : null;
            $now = now()->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i:s T');
            $appName = config('app.name', 'AttendX');
            $loc = self::resolveLocationInfo($ip, $clientLat, $clientLng);

            $text = "⏳ 🔴 <b>[{$appName}] New User Registration Pending Approval</b>\n\n"
                  . "👤 <b>Name:</b> " . htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') . "\n"
                  . "📧 <b>Email:</b> <code>" . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . "</code>\n"
                  . "🕒 <b>Registered:</b> {$now}\n"
                  . "🌐 <b>IP Address:</b> <code>" . htmlspecialchars($loc['ip'], ENT_QUOTES, 'UTF-8') . "</code>\n"
                  . ($loc['isp'] ? "📡 <b>ISP / Network:</b> " . htmlspecialchars($loc['isp'], ENT_QUOTES, 'UTF-8') . "\n" : "")
                  . "📍 <b>Real Location:</b> <a href=\"{$loc['map_url']}\">" . htmlspecialchars($loc['address'], ENT_QUOTES, 'UTF-8') . "</a>\n"
                  . "🧭 <b>Location Source:</b> " . htmlspecialchars($loc['source'], ENT_QUOTES, 'UTF-8') . "\n"
                  . "🗺️ <b>Live Map:</b> <a href=\"{$loc['map_url']}\">Open in Google Maps ↗</a>\n"
                  . "💻 <b>Device:</b> " . htmlspecialchars($device, ENT_QUOTES, 'UTF-8') . "\n\n"
                  . "👉 <i>Please review and approve this user in the Admin Employees Directory.</i>";

            $primaryChatId = self::getChatId();
            if (!empty($primaryChatId)) {
                self::sendMessage($primaryChatId, $text);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed sending Telegram registration alert: ' . $e->getMessage());
        }
    }

    /**
     * Query getUpdates from Telegram Bot API to detect chat IDs from recent messages.
     * Returns an array of detected chats: [['chat_id' => '...', 'name' => '...', 'username' => '...', 'text' => '...']].
     */
    public static function getRecentChatUpdates(): array
    {
        $token = self::getToken();
        if (empty($token)) {
            return [];
        }

        try {
            $response = Http::timeout(5)->get("https://api.telegram.org/bot{$token}/getUpdates");
            if (!$response->successful() || $response->json('ok') !== true) {
                return [];
            }

            $results = $response->json('result', []);
            $chats = [];

            foreach (array_reverse($results) as $item) {
                $msg = $item['message'] ?? $item['channel_post'] ?? null;
                if ($msg && isset($msg['chat']['id'])) {
                    $chat = $msg['chat'];
                    $chatId = (string) $chat['id'];
                    if (!isset($chats[$chatId])) {
                        $chats[$chatId] = [
                            'chat_id' => $chatId,
                            'type' => $chat['type'] ?? 'private',
                            'name' => trim(($chat['first_name'] ?? '') . ' ' . ($chat['last_name'] ?? '')) ?: ($chat['title'] ?? 'Unknown'),
                            'username' => $chat['username'] ?? '',
                            'last_message' => $msg['text'] ?? '',
                            'date' => isset($msg['date']) ? date('Y-m-d H:i:s', $msg['date']) : '',
                        ];
                    }
                }
            }

            return array_values($chats);
        } catch (\Throwable $e) {
            Log::warning('Telegram getUpdates error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Helper to parse user agent into a human-readable browser & platform.
     */
    protected static function parseUserAgent(?string $ua): string
    {
        if (empty($ua)) {
            return 'Unknown Device';
        }

        $platform = 'Unknown OS';
        if (str_contains($ua, 'Windows')) $platform = 'Windows';
        elseif (str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS')) $platform = 'macOS';
        elseif (str_contains($ua, 'iPhone')) $platform = 'iPhone';
        elseif (str_contains($ua, 'iPad')) $platform = 'iPad';
        elseif (str_contains($ua, 'Android')) $platform = 'Android';
        elseif (str_contains($ua, 'Linux')) $platform = 'Linux';

        $browser = 'Browser';
        if (str_contains($ua, 'Edg/')) $browser = 'Edge';
        elseif (str_contains($ua, 'Chrome/')) $browser = 'Chrome';
        elseif (str_contains($ua, 'Firefox/')) $browser = 'Firefox';
        elseif (str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome/')) $browser = 'Safari';

        return "{$browser} on {$platform}";
    }
}
