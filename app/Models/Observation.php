<?php

namespace App\Models;

use App\Helpers\Prompts;
use App\Services\VisionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function analysis()
    {
        return $this->hasMany(Analysis::class);
    }

    public function analyze()
    {
        Log::debug('Starting analysis for Observation ID: ' . $this->id);
        $prompt = Prompts::coffeeMaker();

        $response = VisionService::analyzeImageWithPrompt(asset('storage/' . $this->image_path), $prompt);

        $this->analysis()->create([
            'prompt' => $prompt,
            'result' => $response,
        ]);

        Log::debug('Analysis completed for Observation ID: ' . $this->id);
    }
}
