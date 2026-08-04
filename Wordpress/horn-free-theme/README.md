# Horn Free India WordPress theme

This is the supplied `horn-free-theme` starter converted into a dynamic WordPress theme. It preserves the HTML template's design and includes all original image assets.

## Installation

1. In WordPress, go to **Appearance → Themes → Add New → Upload Theme**.
2. Upload `horn-free-theme.zip`, install it, and activate it.
3. Install and activate the free **Advanced Custom Fields (ACF)** plugin.
4. Create a page named **Home**, then choose it under **Settings → Reading → Your homepage displays → A static page**.
5. Edit the Home page. The **Horn Free India — Homepage** ACF panel contains tabs for every homepage section.
6. Optionally create a menu and assign it to the **Primary** menu location. Without a menu, the campaign's anchor navigation is shown automatically.

The ACF fields are registered by the theme in `inc/acf-fields.php`; no import is required. A standalone `acf-export-horn-free-homepage.json` export is also included if you prefer to import and manage the group in **ACF → Tools → Import Field Groups**. The fields include visible default content. The footer is global and static in `footer.php`, so it is intentionally not part of the Home page field group.

## Supporter form

The Take Action flow uses nonce-protected WordPress AJAX endpoints. Submitting contact details creates a pending supporter record, but does not change the public count. Clicking **Send my email** opens an accessible provider dialog with Gmail, Outlook, the device's default email app, and a copy-message fallback. The supporter is counted once when one of these methods is selected. Email addresses are deduplicated by SHA-256 hash. Because external compose screens cannot report delivery, the count represents unique email-action selections, not verified delivered emails. The public total equals the optional **Verified existing count** ACF value plus confirmed actions.

The primary email recipient is fixed in the theme as the official ministerial address `nitin.gadkari@nic.in`; this prevents an outdated ACF value from redirecting campaign messages. The Horn Free India contact remains editable as the CC address.

The supporter form collects only Full Name, State, Country and Email. Country is a required dropdown backed by a self-contained 249-entry ISO 3166 country/region list embedded in the theme PHP, so it does not depend on host file-read settings. These values and the counted status are visible under **Supporters** in the WordPress dashboard.

Before launch, replace placeholder content, verify the Ministry and CC email fields, add a privacy policy, and ensure your site's data collection complies with applicable privacy rules.

## Developer notes

- `front-page.php` — dynamic campaign homepage
- `inc/acf-fields.php` — local ACF field group and fallback helper
- `assets/css/site.css` — converted template styles
- `assets/js/site.js` — navigation, animation, AJAX signup, email and WhatsApp actions
- `functions.php` — assets, supporter post type, count and AJAX handler
