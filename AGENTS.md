# Clock repository

This repository publishes `fast-forward/clock`, a PHP 8.3+ PSR-20 library.
Keep application examples aligned with `src/`, Composer requirements and tests.
Preserve unrelated working changes. Run shell commands through RTK when available.

## Validation and contributions

Use `composer dev-tools` for the repository checks and the existing PHPUnit
suite for executable behavior. Add an independent `.changelog/*.md` fragment
with `category` and `type`; do not consolidate history in an ordinary PR.
Keep merge, tags and release publication subject to maintainer authorization.

## Bundled consumer skill

Read [fast-forward-clock](resources/skills/fast-forward-clock/SKILL.md) when
changing consumer integration examples, consumer guides or the skill. Keep its
version contract, references and executable examples aligned with the supported
public API. This source link needs no installed copy or host alias. Repository
maintenance and checks continue to follow this file. The skill owns its portable
consumer procedure in `SKILL.md`; there are no independent child contracts.
