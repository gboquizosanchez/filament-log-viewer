<?php

declare(strict_types=1);

namespace Boquizo\FilamentLogViewer\Models;

use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

/**
 * @property int $id
 * @property string $date
 * @property int $all
 * @property int $emergency
 * @property int $alert
 * @property int $critical
 * @property int $error
 * @property int $warning
 * @property int $notice
 * @property int $info
 * @property int $debug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LogStat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogStat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogStat query()
 *
 * @mixin \Eloquent
 */
class LogStat extends Model
{
    use Sushi;

    protected $fillable = [
        'date',
        'all',
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
    ];

    /** @return array<string, array{
     *     date: string,
     *     all: int,
     *     emergency: int,
     *     alert: int,
     *     critical: int,
     *     error: int,
     *     warning: int,
     *     notice: int,
     *     info: int,
     *     debug: int
     * }>
     */
    public function getRows(): array
    {
        $rows = FilamentLogViewerPlugin::get()
            ->getViewerStatsTable()
            ->rows();

        return array_values($rows) ?? [];
    }
}
