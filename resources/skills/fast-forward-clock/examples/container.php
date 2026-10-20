<?php

declare(strict_types=1);

use FastForward\Clock\FrozenClock;
use FastForward\Clock\ServiceProvider\ClockServiceProvider;
use FastForward\Clock\ServiceProvider\Factory\DateTimeZoneFactory;
use FastForward\Clock\SystemClock;
use FastForward\Config\ConfigInterface;
use FastForward\Container\ServiceProvider\ArrayServiceProvider;
use Psr\Clock\ClockInterface;

use function FastForward\Config\config;
use function FastForward\Container\container;

require $argv[1] ?? getcwd() . '/vendor/autoload.php';

$settings = config([DateTimeZone::class => 'America/Sao_Paulo']);
$configured = container(
    new ArrayServiceProvider([
        ConfigInterface::class => static fn(): ConfigInterface => $settings,
    ]),
    new ClockServiceProvider(),
);
$clock = $configured->get(ClockInterface::class);
if ($clock !== $configured->get(SystemClock::class)
    || $clock->now()->getTimezone()->getName() !== 'America/Sao_Paulo'
) {
    throw new RuntimeException('Configuration or alias identity failed.');
}

$fallback = container(new ClockServiceProvider());
if ($fallback->get(ClockInterface::class)->now()->getTimezone()->getName() !== date_default_timezone_get()) {
    throw new RuntimeException('Missing configuration fallback failed.');
}

$frozen = new FrozenClock('2026-04-07T10:00:00+00:00');
$overridden = container(
    new ArrayServiceProvider([ClockInterface::class => static fn(): FrozenClock => $frozen]),
    new ClockServiceProvider(),
);
if ($overridden->get(ClockInterface::class) !== $frozen
    || ! $overridden->get(SystemClock::class) instanceof SystemClock
) {
    throw new RuntimeException('Earlier provider override failed.');
}

$invalid = container(
    new ArrayServiceProvider([
        ConfigInterface::class => static fn(): ConfigInterface => config([DateTimeZone::class => 'Invalid/Timezone']),
    ]),
    new ClockServiceProvider(),
);
try {
    (new DateTimeZoneFactory())($invalid);
    throw new RuntimeException('Invalid configuration was accepted.');
} catch (DateInvalidTimeZoneException) {
    // Validate at the factory boundary before container fallback/autowiring.
}

echo "container: ok\n";
