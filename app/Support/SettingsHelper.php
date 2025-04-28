<?php
namespace App\Support;

use App\Models\OpeningHour;

class SettingsHelper
{
    public static function getOpeningHours()
    {
        return OpeningHour::all()
            ->keyBy(fn($hour) => strtolower($hour->day))
            ->map(fn($hour) => [
                'open' => $hour->open,
                'close' => $hour->close,
            ])
            ->toArray();
    }
}