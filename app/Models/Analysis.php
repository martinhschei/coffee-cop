<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    protected $guarded = [];

    protected $casts = [
        'result' => 'array',
    ];

    public function observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
