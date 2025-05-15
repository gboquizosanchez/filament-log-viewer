<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class Entity implements Arrayable, Jsonable, JsonSerializable
{
}
