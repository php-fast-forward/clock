SystemClock
===========

``FastForward\Clock\SystemClock`` provides the current system time with optional timezone support.

Description
-----------

This class implements ``Psr\Clock\ClockInterface`` and is suitable for production use. It returns the current time
from the system clock, optionally using a specified timezone.

Constructor
-----------

.. code-block:: php

   public function __construct(DateTimeZone|string|null $timezone = null)

Parameters
~~~~~~~~~~

- ``$timezone`` (``DateTimeZone|string|null``): The timezone to use. If null, uses PHP's default timezone.
  Can be a timezone string (e.g., ``'America/Sao_Paulo'``) or a ``DateTimeZone`` instance.

Methods
-------

now()
~~~~~

Returns the current date-time as ``DateTimeImmutable``.

.. code-block:: php

   public function now(): DateTimeImmutable

Usage examples
--------------

Basic usage:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\SystemClock;

   $clock = new SystemClock();
   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

With timezone:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\SystemClock;

   $clock = new SystemClock('America/Sao_Paulo');
   echo $clock->now()->format('Y-m-d H:i:s P') . PHP_EOL;

With DateTimeZone object:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\SystemClock;
   use DateTimeZone;

   $clock = new SystemClock(new DateTimeZone('Europe/London'));
   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

Integration with dependency injection:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use Psr\Clock\ClockInterface;

   final readonly class TokenGenerator
   {
       public function __construct(private ClockInterface $clock)
       {
       }

       public function generate(): string
       {
           return bin2hex(random_bytes(16)) . '-' . $this->clock->now()->format('YmdHis');
       }
   }

Notes
-----

- This class is ``final readonly``, meaning it cannot be extended
- The timezone is resolved at construction time
- If no timezone is provided, PHP's default timezone (from ``date_default_timezone_get()``) is used