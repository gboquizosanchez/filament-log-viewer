# Filament Log Viewer

[![Latest Stable Version](https://poser.pugx.org/gboquizosanchez/version.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)
[![License](https://poser.pugx.org/tomatophp/filament-log-viewer/license.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)
[![Downloads](https://poser.pugx.org/tomatophp/filament-log-viewer/d/total.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)

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

## Screenshots 💄

![Panel](https://raw.githubusercontent.com/gboquizosanchez/filament-log-viewer/refs/heads/main/arts/panel.jpg)

## Problems? 🚨

Let me know about yours by [opening an issue](https://github.com/gboquizosanchez/filament-log-viewer/issues/new)!

## Credits 🧑‍💻

- [Germán Boquizo Sánchez](mailto:germanboquizosanchez@gmail.com)
- [All Contributors](../../contributors)

## License 📄

MIT License (MIT). See [License File](LICENSE.md).
