<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Exceptions;

use InvalidArgumentException;

final class TimezoneNotValidException extends InvalidArgumentException
{
    public function __construct(string $timezone)
    {
        parent::__construct("Timezone '{$timezone}' is not valid.");
    }
}
