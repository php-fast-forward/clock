# FastForward\Clock

[![PHP Version](https://img.shields.io/badge/php-8.3%2B-blue.svg)](https://www.php.net/)
[![PSR-20](https://img.shields.io/badge/PSR-20-compliant-777bb4.svg)](https://www.php-fig.org/psr/psr-20/)
[![Tests](https://github.com/php-fast-forward/clock/actions/workflows/tests.yml/badge.svg)](https://github.com/php-fast-forward/clock/actions/workflows/tests.yml)
[![Coverage](https://img.shields.io/badge/coverage-phpunit-green)](https://php-fast-forward.github.io/clock/coverage/index.html)
[![License](https://img.shields.io/github/license/php-fast-forward/clock)](https://opensource.org/licenses/MIT)

FastForward\Clock provides PSR-20 compliant clock implementations and seamless integration with [Fast Forward Container](https://github.com/php-fast-forward/container).

This package offers two clock implementations: `SystemClock` for production time and `FrozenClock` for deterministic testing.

## ✨ Features

- 🕒 **PSR-20 Compliant** - Full support for the PSR-20 Clock interface
- 🧪 **Testing Made Easy** - `FrozenClock` lets you freeze time for reliable tests
- 🌐 **Timezone Support** - Configure timezones in production and tests
- 🔌 **Container Integration** - Automatic service registration via `ClockServiceProvider`
- 🎯 **Beginner Friendly** - Simple API designed for developers new to PSR patterns

## 📦 Installation

```bash
composer require fast-forward/clock
```

Requirements:

- PHP 8.3 or higher
- `fast-forward/container` 1.6 or higher
- `psr/clock` 1.0

## 🛠️ Usage

### Basic: Get the current time

The simplest way to get the current time is using `SystemClock`:

```php
<?php

declare(strict_types=1);

use FastForward\Clock\SystemClock;

$clock = new SystemClock();

echo $clock->now()->format(DATE_ATOM) . PHP_EOL;
```

### With a specific timezone

```php
<?php

declare(strict_types=1);

use FastForward\Clock\SystemClock;

$clock = new SystemClock('America/Sao_Paulo');

echo $clock->now()->format('Y-m-d H:i:s P') . PHP_EOL;
```

### Using with Fast Forward Container

For larger applications, use the service provider to register the clock in your container:

```php
<?php

declare(strict_types=1);

use FastForward\Clock\ServiceProvider\ClockServiceProvider;
use Psr\Clock\ClockInterface;
use function FastForward\Container\container;

$container = container(new ClockServiceProvider());

$clock = $container->get(ClockInterface::class);

echo $clock->now()->format(DATE_ATOM) . PHP_EOL;
```

### Freezing time for tests

Use `FrozenClock` to create deterministic tests:

```php
<?php

declare(strict_types=1);

use FastForward\Clock\FrozenClock;

$clock = new FrozenClock('2026-04-07 10:00:00');

echo $clock->now()->format(DATE_ATOM) . PHP_EOL;
// Output: 2026-04-07T10:00:00+00:00
```

`FrozenClock` accepts multiple input formats:

```php
<?php

declare(strict_types=1);

use FastForward\Clock\FrozenClock;

// From a DateTimeImmutable
$clock1 = new FrozenClock(new DateTimeImmutable('2026-04-07 10:00:00'));

// From a string
$clock2 = new FrozenClock('next Monday');

// From a timestamp
$clock3 = new FrozenClock(1775640000);

// From another ClockInterface
$clock4 = new FrozenClock(new SystemClock('America/New_York'));
```

### Using FrozenClock with Fast Forward Container in tests

```php
<?php

declare(strict_types=1);

use FastForward\Clock\ServiceProvider\ClockServiceProvider;
use FastForward\Clock\FrozenClock;
use FastForward\Container\ServiceProvider\ArrayServiceProvider;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use function FastForward\Container\container;

$frozenClock = new FrozenClock('2026-04-07 10:00:00');

$testProvider = new ArrayServiceProvider([
    FrozenClock::class => static fn(ContainerInterface $container): FrozenClock => $frozenClock,
    ClockInterface::class => static fn(ContainerInterface $container): FrozenClock => $container->get(FrozenClock::class),
]);

$container = container($testProvider, new ClockServiceProvider());

$clock = $container->get(ClockInterface::class);

echo $clock->now()->format(DATE_ATOM) . PHP_EOL;
```

## 🧰 API Summary

| Class | Description |
|-------|-------------|
| `FastForward\Clock\SystemClock` | PSR-20 clock that returns current system time with optional timezone |
| `FastForward\Clock\FrozenClock` | PSR-20 clock that returns a fixed time (ideal for testing) |
| `FastForward\Clock\ServiceProvider\ClockServiceProvider` | Registers clock services in Fast Forward Container |

### SystemClock Constructor

```php
public function __construct(DateTimeZone|string|null $timezone = null)
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `$timezone` | `DateTimeZone\|string\|null` | Timezone for the clock (defaults to system default) |

### FrozenClock Constructor

```php
public function __construct(DateTimeInterface|ClockInterface|string|int|float $clock = 'now')
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `$clock` | `DateTimeInterface\|ClockInterface\|string\|int\|float` | The time to freeze. Accepts DateTimeImmutable, string (relative or absolute), timestamp, or another ClockInterface |

## 🔌 Integration

This package integrates seamlessly with:

- **Fast Forward Container** - Use `ClockServiceProvider` for automatic registration
- **PSR-20** - Both `SystemClock` and `FrozenClock` implement `Psr\Clock\ClockInterface`
- **Any PSR-11 Container** - Both clocks can be instantiated directly without the service provider

## 📁 Directory Structure

```
src/
├── SystemClock.php              # Production clock implementation
├── FrozenClock.php              # Testing clock implementation
├── ServiceProvider/
│   ├── ClockServiceProvider.php # Container service provider
│   └── Factory/
│       └── DateTimeZoneFactory.php # Timezone factory
tests/
├── SystemClockTest.php
├── FrozenClockTest.php
└── ServiceProvider/
    └── ClockServiceProviderTest.php
examples/
├── 01-system-clock.php
├── 02-system-clock-with-timezone.php
├── 03-frozen-clock.php
├── 04-frozen-clock-inputs.php
├── 05-frozen-clock-container.php
└── 06-psr20-clock-interface.php
docs/
└── ... (Sphinx documentation)
```

## ⚙️ Advanced / Customization

### Custom timezone via configuration

If you use Fast Forward Config, you can configure the default timezone:

```php
<?php

use FastForward\Config\Config;
use FastForward\Clock\ServiceProvider\ClockServiceProvider;
use function FastForward\Container\container;

$config = new Config([
    DateTimeZone::class => 'America/Sao_Paulo',
]);

$container = container($config, new ClockServiceProvider());
```

### Creating a custom clock

Both clocks are `final readonly` classes, but you can wrap them:

```php
<?php

declare(strict_types=1);

use FastForward\Clock\FrozenClock;
use Psr\Clock\ClockInterface;

final class StubbedClock implements ClockInterface
{
    public function __construct(private ClockInterface $clock)
    {
    }

    public function now(): \DateTimeImmutable
    {
        return $this->clock->now();
    }

    public static function create(string $time): self
    {
        return new self(new FrozenClock($time));
    }
}
```

## ❓ FAQ

**Q: What's the difference between SystemClock and FrozenClock?**  
**A:** `SystemClock` returns the current time and is suitable for production. `FrozenClock` returns a fixed time and is ideal for testing.

**Q: Which interface should I use in my application?**  
**A:** Use `Psr\Clock\ClockInterface` for maximum portability. Both implementations satisfy this interface.

**Q: How do I freeze time in tests?**  
**A:** Use `FrozenClock` directly, or register it via `ArrayServiceProvider` before `ClockServiceProvider`.

**Q: Can I use this without Fast Forward Container?**  
**A:** Yes! Simply instantiate `SystemClock` or `FrozenClock` directly in your code.

**Q: Does this work with other PSR-11 containers?**  
**A:** Yes. The clocks are standalone classes that don't require any container.

**Q: How do I set a specific timezone?**  
**A:** Pass a timezone string or `DateTimeZone` instance to the `SystemClock` constructor.

## 🛡 License

This project is licensed under the [MIT License](LICENSE).

## 🤝 Contributing

Issues, documentation improvements, and pull requests are welcome!

## 🔗 Links

- [Repository](https://github.com/php-fast-forward/clock)
- [Issue Tracker](https://github.com/php-fast-forward/clock/issues)
- [Examples](examples)
- [Sphinx Documentation](docs/index.rst)
- [Fast Forward Container](https://github.com/php-fast-forward/container)
- [PSR-20 Clock](https://www.php-fig.org/psr/psr-20/)
