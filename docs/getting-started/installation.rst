Installation
=============

Requirements
------------

- PHP 8.3 or higher
- ``fast-forward/container`` 1.6 or higher
- ``psr/clock`` 1.0

Install the package with Composer:

.. code-block:: bash

   composer require fast-forward/clock

What this package provides
--------------------------

This package provides two PSR-20 compliant clock implementations:

- ``FastForward\Clock\SystemClock``: Returns the current system time with optional timezone support
- ``FastForward\Clock\FrozenClock``: Returns a fixed time, ideal for testing

Additionally, it provides ``ClockServiceProvider`` for integration with Fast Forward Container.

If your application already uses Fast Forward Container, simply register the service provider to get started.
