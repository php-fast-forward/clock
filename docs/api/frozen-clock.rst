FrozenClock
============

``FastForward\Clock\FrozenClock`` provides a fixed, deterministic time ideal for testing scenarios.

Description
-----------

This class implements ``Psr\Clock\ClockInterface`` and returns a fixed time regardless of when it's called.
This makes it perfect for writing reliable tests where time-dependent behavior needs to be predictable.

Unlike ``SystemClock`` which returns the current time, ``FrozenClock`` always returns the time that was
set when the clock was created.

Constructor
-----------

.. code-block:: php

   public function __construct(DateTimeInterface|ClockInterface|string|int|float $clock = 'now')

Parameters
~~~~~~~~~~

- ``$clock`` (``DateTimeInterface|ClockInterface|string|int|float``): The time to freeze. Defaults to ``'now'``.
  
  Supported formats:
  
  - ``DateTimeInterface``: Any DateTimeImmutable or DateTime object
  - ``ClockInterface``: Another PSR-20 clock implementation (its current time is used)
  - ``string``: Relative or absolute date strings (e.g., ``'2026-04-07 10:00:00'``, ``'next Monday'``)
  - ``int``: Unix timestamp
  - ``float``: Unix timestamp with microseconds

Methods
-------

now()
~~~~~

Returns the frozen date-time as ``DateTimeImmutable``.

.. code-block:: php

   public function now(): DateTimeImmutable

Usage examples
--------------

Basic usage with string:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;

   $clock = new FrozenClock('2026-04-07 10:00:00');
   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;
   // Output: 2026-04-07T10:00:00+00:00

With DateTimeImmutable:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;

   $frozenTime = new DateTimeImmutable('2026-04-07 10:00:00', new DateTimeZone('America/Sao_Paulo'));
   $clock = new FrozenClock($frozenTime);
   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

With timestamp:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;

   $clock = new FrozenClock(1775640000); // 2026-04-07 10:00:00 UTC
   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

With another ClockInterface:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;
   use FastForward\Clock\SystemClock;

   $clock = new FrozenClock(new SystemClock('America/New_York'));
   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

With relative time strings:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;

   // Freeze to "one hour from now"
   $clock = new FrozenClock('+1 hour');

   // Freeze to "yesterday"
   $clock = new FrozenClock('yesterday');

Testing example:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;

   final class TokenGenerator
   {
       public function __construct(private ClockInterface $clock)
       {
       }

       public function expiresAt(int $ttlMinutes = 15): DateTimeImmutable
       {
           return $this->clock->now()->add(new DateInterval("PT{$ttlMinutes}M"));
       }
   }

   // In your test
   $frozenClock = new FrozenClock('2026-04-07 10:00:00');
   $generator = new TokenGenerator($frozenClock);

   $expiresAt = $generator->expiresAt(15);

   // $expiresAt is always 2026-04-07T10:15:00+00:00, not dependent on when test runs

Notes
-----

- This class is ``final readonly``, meaning it cannot be extended
- The frozen time is set once during construction and never changes
- Useful for testing time-sensitive operations like token expiration, cache TTL, etc.
- Accepts any input that PHP's ``DateTimeImmutable`` can parse