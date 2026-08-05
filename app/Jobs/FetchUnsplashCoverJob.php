<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Recipe;
use App\Services\UnsplashService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class FetchUnsplashCoverJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Recipe $recipe)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(UnsplashService $unsplash): void
    {
        // Skip if keywords are empty or if the recipe already has a cover
        if (empty($this->recipe->seo_keywords) || $this->recipe->hasMedia('covers')) {
            return;
        }

        try {
            $imageUrl = $unsplash->searchPhoto($this->recipe->seo_keywords);
            if ($imageUrl) {
                $this->recipe->addMediaFromUrl($imageUrl)
                    ->toMediaCollection('covers');
                Log::info("Successfully fetched Unsplash cover for recipe ID: {$this->recipe->id}");
            }
        } catch (Exception $e) {
            Log::error("Unsplash cover fetch failed for recipe ID: {$this->recipe->id}. Error: " . $e->getMessage());
            throw $e;
        }
    }
}
