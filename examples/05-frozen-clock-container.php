<?php

declare(strict_types=1);

/**
 * This file is part of php-fast-forward/clock.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @copyright Copyright (c) 2025-2026 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * @see       https://github.com/php-fast-forward/clock
 * @see       https://github.com/php-fast-forward
 * @see       https://datatracker.ietf.org/doc/html/rfc2119
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use FastForward\Clock\ServiceProvider\ClockServiceProvider;
use FastForward\Clock\FrozenClock;
use FastForward\Container\ServiceProvider\ArrayServiceProvider;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;

use function FastForward\Container\container;

$frozenClock = new FrozenClock('2026-04-07 10:00:00');

$testProvider = new ArrayServiceProvider([
    FrozenClock::class => static fn(ContainerInterface $container): FrozenClock => $frozenClock,
    ClockInterface::class => static fn(ContainerInterface $container): FrozenClock => $container->get(
        FrozenClock::class
    ),
]);

$container = container($testProvider, new ClockServiceProvider());

$clock = $container->get(ClockInterface::class);

printf("Resolved class: %s\n", $clock::class);
printf("Frozen time: %s\n", $clock->now()->format(\DATE_ATOM));
