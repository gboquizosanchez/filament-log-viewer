<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Tests\Unit;

enum FakeGroupLabelled: string
{
    case Admin = 'admin';

    public function getLabel(): string
    {
        return 'Admin Label';
    }
}
