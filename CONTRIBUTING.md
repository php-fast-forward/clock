# Contributing

Include one unique `.changelog/*.md` fragment for each observable change. Use
PHP 8.5+ and [CPX](https://github.com/laravel/cpx) to run the isolated tooling;
the library itself does not gain a runtime dependency:

```sh
cpx --skip-local fast-forward/changelog add "Describe the change." --category=fixed --type=patch --no-interaction
cpx --skip-local fast-forward/changelog check --no-interaction
# Compare committed changes with the contribution base:
cpx --skip-local fast-forward/changelog check --since=origin/main --no-interaction
```

Select the category and semantic impact that describe the change. Keep published
history intact; pending notes belong in fragments rather than an `Unreleased`
section.

Run `composer install` to restore the project skill into
`.agents/skills/fast-forward-changelog` through the development-only `llm/skills`
plugin and the reviewed GitHub source in `skills.json`. Generated skill files
are ignored by Git. Use `composer skills:show` to inspect the source and
`composer skills:update --from=github` for an explicit refresh within its
configured version range. Review `skills.json` before changing that range.

The [workflow](.github/workflows/changelog.yml) validates PR notes,
maintains PR feedback and prepares one version PR on `changelog/version`.
Review that PR's version, history and consumed fragments before merging.
After that approved version PR is merged into `main`, automatic publication
creates its version tag and GitHub Release. The workflow never merges PRs.

`CHANGELOG.md` includes the published `v1.0.0` history, backfilled from its
GitHub Release. The workflow preserves that evidence with `source: github`.
Empty release bodies
still need editorial review; no PR attribution is inferred from prose alone.

## Composer dependency skills

The development-only manager restores `fast-forward-container` and
`fast-forward-config` from the installed Composer packages, including Config as
a transitive dependency. `skills.json` explicitly trusts only those two Composer
donors; dependency trust does not extend to other installed packages. No separate
GitHub source or version pin is used for either library.

Inspect donors with `composer skills:show` and refresh their payloads with
`composer skills:update --from=composer`. The existing GitHub source continues
to manage the Changelog contributor skill independently. Generated copies remain
ignored. Synchronization does not upgrade runtime dependencies; an installed
version without a bundled skill contributes no skill. Check installed versions
and the bundled skill's supported contract before using its instructions.
