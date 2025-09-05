# Filament Log Viewer

[![Latest Stable Version](https://img.shields.io/packagist/v/gboquizosanchez/filament-log-viewer.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)
[![Software License](https://img.shields.io/badge/license-MIT-red.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)
[![Total Downloads](https://img.shields.io/packagist/dt/gboquizosanchez/filament-log-viewer.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)

## Summary

This package allows you to manage and keep track of each one of your log files in Filament panels.

Based on [ARCANEDEV LogViewer](https://github.com/ARCANEDEV/LogViewer).

## Starting 🚀

### Prerequisites 📋

- Composer.
- PHP version 8.3 or higher.

## Running 🛠️

Install the package via composer:

```shell
composer require gboquizosanchez/filament-log-viewer
```

And register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::make())
```

You can also publish the configuration file to customize the package:

```shell
php artisan vendor:publish --provider="Boquizo\FilamentLogViewer\FilamentLogViewerServiceProvider"
```

> [!IMPORTANT]
> In v4 it's necessary to set up a custom theme following the instructions in the [Filament Docs](https://filamentphp.com/docs/4.x/styling/overview#creating-a-custom-theme) first.

After setting up the custom theme, you need to add this line, if not, the plugin will not work properly.

```css
@source '../../../../vendor/gboquizosanchez/filament-log-viewer/resources/views/**/*.blade.php';
```

### 🔧 Drivers

By default, the plugin uses **LOG_CHANNEL** as the driver.  
To override this behavior, set the environment variable in your `.env` file:

```
FILAMENT_LOG_VIEWER_DRIVER=raw
```

#### 📌 Available Drivers
| Driver | Description                                                                          |
|--------|--------------------------------------------------------------------------------------|
| daily  | Default driver used by the plugin                                                    |
| single | Standard Laravel single driver                                                       |
| raw    | Only available when explicitly using FILAMENT_LOG_VIEWER_DRIVER; shows all log files |

👉 **Note:**  
If `FILAMENT_LOG_VIEWER_DRIVER` is not defined, the plugin will continue using `LOG_CHANNEL`.

#### Example `.env` configuration
**Use the default LOG_CHANNEL (daily):**

```
LOG_CHANNEL=daily
```

**Or override to use raw with FILAMENT_LOG_VIEWER_DRIVER:**

```
FILAMENT_LOG_VIEWER_DRIVER=raw
```

### Others configurations

```php
->plugins([
    \Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::make()
        ->navigationGroup('System')
        ->navigationSort(2)
        ->navigationIcon(Heroicon::OutlinedDocumentText)
        ->navigationLabel('Log Viewer')
        ->authorize(fn (): bool => auth()->user()->can('view-logs')),
    // Other plugins
])
```

### Custom Pages Configuration

You can customize the plugin pages by extending the base classes:

```php
// app/Filament/Pages/CustomListLogs.php
<?php

namespace App\Filament\Pages;

use Boquizo\FilamentLogViewer\Pages\ListLogs as BaseListLogs;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class CustomListLogs extends BaseListLogs
{
    protected static ?string $navigationLabel = 'Application Logs';
    
    protected static ?string $navigationGroup = 'Monitoring';
    
    public function table(Table $table): Table
    {
        return parent::table($table)
            ->defaultPaginationPageOption(25)
            ->poll('30s'); // Auto-refresh every 30 seconds
    }
}
```

```php
// app/Filament/Pages/CustomViewLog.php
<?php

namespace App\Filament\Pages;

use Boquizo\FilamentLogViewer\Pages\ViewLog as BaseViewLog;
use Filament\Actions\Action;

class CustomViewLog extends BaseViewLog
{
    protected function getHeaderActions(): array
    {
        return array_merge(
            parent::getHeaderActions(),
            [
                Action::make('export')
                    ->label('Export to CSV')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->action(fn () => $this->exportToCsv()),
            ]
        );
    }
    
    private function exportToCsv(): void
    {
        // Custom export logic
    }
}
```

Then register your custom pages in the plugin configuration:

```php
->plugins([
    \Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::make()
        ->listLogs(\App\Filament\Pages\CustomListLogs::class)
        ->viewLog(\App\Filament\Pages\CustomViewLog::class)
        ->navigationGroup('System')
        ->navigationSort(2)
        ->navigationIcon(Heroicon::DocumentText)
        ->navigationLabel('System Logs')
        ->authorize(function (): bool {
            return auth()->user()->hasAnyRole(['admin', 'developer']);
        }),
    // Other plugins like FilamentEmailPlugin, etc.
])
```

## Screenshots 💄

![Panel](https://raw.githubusercontent.com/gboquizosanchez/filament-log-viewer/refs/heads/main/arts/panel.jpg)

### PHP dependencies 📦
- Owenvoke Blade Fontawesome [![Latest Stable Version](https://img.shields.io/badge/stable-v2.9.1-blue)](https://packagist.org/packages/owenvoke/blade-fontawesome)

#### Develop dependencies 🔧
- Friendsofphp Php Cs Fixer [![Latest Stable Version](https://img.shields.io/badge/stable-v3.85.1-blue)](https://packagist.org/packages/friendsofphp/php-cs-fixer)
- Hermes Dependencies [![Latest Stable Version](https://img.shields.io/badge/stable-1.2.0-blue)](https://packagist.org/packages/hermes/dependencies)
- Larastan Larastan [![Latest Stable Version](https://img.shields.io/badge/stable-v2.11.2-blue)](https://packagist.org/packages/larastan/larastan)
- Orchestra Testbench [![Latest Stable Version](https://img.shields.io/badge/stable-v9.14.0-blue)](https://packagist.org/packages/orchestra/testbench)
- Pestphp Pest [![Latest Stable Version](https://img.shields.io/badge/stable-v3.8.2-blue)](https://packagist.org/packages/pestphp/pest)


## Problems? 🚨

Let me know about yours by [opening an issue](https://github.com/gboquizosanchez/filament-log-viewer/issues/new)!

## Credits 🧑‍💻

- [Germán Boquizo Sánchez](mailto:germanboquizosanchez@gmail.com)
- [All Contributors](../../contributors)

## License 📄

MIT License (MIT). See [License File](LICENSE.md).
