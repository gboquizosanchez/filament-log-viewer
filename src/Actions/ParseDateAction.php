<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Actions;

use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Carbon;

class ParseDateAction
{
    public static function execute(?string $date): string
    {
        try {
            return Carbon::parse($date)->isoFormat('LL');
        } catch (InvalidFormatException) {
            return $date;
        }
    }

}
