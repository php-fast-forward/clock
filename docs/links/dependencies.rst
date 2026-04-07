Dependencies
============

Direct runtime dependencies
---------------------------

+-------------------------------------+---------------------------------------------------------------+
| Dependency                          | Purpose                                                       |
+=====================================+===============================================================+
| ``fast-forward/container``          | Provides the service provider integration and container API   |
+-------------------------------------+---------------------------------------------------------------+
| ``psr/clock``                       | Provides the PSR-20 interface used by application services    |
+-------------------------------------+---------------------------------------------------------------+
| ``symfony/clock``                   | Provides ``NativeClock``, ``MockClock``, and Symfony's API    |
+-------------------------------------+---------------------------------------------------------------+

Development dependencies
------------------------

+-------------------------------------+---------------------------------------------------------------+
| Dependency                          | Purpose                                                       |
+=====================================+===============================================================+
| ``fast-forward/dev-tools``          | Provides PHPUnit integration, QA tooling, and CI helpers      |
+-------------------------------------+---------------------------------------------------------------+

Related references
------------------

- `Fast Forward Container <https://github.com/php-fast-forward/container>`_
- `Symfony Clock documentation <https://symfony.com/doc/current/components/clock.html>`_
- `PSR-20 Clock <https://www.php-fig.org/psr/psr-20/>`_
