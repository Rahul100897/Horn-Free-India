# Horn Free India

A citizen movement to replace **"Blow Horn"** signage on India's commercial vehicles
with messages of peace — *Stop Horn, No Horn, Om Shanti* — for safer streets, quieter
cities, and a calmer India.

**No donations. No politics. The only thing we collect is voices.**

🔗 **Live preview:** https://rahul100897.github.io/Horn-Free-India/

---

## What this is

A modern, mobile-first, single-page redesign of the Horn Free India homepage. Built as a
plain static site — no framework, no build step — so it can be hosted free anywhere.

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

## Before going live — things to edit

The copy intentionally contains `[placeholders]` to fill in:

- **Founder name(s)** and the one-line origin story (in *Our Story*)
- **Real supporter count** — the counter currently starts at a sample number
- **Citation sources** next to the two statistics (CPCB, MoRTH)
- **Social links** in the footer

## Important: the counter is front-end only

Right now, submitting the form personalizes the Ministry email and increments the counter
*on the visitor's own screen* — nothing is saved to a server. To make the count **real and
verifiable** (and to build a contactable supporter list), wire the form `submit` handler in
`main.js` to a backend or form service (Google Form/Sheet, Airtable, Formspark, etc.). Look
for the `// NOTE:` comment marking the exact spot.

## License

This project belongs to the Horn Free India movement.
