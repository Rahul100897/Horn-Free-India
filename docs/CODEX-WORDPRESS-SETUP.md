# Codex and WordPress workflow

## Local development

Open this repository in Codex. The repository-level `AGENTS.md` gives Codex the theme path, security rules, and verification commands.

Run:

```sh
sh scripts/validate-theme.sh
sh scripts/package-theme.sh
```

The installable ZIP is generated in `dist/` and is not committed.

## Continuous integration

`.github/workflows/theme-ci.yml` validates PHP, JavaScript, ACF JSON, the official recipient, and packages an installable ZIP on pushes and pull requests affecting the theme. Download the `horn-free-theme` artifact from the GitHub Actions run.

## Deployment

The deployment workflow is manual by design. Configure GitHub environments named `staging` and `production`, then add these secrets to each environment:

| Secret | Value |
|---|---|
| `WP_SSH_HOST` | WordPress server hostname |
| `WP_SSH_USER` | Restricted deployment user |
| `WP_SSH_PRIVATE_KEY` | Private SSH key for that user |
| `WP_SSH_KNOWN_HOSTS` | Verified SSH host-key entry from the hosting provider |
| `WP_THEME_PATH` | Absolute server path ending in `wp-content/themes/horn-free-theme` |

Run **Actions → Deploy WordPress theme → Run workflow**, choose `staging`, verify the site, and only then deploy to `production`.

Protect the production GitHub environment with a required reviewer. The deployment user should be restricted to the theme directory and must not have access to `wp-config.php`, database credentials, uploads, or other sites.

## WordPress requirements

- WordPress with PHP 7.4 or newer
- Advanced Custom Fields activated
- A static front page selected in **Settings → Reading**
- The theme activated under **Appearance → Themes**

Do not commit WordPress credentials, supporter exports, database dumps, or SSH keys.
