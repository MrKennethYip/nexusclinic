# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Nexus Clinic is a PHP-templated marketing website for a massage therapy and rehabilitation clinic in Toronto. It has no backend logic, CMS, or database — pure PHP includes for templating, SASS/Bootstrap 4 for styling, and jQuery for interactivity. Images are served from AWS S3.

## Build & Development

```bash
npm install          # Install dependencies (first time)
gulp                 # Watch + compile SASS + start BrowserSync (default task)
gulp sass            # Compile SASS to assets/css/
gulp dist            # Production build: copyVendors + minCSS + minJS + minIMG
```

BrowserSync serves from the project root on `localhost:3000`. There are no tests.

For production, `assets/css/front.min.css` and `assets/js/front.min.js` are the minified outputs used by pages. Run `gulp dist` before deploying.

## Architecture

**PHP templating:** Every `.php` page follows the same structure — `includes/common-header.php` (analytics, fonts, meta tags), `includes/nav.php` (mega menu), page content, then `includes/footer.php`. Pages in subdirectories (e.g., `service/`, `profiles/`) use `../` relative paths for includes.

**SASS:** `assets/include/scss/front.scss` is the main entry point importing all component partials. Custom clinic overrides live in `custom-front.scss` (loaded last). Bootstrap SCSS is compiled separately by the `sass-bootstrap` gulp task (to `assets/vendor/bootstrap/css/`).

**JavaScript:** The `hs.*` component system (from the "Front" HTML template) initializes via data attributes on `$(document).ready()`. Custom tracking and overrides are in `assets/js/custom-front.js`. The minJS gulp task concatenates all `hs.*.js` files + `custom-front.js` into `front.min.js`.

**Staff profiles:** Each practitioner has a dedicated `.php` file in `profiles/`. The team listing page (`team.php`) and nav (`includes/nav.php`) must both be updated when adding/removing staff.

**Booking:** All booking CTAs link to `https://nexusclinic.clinicsense.com/book`. Newsletter signup posts to Mailchimp.

## Key Files

- `includes/nav.php` — main navigation (mega menu); update for any new pages or staff
- `includes/common-header.php` — GA4, Google Ads, Meta Pixel, Drift chat, all third-party scripts
- `includes/markup.php` — schema.org structured data (LocalBusiness, OfferCatalog)
- `assets/include/scss/custom-front.scss` — all clinic-specific style overrides
- `assets/js/custom-front.js` — custom JS and `handleClick()` event tracking calls
