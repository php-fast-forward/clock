FastForward Clock
=================

FastForward Clock provides PSR-20 compliant clock implementations for PHP applications.

This package offers two clock implementations:

- **SystemClock**: Returns the current system time, ideal for production use
- **FrozenClock**: Returns a fixed time, perfect for testing scenarios where you need deterministic time

Both clocks implement ``Psr\Clock\ClockInterface`` and integrate seamlessly with Fast Forward Container through ``ClockServiceProvider``.

Useful links
------------

- `GitHub repository <https://github.com/php-fast-forward/clock>`_
- `Issue tracker <https://github.com/php-fast-forward/clock/issues>`_
- `Fast Forward Container <https://github.com/php-fast-forward/container>`_
- `PSR-20 Clock <https://www.php-fig.org/psr/psr-20/>`_

.. toctree::
   :maxdepth: 2
   :caption: Contents:

   getting-started/index
   usage/index
   advanced/index
   api/index
   links/index
   faq
   compatibility
