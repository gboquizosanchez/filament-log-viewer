<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use Exception;
use Illuminate\Support\Facades\File;

class ClearLogUseCase
{
    public static function execute(string $file): bool
    {
        try {
            File::put(ExtractLogPathUseCase::execute($file), '');

            return true;
        } catch (Exception) {
            return false;
        }
    }
}
