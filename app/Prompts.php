<?php

namespace App;

class Prompts
{
    private static $prompts = [
        'water_tank' => "Analyze the image of the coffee maker. Look specifically at the water tank. Estimate how much water is currently present, and give the answer as a percentage of the tank’s full capacity. If possible, also state if the tank looks empty, half full, or full.",
        'coffee_mug' => "Analyze the image of the coffee mug placed under the coffee maker. Estimate how much coffee is inside the mug. Give the answer as a percentage of the mug’s full capacity, and describe whether it is empty, partially filled, or full.",
        'power_buttons' => "Analyze the image of the control panel and the heating plate of the coffee maker. Tell me: (1) whether the power is currently on or off, based on the power button indicator lights, and (2) whether the heating plate below the mug is turned on or off.",
    ];

    public static function get($key)
    {
        return self::$prompts[$key] ?? null;
    }
}
