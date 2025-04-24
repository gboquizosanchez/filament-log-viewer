# Filament Log Viewer

[![Latest Stable Version](https://poser.pugx.org/gboquizosanchez/version.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)
[![License](https://poser.pugx.org/gboquizosanchez/filament-log-viewer/license.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)
[![Downloads](https://poser.pugx.org/gboquizosanchez/filament-log-viewer/d/total.svg)](https://packagist.org/packages/gboquizosanchez/filament-log-viewer)

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

### PHP dependencies 📦

- Arcanedev Log Viewer [![Latest Stable Version](https://img.shields.io/badge/stable-10.1.0-blue)](https://packagist.org/packages/arcanedev/log-viewer)
- Calebporzio Sushi [![Latest Stable Version](https://img.shields.io/badge/stable-v2.5.3-blue)](https://packagist.org/packages/calebporzio/sushi)
- Eightynine Filament Advanced Widgets [![Latest Stable Version](https://img.shields.io/badge/stable-3.0.1-blue)](https://packagist.org/packages/eightynine/filament-advanced-widgets)
- Owenvoke Blade Fontawesome [![Latest Stable Version](https://img.shields.io/badge/stable-v2.9.1-blue)](https://packagist.org/packages/owenvoke/blade-fontawesome)

#### Develop dependencies 🔧

- Friendsofphp Php Cs Fixer [![Latest Stable Version](https://img.shields.io/badge/stable-v3.75.0-blue)](https://packagist.org/packages/friendsofphp/php-cs-fixer)
- Hermes Dependencies [![Latest Stable Version](https://img.shields.io/badge/stable-1.1.1-blue)](https://packagist.org/packages/hermes/dependencies)
- Larastan Larastan [![Latest Stable Version](https://img.shields.io/badge/stable-v2.11.0-blue)](https://packagist.org/packages/larastan/larastan)
- Orchestra Testbench [![Latest Stable Version](https://img.shields.io/badge/stable-v9.13.0-blue)](https://packagist.org/packages/orchestra/testbench)
- Pestphp Pest [![Latest Stable Version](https://img.shields.io/badge/stable-v3.8.2-blue)](https://packagist.org/packages/pestphp/pest)



## Problems? 🚨

Let me know about yours by [opening an issue](https://github.com/gboquizosanchez/filament-log-viewer/issues/new)!

## Credits 🧑‍💻

- [Germán Boquizo Sánchez](mailto:germanboquizosanchez@gmail.com)
- [All Contributors](../../contributors)

## License 📄

MIT License (MIT). See [License File](LICENSE.md).
