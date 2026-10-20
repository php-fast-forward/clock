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

## Container consumer skill

The same development-only manager also restores `fast-forward-container` from
`php-fast-forward/container` within `^1.10.0`, alongside the other configured
skills. Inspect all sources with `composer skills:show` and refresh them with
`composer skills:update --from=github`. Generated copies stay ignored; this
configuration does not upgrade the runtime library. Check the installed
Container version before following the skill: its API contract supports `^1.10`
and requires reporting earlier versions instead of assuming compatibility.
