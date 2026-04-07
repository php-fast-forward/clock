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

use FastForward\Clock\FrozenClock;

$clockFromString = new FrozenClock('2026-04-07 10:00:00');
printf("From string: %s\n", $clockFromString->now()->format(\DATE_ATOM));

$clockFromTimestamp = new FrozenClock(1775640000);
printf("From timestamp: %s\n", $clockFromTimestamp->now()->format(\DATE_ATOM));

$clockFromDatetime = new FrozenClock(new DateTimeImmutable('2026-04-07 10:00:00'));
printf("From DateTimeImmutable: %s\n", $clockFromDatetime->now()->format(\DATE_ATOM));
