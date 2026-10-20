<?php

declare(strict_types=1);

use FastForward\Clock\FrozenClock;
use FastForward\Clock\SystemClock;
use Psr\Clock\ClockInterface;

require $argv[1] ?? getcwd() . '/vendor/autoload.php';

function expiresAt(ClockInterface $clock): DateTimeImmutable
{
    return $clock->now()->modify('+15 minutes');
}

$production = new SystemClock('UTC');
if ($production->now()->getTimezone()->getName() !== 'UTC') {
    throw new RuntimeException('Unexpected production timezone.');
}

$input = new DateTime('2026-04-07T10:00:00+00:00');
$frozen = new FrozenClock($input);
$input->modify('+1 day');
if (expiresAt($frozen)->format(DATE_ATOM) !== '2026-04-07T10:15:00+00:00') {
    throw new RuntimeException('Expiry or immutable capture failed.');
}
if ($frozen->now() !== $frozen->now() || (new FrozenClock($frozen))->now() != $frozen->now()) {
    throw new RuntimeException('Frozen reads or clock snapshot failed.');
}

$deadline = new DateTimeImmutable('2026-04-07T10:15:00+00:00');
foreach (['10:14:59' => false, '10:15:00' => true, '10:15:01' => true] as $time => $expired) {
    $clock = new FrozenClock('2026-04-07T' . $time . '+00:00');
    if (($clock->now() >= $deadline) !== $expired) {
        throw new RuntimeException('Expiry boundary failed.');
    }
}

try {
    new SystemClock('Invalid/Timezone');
    throw new RuntimeException('Invalid timezone was accepted.');
} catch (DateInvalidTimeZoneException) {
    // PHP's invalid timezone error is expected to propagate.
}
try {
    new FrozenClock('not a valid date');
    throw new RuntimeException('Invalid date was accepted.');
} catch (DateMalformedStringException) {
    // PHP's invalid date error is expected to propagate.
}

echo "application: ok\n";
