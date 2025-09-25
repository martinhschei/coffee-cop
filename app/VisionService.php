<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class VisionService
{
    public static function analyzeImageWithPrompt(string $imagePath, string $prompt): array
    {
        $apiKey = config('services.openai.api_key');

        if (!$apiKey || strlen($apiKey) === 0) {
            throw new \Exception('OpenAI API key not configured.');
        }

        $url = 'https://api.openai.com/v1/chat/completions';

        $fileContents = Storage::disk('public')->get($imagePath);
        $imageData = base64_encode($fileContents);

        $payload = [
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt,
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $imagePath, 'detail' => 'high'
                            ],
                        ],
                    ],
                ],
            ],
            'max_completion_tokens' => 3000,
        ];

        Log::info("Sending request to OpenAI with payload: " . json_encode($payload));

        $response = Http::withToken($apiKey)
            ->timeout(70)
            ->post($url, $payload);

        return $response->json();
    }
}
