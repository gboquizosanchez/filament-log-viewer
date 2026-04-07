<div align="center">

<img src="https://raw.githubusercontent.com/twitter/twemoji/master/assets/svg/1f4cb.svg" width="100" alt="Log Viewer">

# `gboquizosanchez/filament-log-viewer`

**Log Viewer plugin for Filament panels**

[![Latest Stable Version](https://img.shields.io/packagist/v/gboquizosanchez/filament-log-viewer.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)
[![Total Downloads](https://img.shields.io/packagist/dt/gboquizosanchez/filament-log-viewer.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)
[![PHP](https://img.shields.io/badge/PHP-%5E8.2-777BB4?logo=php&logoColor=white)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)
[![License: MIT](https://img.shields.io/badge/License-MIT-22C55E.svg)](LICENSE.md)
[![Tests](https://img.shields.io/badge/Tests-Pest%20v3-9C27B0)](https://pestphp.com/)

---

*Browse, filter, and manage your Laravel log files directly inside your Filament panel.*

</div>

---

## Overview

This plugin integrates a full-featured log viewer into any Filament panel. Browse log entries by level, filter by date, and inspect stack traces — all without leaving your admin interface.

Based on [ARCANEDEV LogViewer](https://github.com/ARCANEDEV/LogViewer).

![Panel](https://raw.githubusercontent.com/gboquizosanchez/filament-log-viewer/refs/heads/main/arts/panel.jpg)

---

## Version compatibility

| Plugin | Filament  |
|--------|-----------|
| 1.x    | 3.x       |
| 2.x    | 4.x – 5.x |

> [!IMPORTANT]
> Version 1.x **won't receive** any further updates.
---

## 📦 Installation

```bash
composer require gboquizosanchez/filament-log-viewer
```

Register the plugin in your panel provider (`app/Providers/Filament/AdminPanelProvider.php`):

```php
->plugin(\Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::make())
```

Optionally, publish the configuration file:

```bash
php artisan vendor:publish --provider="Boquizo\FilamentLogViewer\FilamentLogViewerServiceProvider"
```

> [!IMPORTANT]
> **Filament v4+ requires a custom theme.** Follow the [Filament docs](https://filamentphp.com/docs/4.x/styling/overview#creating-a-custom-theme) to set one up, then add this line to your theme's CSS source:
>
> ```css
> @source '../../../../vendor/gboquizosanchez/filament-log-viewer/resources/views/**/*.blade.php';
> ```

---

## 🔧 Drivers

By default, the plugin reads from the `LOG_CHANNEL` defined in your `.env`. You can override this with a dedicated environment variable:

```env
FILAMENT_LOG_VIEWER_DRIVER=raw
```

| Driver   | Description |
|----------|-------------|
| `daily`  | Default — mirrors your `LOG_CHANNEL=daily` setting |
| `single` | Standard Laravel single-file driver |
| `raw`    | Shows **all** log files; only available via `FILAMENT_LOG_VIEWER_DRIVER` |

> If `FILAMENT_LOG_VIEWER_DRIVER` is not set, the plugin falls back to `LOG_CHANNEL`.

---

## ⚙️ Configuration

All plugin options are chainable:

```php
->plugins([
    \Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::make()
        ->navigationGroup('System')
        ->navigationSort(2)
        ->navigationIcon(Heroicon::OutlinedDocumentText)
        ->navigationLabel('Log Viewer')
        ->timezone('Europe/Madrid')
        ->authorize(fn (): bool => auth()->user()->can('view-logs')),
])
```

### View in modal

By default, clicking "View" opens the log in a full page. You can enable modal mode instead.

**Via `.env`:**

```env
FILAMENT_LOG_VIEWER_VIEW_IN_MODAL=true
```

**Or programmatically:**

```php
->plugins([
    \Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::make()
        ->viewInModal(),
])
```

When using modal view, you may want to block direct URL access to the `ViewLog` page by registering a custom page that denies access:

```php
->plugins([
    \Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::make()
        ->viewInModal()
        ->viewLog(\App\Filament\LogViewer\Pages\ViewLogDenied::class),
])
```

---

## 🧩 Custom Pages

You can extend the built-in pages to add your own behaviour.

**Custom log list** — e.g. auto-refresh every 30 seconds:

```php
// app/Filament/Pages/CustomListLogs.php

namespace App\Filament\Pages;

use Boquizo\FilamentLogViewer\Pages\ListLogs as BaseListLogs;
use Filament\Tables\Table;

class CustomListLogs extends BaseListLogs
{
    protected static ?string $navigationLabel = 'Application Logs';
    protected static ?string $navigationGroup = 'Monitoring';

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->defaultPaginationPageOption(25)
            ->poll('30s');
    }
}
```

**Custom log viewer** — e.g. add an export action:

```php
// app/Filament/Pages/CustomViewLog.php

namespace App\Filament\Pages;

use Boquizo\FilamentLogViewer\Pages\ViewLog as BaseViewLog;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

class CustomViewLog extends BaseViewLog
{
    protected function getHeaderActions(): array
    {
        return array_merge(parent::getHeaderActions(), [
            Action::make('export')
                ->label('Export to CSV')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->action(fn () => $this->exportToCsv()),
        ]);
    }

    private function exportToCsv(): void
    {
        // Custom export logic
    }
}
```

Then register your custom pages in the plugin:

```php
->plugins([
    \Boquizo\FilamentLogViewer\FilamentLogViewerPlugin::make()
        ->listLogs(\App\Filament\Pages\CustomListLogs::class)
        ->viewLog(\App\Filament\Pages\CustomViewLog::class)
        ->navigationGroup('System')
        ->navigationSort(2)
        ->navigationIcon(Heroicon::DocumentText)
        ->navigationLabel('System Logs')
        ->timezone('Pacific/Auckland')
        ->authorize(fn (): bool => auth()->user()->hasAnyRole(['admin', 'developer'])),
])
```

---

## 🧪 Testing

```bash
composer test
```

---

## Contributing

Contributions are welcome!

- 🐛 **Report bugs** via [GitHub Issues](https://github.com/gboquizosanchez/filament-log-viewer/issues/new)
- 💡 **Suggest features** or improvements
- 🔧 **Submit pull requests** with fixes or enhancements

---

## Credits

- **Author**: [Germán Boquizo Sánchez](mailto:germanboquizosanchez@gmail.com)
- **Based on**: [ARCANEDEV LogViewer](https://github.com/ARCANEDEV/LogViewer)
- **Contributors**: [View all contributors](../../contributors)

---

## 📄 License

This package is open-source software licensed under the [MIT License](LICENSE.md).
