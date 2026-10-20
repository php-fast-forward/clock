# PSR-20 and the implementation contract

Supports `fast-forward/clock ^1.1`, PHP ^8.3 and `psr/clock ^1.0`.

The [PHP-FIG PSR-20 Clock specification](https://www.php-fig.org/psr/psr-20/)
defines `Psr\Clock\ClockInterface::now(): DateTimeImmutable`. Obtain Unix seconds
with `$clock->now()->getTimestamp()`. Consumers depend on that interface so the
composition point can choose a live or deterministic implementation.

The [PSR-20 meta document](https://www.php-fig.org/psr/psr-20/meta/) explains
interoperability and testing, and links the
[Clock Wrapper pattern](https://martinfowler.com/bliki/ClockWrapper.html).
Here the clock object wraps access to wall time, while constructor dependency
injection makes it replaceable. `SystemClock` and `FrozenClock` are the library's
two implementations. PSR-20 does not prescribe timezone policy or scheduling.

`SystemClock` constructs a new immutable current date on every read. An explicit
zone fixes its display policy; null delegates to PHP's current default.
`FrozenClock` stores one immutable instant and returns it on every read. Passing
another clock snapshots its `now()` once; passing a mutable date copies it.
Neither implementation changes PHP's global date/time functions.

In a domain service, inject the interface in its constructor. For example, an
expiry decision uses `$clock->now() >= $deadline`, with the deadline and timezone
policy supplied by the application. Use separate frozen subjects to exercise
before/equal/after boundary values. The bundled
[application program](../examples/application.php) shows a checked expiry
calculation without requiring a framework or test-time extension.

Prefer explicit UTC or an application-selected timezone; do not claim that
PSR-20 requires UTC. Converting a returned value with `setTimezone()` changes its
representation without changing its Unix instant. Do not use wall-clock reads
to promise monotonic duration measurements.

Implementation confirmation:
[SystemClock](https://github.com/php-fast-forward/clock/blob/main/src/SystemClock.php),
[FrozenClock](https://github.com/php-fast-forward/clock/blob/main/src/FrozenClock.php).
For version-sensitive behavior read those files in the installed package or its
exact release tag, rather than assuming the moving source matches the consumer.
