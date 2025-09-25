<?php

namespace App\Http\Controllers\Api;

use App\Prompts;
use App\VisionService;
use App\Jobs\AnalyzeImage;
use App\Models\Observation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ObservationController extends Controller
{
    protected $guarded = [];

    public function store()
    {
        request()->validate([
            'metadata' => 'nullable|array',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:4096',
        ]);

        $guid = \Str::uuid()->toString();
        $path = request()->file('image')->store('observations/' . $guid, 'public');
        $image = request()->file('image');
        $storagePath = \Storage::disk('public')->path($path);

        $observationImage = new \App\ObservationImage($storagePath, $image->getMimeType(), $guid);
        $imageCrops = $observationImage->crop();

        $observation = Observation::create([
            'image_path' => $path,
            'observation_uuid' => $guid,
            'metadata' => request()->input('metadata', []),
        ]);

        foreach ($imageCrops as $name => $crop) {
            $result = VisionService::analyzeImageWithPrompt($crop['url'], Prompts::get($name));
            Log::info("VisionService result for {$name}: " . json_encode($result));
        }

        return response()->json(
            $imageCrops,
            201
        );
    }
}
