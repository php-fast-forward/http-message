# Contributing

Add one independent `.changelog/*.md` fragment for each observable change.
Use [Fast Forward Changelog](https://github.com/php-fast-forward/changelog)
through CPX with PHP 8.5 or newer; the package's PHP requirements stay unchanged.

```sh
cpx --skip-local fast-forward/changelog help add
cpx --skip-local fast-forward/changelog add "Describe the change." --category=fixed --no-interaction
cpx --skip-local fast-forward/changelog check --no-interaction
```

Choose the category and semantic impact that describe the change. Commit the
fragment with the contribution; leave published history intact and do not add
an `Unreleased` section to `CHANGELOG.md`. After committing, validate the PR delta
with `check --since=origin/main --source=github --no-interaction` using the same CLI.

[The installed project skill](.agents/skills/fast-forward-changelog/SKILL.md)
explains the contribution and release lifecycle. Check its integrity with
`cpx --skip-local fast-forward/changelog skill:install --check --json`.
The skill is a local instruction payload, independent of a Composer dependency.
Its PHP integration example currently shows a CLI check outside the disposable
target directory. Run that check with `--cwd=/absolute/project` to validate the
same project. The bundled payload remains intact so native integrity checks and
future updates remain reliable; the example correction belongs upstream.

The GitHub workflow checks fragments, maintains PR feedback and prepares a
version PR from stable ancestral tags and committed history. Review that PR's
version, history and fragment deletions before merging it. It never merges itself;
its approved merge publishes the version tag and GitHub Release. Installing the
workflow or merging an ordinary contribution does not publish a release.

With the default `GITHUB_TOKEN`, a generated version PR's `pull_request` runs
can require approval. Select **Approve workflows to run**, then inspect the
results before merging. A GitHub App installation token is optional when CI
must start without that approval; follow the selected release's
[App configuration guide](https://github.com/php-fast-forward/changelog/blob/v0/docs/github-app.md).

There was no central changelog before this integration. Its historical backfill
preserves existing GitHub Release text and links verified ancestral versions in
Keep a Changelog format. Releases `v1.4.0` and `v1.2.0` contain only comparison
links; `v1.3.0`, `v1.1.0` and `v1.0.0` have empty bodies. These gaps cannot supply
change descriptions or origin PRs, so do not invent them. New fragments resolve
their origin PR during consolidation when GitHub can prove the association;
leave optional PR metadata absent until that evidence exists. The GitHub Release
body for each new version uses the same consolidated notes as `CHANGELOG.md`.
