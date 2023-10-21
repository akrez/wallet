<?php

namespace App\Traits;

trait Enum
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
