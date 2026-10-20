# Configure and inject through Fast Forward Container

Supports Clock `^1.1`, Container `^1.6`, Config `^1.4` and PHP ^8.3. Confirm the
installed versions before applying this route. These packages are runtime
requirements of Clock; no Symfony Clock integration is provided.

Create `config/clock.php` in the application:

```php
<?php

return [DateTimeZone::class => 'America/Sao_Paulo'];
```

The raw configuration key is `DateTimeZone::class`, not `timezone` or
`clock.timezone`. Create the application root `bootstrap.php`:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use FastForward\Clock\ServiceProvider\ClockServiceProvider;
use FastForward\Config\ConfigInterface;
use FastForward\Container\ServiceProvider\ArrayServiceProvider;
use Psr\Clock\ClockInterface;

use function FastForward\Config\config;
use function FastForward\Container\container;

$settings = config(require __DIR__ . '/config/clock.php');
$container = container(
    new ArrayServiceProvider([
        ConfigInterface::class => static fn(): ConfigInterface => $settings,
    ]),
    new ClockServiceProvider(),
);

$clock = $container->get(ClockInterface::class);
if ($clock->now()->getTimezone()->getName() !== 'America/Sao_Paulo') {
    throw new RuntimeException('Configured timezone was not applied.');
}
echo "configured clock: ok\n";
```

Run `php bootstrap.php`. Pass `$clock` to application service constructors that
accept `ClockInterface`. Explicitly binding `ConfigInterface` lets the timezone
factory read the settings; merely loading an array does not register it.

The provider registers `DateTimeZone`, `SystemClock` and a `ClockInterface` alias
to `SystemClock`. A configured `DateTimeZone` object is reused as-is. Missing
`ConfigInterface` or a missing raw key falls back to `date_default_timezone_get()`.
Malformed values and errors retrieving configuration propagate; they do not
trigger that absence fallback. A null configured value is not an omitted key.

Earlier container sources win. To override the PSR clock for tests, put an
`ArrayServiceProvider` binding `ClockInterface::class` to a preconstructed
`FrozenClock` before `ClockServiceProvider`. This replaces the interface binding;
an explicit request for `SystemClock` still returns the system implementation.
Consumers should request the interface. See the complete
[container program](../examples/container.php).

Services are cached in the composed container: the interface alias and system
service resolve to the same instance. Configure settings and overrides before
the first resolution. Rebuild the container for changed configuration or test
overrides; mutating settings does not refresh a cached timezone. The provider
captures PHP's default zone when its timezone service is first resolved, unlike
direct `new SystemClock()` which defers the default until each read.
