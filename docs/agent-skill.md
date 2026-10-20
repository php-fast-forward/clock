# Install and maintain the Clock consumer skill

The `fast-forward-clock` skill supports `fast-forward/clock ^1.1` on PHP ^8.3,
with `psr/clock ^1.0`, Container `^1.6` and Config `^1.4`. Earlier Clock releases
are outside its contract. Inspect `composer show fast-forward/clock` and confirm
`vendor/fast-forward/clock/resources/skills/fast-forward-clock/SKILL.md` exists.
Do not upgrade an application solely to obtain instructions without authorization.

Composer ships the canonical payload in `resources/skills/fast-forward-clock`.
Shipping files, copying them into a discovery directory, and an agent loading
them in a refreshed session are separate steps. The optional suggestion does
not install a plugin or activate a skill. Choose one route and scope to avoid
ambiguous duplicate copies. Project scope is recommended for team review.

## Optional Composer project synchronization

Use Composer 2 and the optional development plugin `llm/skills ^1.15`. Before
installing/enabling it, merge this into the consumer's `skills.json`, preserving
intentional existing sources, target and aliases:

```json
{
  "target": ".agents/skills",
  "aliases": [],
  "discovery": false,
  "auto-sync": true,
  "vendor-sources": false,
  "dependencies": {
    "composer": {
      "trusted": ["fast-forward/clock"],
      "trusted-replace": true
    }
  }
}
```

The narrow allowlist trusts this donor; `trusted-replace` replaces implicit
dependency trust. No extra host aliases are requested. For a Claude Code-only
project choose `.claude/skills` if that is the host's configured discovery root.
With authority to execute this project plugin, merge its approval and install:

```sh
composer config allow-plugins.llm/skills true
composer require --dev 'llm/skills:^1.15'
composer skills:show
```

Preserve other plugin approvals. Review/commit intended Composer files and
`skills.json` under the application's policy. Approved install/update operations
automatically synchronize trusted installed donors; no post-install hook is
needed. Inspect the complete destination `.agents/skills/fast-forward-clock`.

For explicit synchronization only, set `auto-sync:false` before installation:

```sh
composer skills:show
composer skills:update fast-forward/clock --dry-run
composer skills:update fast-forward/clock
```

Naming a package selects/trusts it for that run. It does not test the automatic
allowlist. In Composer 2.10.3 with llm/skills 1.15.0, `--no-scripts` alone does
not prevent synchronization; use `auto-sync:false` or `--no-plugins` as
appropriate. Recheck these behaviors when the tooling version changes.

Synchronization overwrites incoming same-path files but retains additional
local files and files removed upstream. Removing the donor or plugin leaves
copied skills behind. Treat copies as donor-managed and keep custom instructions
in a separately named consumer-owned package. Record source versions and review
diffs on update.

Preview cleanup with `composer skills:update --clean --dry-run`. An unscoped
clean can delete handwritten skills in the target; do not execute it as an
automatic hook. Scoped clean reinstalls only selected current donors and cannot
identify a donor already removed. Inventory and back up exact stale copies
outside discovery before any separately authorized cleanup.

## Guarded manual project or global copy

From the consumer root, copy the entire installed payload, including references,
examples and license:

```sh
skill_source="vendor/fast-forward/clock/resources/skills/fast-forward-clock"
skill_target=".agents/skills/fast-forward-clock"
test -f "$skill_source/SKILL.md" &&
test ! -e "$skill_target" &&
test ! -L "$skill_target" &&
mkdir -p "$(dirname "$skill_target")" &&
cp -R "$skill_source" "$skill_target"
```

For an explicit global Codex choice, set
`skill_target="$HOME/.agents/skills/fast-forward-clock"` instead. Claude Code
users may choose `.claude/skills/fast-forward-clock` or its home counterpart.
Confirm the actual host's discovery roots. Never link a destination back into
replaceable `vendor/` content. A missing source calls for package/archive
inspection, not an empty placeholder.

For an update or removal, archive only this existing package outside discovery:

```sh
skill_backup="$HOME/.agent-skill-backups/fast-forward-clock-$(date +%Y%m%d%H%M%S)-$$"
test -d "$skill_target" &&
test ! -L "$skill_target" &&
test ! -e "$skill_backup" &&
test ! -L "$skill_backup" &&
mkdir -p "$(dirname "$skill_backup")" &&
mv "$skill_target" "$skill_backup"
```

Moving the selected copy out removes it from discovery. For update, review the
new source and repeat the guarded copy. For rollback, archive any replacement,
restore the complete backup to the original destination and refresh the agent
session. Keep backups outside skill-discovery directories. Plugin-managed copies
can return on sync until the consumer changes the donor/trust configuration.

## Optional pinned Skills CLI

With Node.js 22.20.0+, npm and Git for repository sources:

```sh
npx --yes skills@1.7.2 add ./vendor/fast-forward/clock/resources/skills/fast-forward-clock --skill fast-forward-clock --agent codex --copy --yes
npx --yes skills@1.7.2 list --agent codex
```

Choose the actual host (`claude-code` where appropriate). Add `--global` to both
commands for an explicit global copy. `--copy` avoids a vendor symlink. Review
project copies and CLI lockfiles. A repository alternative requires setting
`SKILL_REF` to a reviewed full immutable commit first:

```sh
npx --yes skills@1.7.2 add "https://github.com/php-fast-forward/clock/tree/$SKILL_REF/resources/skills/fast-forward-clock" --skill fast-forward-clock --agent codex --copy --yes
```

Update by reviewing a new revision, backing up local changes and repeating the
chosen route. Remove only the selected name/scope:

```sh
npx --yes skills@1.7.2 remove fast-forward-clock --agent codex --yes
# Explicit global alternative:
npx --yes skills@1.7.2 remove fast-forward-clock --agent codex --global --yes
```

Installing a Composer plugin globally does not install this skill globally.
After any route inspect the copied files, refresh the session, confirm the host
loads `fast-forward-clock`, and request a small clock integration task. A copied
file or successful CLI exit alone does not prove host activation.

Tool references: [llm/skills 1.15.0](https://github.com/roxblnfk/skills/tree/1.15.0),
[Composer plugin approval](https://getcomposer.org/doc/06-config.md#allow-plugins),
[Skills CLI](https://github.com/vercel-labs/skills).
