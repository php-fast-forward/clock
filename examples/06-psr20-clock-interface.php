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
use Psr\Clock\ClockInterface;

use function FastForward\Container\container;

$container = container(new ClockServiceProvider());

$clock = $container->get(ClockInterface::class);

printf("Resolved via ClockInterface: %s\n", $clock::class);
printf("Current time: %s\n", $clock->now()->format(\DATE_ATOM));
