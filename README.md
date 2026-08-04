# Horn Free India

A citizen movement to replace **"Blow Horn"** signage on India's commercial vehicles
with messages of peace — *Stop Horn, No Horn, Om Shanti* — for safer streets, quieter
cities, and a calmer India.

**No donations. No politics. The only thing we collect is voices.**

🔗 **Live preview:** https://rahul100897.github.io/Horn-Free-India/

---

## What this is

A modern, mobile-first campaign site with both the original static prototype and a production-ready WordPress theme.

The WordPress theme lives in [`Wordpress/horn-free-theme`](Wordpress/horn-free-theme). It uses ACF for homepage content, stores supporter submissions privately in WordPress, counts unique email actions, and supports Gmail, Outlook, default-mail-app, and copy-message sending options.

The page is structured as a narrative argument:

1. **Hero** — *From Blow Horn to Om Shanti*
2. **The Problem** — the noise, the health cost, the scale
3. **Our Ask** — one specific, winnable change to truck signage
4. **Proof it works** — the 2015 Maharashtra precedent
5. **Take Action** — the 3-step engine: add your name → email the Ministry → share
6. **Momentum** — live counter + supporter voices
7. **Our Story** — who's behind it, and the no-money / non-partisan promises
8. **Closing call to action**

## Files

| File | Purpose |
|------|---------|
| `index.html` | The full page and copy |
| `styles.css` | The "noise → calm" design system |
| `main.js`   | Counter, 3-step Take Action flow, email + WhatsApp builders, animations |
| `server.js` | Tiny zero-dependency static server for local preview |

## Run it locally

```bash
node server.js
# then open http://localhost:4321
```

(Or open `index.html` directly in a browser.)

## WordPress theme development

```bash
sh scripts/validate-theme.sh
sh scripts/package-theme.sh
```

The package command creates an installable ZIP in `dist/`. GitHub Actions also validates and packages the theme automatically. See [`docs/CODEX-WORDPRESS-SETUP.md`](docs/CODEX-WORDPRESS-SETUP.md) for Codex, CI, and deployment setup.

## Before going live — things to edit

The copy intentionally contains `[placeholders]` to fill in:

- **Founder name(s)** and the one-line origin story (in *Our Story*)
- **Real supporter count** — the counter currently starts at a sample number
- **Citation sources** next to the two statistics (CPCB, MoRTH)
- **Social links** in the footer

## WordPress supporter workflow

The WordPress implementation stores supporter details in the private `hfi_supporter` post type. A supporter is counted once after choosing an email action. WordPress does not send the Ministry email; it opens the visitor's Gmail, Outlook, default email app, or provides a copyable message.

## License

This project belongs to the Horn Free India movement.
