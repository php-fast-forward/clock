---
name: fast-forward-clock
description: Integrate fast-forward/clock into PHP applications using the PSR-20 ClockInterface, SystemClock, FrozenClock, explicit timezones and Fast Forward Container. Use for clock injection, deterministic time-based tests and clock service configuration.
license: MIT
compatibility: Supports fast-forward/clock ^1.1 on PHP ^8.3 with Composer autoloading and psr/clock ^1.0. Container examples use fast-forward/container ^1.6 and fast-forward/config ^1.4, required by the library. Earlier Clock releases are outside this skill contract.
metadata:
  author: php-fast-forward
  api-baseline: fast-forward/clock ^1.1
---

# Fast Forward Clock

Use a production clock at the application composition point and inject
`Psr\Clock\ClockInterface` into code that reads the current date and time.
Use `FrozenClock` for repeatable tests. Inspect `composer show fast-forward/clock`
and the application's instructions before editing. This skill supports `^1.1`;
for older or incompatible versions inspect their own API/docs and report the
mismatch. Do not change dependency constraints just to fit these instructions.

## First application route

With PHP ^8.3 and Composer, use the application's existing autoloader. For a
new integration, install `composer require 'fast-forward/clock:^1.1'` within the
authorized dependency scope. No container bootstrap is needed for direct use.

Save this complete program as `clock.php` in the application root:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use FastForward\Clock\FrozenClock;
use FastForward\Clock\SystemClock;
use Psr\Clock\ClockInterface;

function expiresAt(ClockInterface $clock): DateTimeImmutable
{
    return $clock->now()->modify('+15 minutes');
}

$production = new SystemClock('UTC');
echo expiresAt($production)->format(DATE_ATOM) . PHP_EOL;

$test = new FrozenClock('2026-04-07T10:00:00+00:00');
if (expiresAt($test)->format(DATE_ATOM) !== '2026-04-07T10:15:00+00:00') {
    throw new RuntimeException('Unexpected expiry.');
}
echo "frozen expiry: ok\n";
```

Run `php clock.php`: the first line is a live UTC expiry; the second must be
`frozen expiry: ok`. In application classes, accept `ClockInterface` through the
constructor and use `$this->clock->now()` at the point of decision. Keep each
class in its own matching file and follow the application's autoload mapping.
Do not replace clock reads with `time()` or a hidden global clock in domain code.

## Select and configure the clock

- `new SystemClock('America/Sao_Paulo')` or a `DateTimeZone` uses an explicit
  zone. Omitted/null zone follows PHP's default at each `now()` call. Prefer
  explicit application configuration where stable timezone behavior matters.
- `FrozenClock` accepts a `DateTimeInterface`, another `ClockInterface`, a PHP
  date string or integer/float Unix seconds. It captures an input clock once;
  it does not follow later reads. Mutable dates are copied to immutable values.
- Use explicit offsets/zones in test strings. Relative strings such as `now`
  depend on the construction time. Numeric timestamps preserve the instant,
  are rounded to microseconds and displayed in PHP's default timezone.
- Repeated frozen reads return the same immutable value. Methods such as
  `modify()` return a new date; they do not advance the clock. To test another
  instant, construct another `FrozenClock` and inject it into a fresh subject.
- Invalid zone/date strings propagate PHP date exceptions; invalid numeric
  timestamp conversion raises `InvalidArgumentException`. Do not silently
  replace invalid application configuration with the current time.

## Fast Forward Container route

Load [container configuration](references/container.md) when an application
uses `ClockServiceProvider`: it shows the configuration file, explicit bootstrap,
service aliases, precedence, lifetime and test overrides. Direct construction
remains appropriate when the application has no container.

## References and executable checks

- [PSR-20 and implementation contract](references/psr-20.md): load for interface
  requirements, the Clock Wrapper pattern, timezone semantics and limitations.
- [Application example](examples/application.php): production clock, immutable
  input capture and deterministic expiry; run with a consumer autoloader.
- [Container example](examples/container.php): configured zone, alias identity,
  missing configuration, overrides and invalid configuration checks.

Run from the consumer root, substituting the copied skill location if necessary:

```sh
php vendor/fast-forward/clock/resources/skills/fast-forward-clock/examples/application.php ./vendor/autoload.php
php vendor/fast-forward/clock/resources/skills/fast-forward-clock/examples/container.php ./vendor/autoload.php
```

Both must exit zero and print their `ok` marker. Verify application-specific
boundaries with its existing PHPUnit suite: check just before, at and after an
expiry. PSR-20 is a wall clock abstraction; it supplies no scheduling, sleeping,
monotonic elapsed-time measurement or process-wide time replacement. Do not
invent `advance()`, `sleep()` or Symfony Clock APIs for this library.
