Aliases
=======

Alias mapping
-------------

``ClockServiceProvider`` contributes the following alias map:

+---------------------------------------------+-----------------------------------------------------------+
| Requested service                           | Target service                                            |
+=============================================+===========================================================+
| ``Psr\Clock\ClockInterface``                | ``FastForward\Clock\SystemClock``                         |
+---------------------------------------------+-----------------------------------------------------------+

Operational consequences
------------------------

- resolving the PSR interface gives the ``SystemClock`` instance;
- resolving the concrete ``SystemClock`` gives that same instance;
- replacing ``SystemClock`` in an earlier provider overrides the default cleanly.

Recommended override strategy
-----------------------------

When you want deterministic time in tests:

1. register a provider that exposes your ``FrozenClock``;
2. alias ``Psr\Clock\ClockInterface`` to your frozen clock;
3. pass that provider before ``ClockServiceProvider`` to ``container()``.
