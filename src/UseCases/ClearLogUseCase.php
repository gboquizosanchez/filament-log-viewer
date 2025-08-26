<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\UseCases;

use Illuminate\Support\Facades\File;

class ClearLogUseCase
{
    public static function execute(string $file): bool
    {
        return (bool) File::put(ExtractLogPathUseCase::execute($file), '');
    }
}
