# TT5 Child Theme

A WordPress child theme built on [Twenty Twenty-Five](https://wordpress.org/themes/twentytwentyfive/) (TT5). Scaffolded as a reusable starting point — Phase 1 is generic infrastructure, Phase 2 is client-specific. See [Using as a starter](#using-as-a-starter) if you're forking this for a new project.

---

## Stack

- **WordPress** block theme (TT5 parent)
- **ACF PRO** — Repeater, Gallery, and `acf_register_block_type()` for custom blocks
- **Vite** — asset bundling for block scripts and styles
- **Tailwind CSS** — utility-first styling scoped to block output
- **TypeScript** — block editor-side JS (`edit.js` / `index.js`); vanilla JS where interactivity is minimal
- **SEOPress (free)** — XML sitemap, title/meta; schema markup is hand-coded in the theme (see `inc/schema.php`)

---

## Project structure

```
├── blocks/                  # Custom ACF PRO blocks (one directory per block)
│   └── example-block/
│       ├── block.json
│       ├── render.php
│       ├── edit.js          # Editor-side (TypeScript compiled by Vite)
│       └── style.css        # Frontend style (compiled by Vite)
├── inc/
│   ├── blocks.php           # acf_register_block_type() calls
│   ├── schema.php           # Hand-coded JSON-LD output (LocalBusiness / EventVenue / Organization)
│   └── setup.php            # Theme supports, image sizes, nav menus
├── parts/                   # Block template parts (HTML)
├── patterns/                # Block patterns (PHP)
├── templates/               # Block templates (HTML)
├── functions.php
├── style.css                # Child theme header + minimal base styles
├── theme.json               # Design tokens: palette, typography, spacing
├── package.json
├── vite.config.ts
└── tsconfig.json
```

---

## Development

### Requirements

- Node 20+
- ACF PRO (license required — add via WP Admin after cloning)
- Local by Flywheel (or equivalent local WordPress environment)

### Setup

```bash
npm install
npm run dev      # Vite dev server with HMR
npm run build    # Production build — output committed to repo (no CI pipeline)
```

Block assets are registered via `block.json` (`style`, `script`, `viewScript` handles). WordPress enqueues them only on pages where each block is present — no global stylesheet bloat.

---

## Two-phase structure

### Phase 1 — Generic infrastructure (reusable across projects)

Everything in this phase is client-agnostic:

- `style.css` child theme header and base reset
- `functions.php` parent theme enqueue, `add_theme_support()` declarations
- `theme.json` with sensible defaults (tokens swapped per project)
- Vite + Tailwind + TypeScript toolchain wired up
- `inc/blocks.php` block registration pattern ready to populate
- `inc/schema.php` schema output infrastructure (swap in client NAP/type)
- `inc/setup.php` for theme supports, nav menus, image sizes

When Phase 1 is complete and merged to `main`, this repo is marked as a **GitHub Template** — new projects start from "Use this template," not a manual clone-and-strip.

### Phase 2 — Client-specific (Brannen Lake Estates)

Everything that is specific to this client:

- Design tokens in `theme.json` (brand colours, typography, spacing)
- Client ACF blocks (`blocks/` directory)
- Client schema data (NAP, venue type, coordinates)
- Page templates and block patterns
- Content-specific block patterns (hero, venue-teaser cards, CTA band, etc.)

Phase 2 work lives in `develop` and is merged to `main` when stable.

---

## Branch strategy

| Branch | Purpose |
|---|---|
| `main` | Stable, deployable. Merges from `develop` when work is tested. |
| `develop` | Active development. Commits here often to protect against file loss. |

When Phase 1 is solid on `main`, the repo is set as a GitHub Template Repository (Settings → check "Template repository"). Future client builds fork from the template rather than from this repo's history.

---

## Schema markup

This theme does **not** use SEOPress Pro's schema editor. JSON-LD is output directly from `inc/schema.php` via a `wp_head` hook. If SEOPress is ever upgraded to Pro, its schema editor can take over — or the hand-rolled version can stay. Either way, do not configure schema in both places.

Current schema types: `LocalBusiness`, `EventVenue`, `Organization`.

---

## Using as a starter

This repo is a GitHub Template. To start a new project:

1. Click **Use this template** → **Create a new repository** on GitHub.
2. Clone your new repo and open in Local.
3. Update `style.css` — theme name, description, author.
4. Swap design tokens in `theme.json`.
5. Update NAP and schema type in `inc/schema.php`.
6. Delete any client-specific blocks from `blocks/` that don't apply.
7. Run `npm install && npm run dev`.

---

## Plugins assumed

| Plugin | Notes |
|---|---|
| ACF PRO | License required. Blocks won't register without it. |
| SEOPress (free) | XML sitemap, title/meta control. Schema is handled in-theme. |
| Contact Form 7 | Inquiry / contact forms. Flamingo (companion) retained for submission storage. |
| Google Site Kit | Connect Search Console post-launch; no setup needed in local dev. |
| Cloudflare Turnstile | Spam protection for CF7 — install on production only. |
