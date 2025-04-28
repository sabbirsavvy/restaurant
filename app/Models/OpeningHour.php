<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'open',
        'close',
    ];

    // Optional: Helper to return in HH:MM format
    public function formatted()
    {
        return [
            'open' => \Carbon\Carbon::parse($this->open)->format('H:i'),
            'close' => \Carbon\Carbon::parse($this->close)->format('H:i'),
        ];
    }
}
