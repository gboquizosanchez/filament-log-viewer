<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Models;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

/**
 * @property int $id
 * @property string $date
 * @property string $env
 * @property string $level
 * @property string $datetime
 * @property string $header
 * @property string $stack
 * @property string|array $context
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LogStat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogStat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogStat query()
 *
 * @mixin \Eloquent
 */
class Log extends Model
{
    use Sushi;

    protected $fillable = [
        'env',
        'level',
        'datetime',
        'header',
        'stack',
        'context',
        'info',
    ];

    /** @return array<string, array{
     *     env: string,
     *     level: string,
     *     datetime: \Carbon\Carbon::class,
     *     header: string,
     *     stack: string,
     *     context: string
     * }>
     */
    public function getRows(): array
    {
        return FilamentLogViewerPlugin::get()
            ->getLogViewerRecord()
            ->toModel() ?? [];
    }
}
