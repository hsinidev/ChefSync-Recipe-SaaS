<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

final class UnsplashService
{
    protected ?string $accessKey;

    public function __construct()
    {
        $this->accessKey = config('services.unsplash.access_key');
    }

    /**
     * Search Unsplash for a photo using query keywords.
     */
    public function searchPhoto(string $query): ?string
    {
        if (empty($this->accessKey)) {
            Log::warning("Unsplash API access key not configured. Using fallback vector.");
            return $this->getFallbackVector();
        }

        try {
            $response = Http::retry(3, 200, function ($exception, $request) {
                return $exception instanceof \Illuminate\Http\Client\ConnectionException 
                    || ($exception instanceof \Illuminate\Http\Client\RequestException && $exception->getCode() === 429);
            })
            ->timeout(10)
            ->get('https://api.unsplash.com/search/photos', [
                'query' => $query,
                'client_id' => $this->accessKey,
                'per_page' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['results'][0]['urls']['regular'] ?? $this->getFallbackVector();
            }

            if ($response->status() === 429) {
                Log::warning("Unsplash API rate limited (429). Serving fallback image.");
                return $this->getFallbackVector();
            }

            Log::error("Unsplash API error: {$response->body()}");
        } catch (Exception $e) {
            Log::error("Unsplash API exception: {$e->getMessage()}");
        }

        return $this->getFallbackVector();
    }

    /**
     * Serve a beautiful fallback/placeholder culinary image.
     */
    public function getFallbackVector(): string
    {
        return 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?w=1200&auto=format&fit=crop';
    }
}
