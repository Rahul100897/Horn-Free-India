# Horn Free India — Codex instructions

## Scope

- The production WordPress theme source is `Wordpress/horn-free-theme/`.
- The root HTML/CSS/JS files are the original static prototype and should remain available as design reference.
- Do not edit WordPress core or plugin files.
- Advanced Custom Fields is required; homepage fields are registered in `inc/acf-fields.php`.

## Engineering rules

- Support PHP 7.4 or newer and current WordPress coding/security practices.
- Escape frontend output, sanitize incoming data, and protect state-changing AJAX actions with nonces.
- Keep the Ministry recipient fixed at the verified official address unless the project owner explicitly changes it.
- Never commit credentials, hosting secrets, private keys, database dumps, or supporter data.
- Increment `_S_VERSION` and the `Version` header when CSS or JavaScript behavior changes.
- Preserve accessibility, mobile responsiveness, reduced-motion support, and keyboard navigation.

## Verification

- Run `scripts/validate-theme.sh` after theme changes.
- Run `scripts/package-theme.sh` when an installable ZIP is requested.
- Verify the ZIP contains a single top-level `horn-free-theme/` directory.
- Test supporter submission, one-time email-action counting, Gmail/Outlook/default-app/copy options, country selection, and mobile navigation before approving a production deployment.

## Git workflow

- Keep generated archives in `dist/`; they are intentionally ignored by Git.
- Use focused commits and do not rewrite shared history.
- Production deployment must come from a reviewed commit and requires explicit approval.
