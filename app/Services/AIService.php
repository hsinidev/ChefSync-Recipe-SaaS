<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class AIService
{
    /**
     * Dispatch recipe title to the preferred AI API and return structured recipe details.
     */
    public static function generateRecipe(string $title, array $settings): array
    {
        $provider = $settings['preferred_ai_provider'] ?? 'gemini';
        
        $prompt = "Generate a comprehensive, professional recipe for '{$title}'. Output must be strictly valid JSON matching this schema:
{
  \"excerpt\": \"Short teaser description (1-2 sentences)\",
  \"prep_time_minutes\": 15,
  \"cook_time_minutes\": 30,
  \"servings\": 4,
  \"ingredients\": [
     { \"name\": \"Flour\", \"amount\": 100, \"unit\": \"g\", \"state\": \"sifted\" }
  ],
  \"steps\": [
     { \"instruction\": \"Whisk the eggs...\" }
  ],
  \"description_html\": \"<h2>Introduction</h2><p>About this dish...</p><h3>Why You'll Love It</h3><p>...</p><h3>Chef Tips</h3><p>...</p>\"
}
Do not use markdown code blocks around the JSON output, just pure JSON.";

        try {
            if ($provider === 'gemini') {
                $apiKey = $settings['gemini_api_key'] ?? '';
                if (empty($apiKey)) {
                    return ['error' => 'Gemini API Key is missing. Add it in settings.'];
                }

                $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->timeout(30)
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]]
                        ],
                        'generationConfig' => [
                            'responseMimeType' => 'application/json'
                        ]
                    ]);

                if ($response->failed()) {
                    return ['error' => 'Gemini API Error (HTTP ' . $response->status() . ')', 'details' => $response->body()];
                }

                $text = $response->json('candidates.0.content.parts.0.text') ?? '';

            } elseif ($provider === 'openai') {
                $apiKey = $settings['openai_api_key'] ?? '';
                $model = $settings['openai_model'] ?? 'gpt-4o-mini';
                if (empty($apiKey)) {
                    return ['error' => 'OpenAI API Key is missing. Add it in settings.'];
                }

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                     'Authorization' => "Bearer {$apiKey}"
                ])
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'response_format' => ['type' => 'json_object']
                ]);

                if ($response->failed()) {
                    return ['error' => 'OpenAI API Error (HTTP ' . $response->status() . ')', 'details' => $response->body()];
                }

                $text = $response->json('choices.0.message.content') ?? '';

            } else {
                return ['error' => 'Unknown AI provider selected.'];
            }

            // Clean markdown block tags if the AI wrapped JSON in ```json ... ```
            $text = preg_replace('/^```json\s*/i', '', $text);
            $text = preg_replace('/```$/i', '', $text);
            $text = trim($text);

            $data = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'error' => 'Failed to parse JSON from AI response: ' . json_last_error_msg(),
                    'raw' => $text
                ];
            }

            return ['success' => true, 'data' => $data];

        } catch (\Exception $e) {
            Log::error("AI Generation Exception: " . $e->getMessage());
            return ['error' => 'AI Request failed: ' . $e->getMessage()];
        }
    }
}
