<?php

namespace App;

class Prompts
{
    private static $prompts = [
        'water_tank' => "Analyze the image of the coffee maker. Look specifically at the water tank. Estimate how much water is currently present, and give the answer as a percentage of the tank’s full capacity. If possible, also state if the tank looks empty, half full, or full.",
        'coffee_mug' => "Analyze the image of the coffee mug placed under the coffee maker. Estimate how much coffee is inside the mug. Give the answer as a percentage of the mug’s full capacity, and describe whether it is empty, partially filled, or full.",
        'power_button' => "Analyze the image of the power button for the coffee maker. Tell me whether the power is currently on or off, based on the power button indicator lights, The only indicators that matters is the red light on the button itself and which side of the button is pressed in.",
        'heating_plate_button' => "Analyze the image of the heating plate button for the coffee maker. Tell me whether the heating plate is currently on full or semi-full mode based on the heating plate button indicator light. The only indicators that matters is the red light on the button itself and which side of the button is pressed in.",
    ];

    public static function get($key)
    {
        return self::$prompts[$key] ?? null;
    }
}
