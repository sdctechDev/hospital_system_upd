# Hospital System: frontend

Drop-in replacement for `assets/frontend/`. **No PHP page, class or API needs to change.**
All variable names, form field names, element ids, `data-` hooks and API calls are the same as before.

## Design direction
Dark, calm, high-trust. One theme everywhere.

| Token | Value | Used for |
|---|---|---|
| Background | `#0B0F19` | page |
| Surface / border | `#161B26` / `#242C3D` | panels, 1px borders (no heavy shadows) |
| Accent | `#6366F1` | the only interactive colour: buttons, selected states, focus |
| Signature | `#D8F264` (lime) | the hand-drawn ring on the active nav item, "today" markers |
| Status | amber / green / blue / red / lilac | appointment + payment states only |

Fonts: Red Hat Display (headings), Geist (UI), Inter (body copy). Loaded from Google Fonts in
`layouts/main.php`; system fonts are the fallback if offline.
No images are used anywhere. Icons are inline SVG (`icon('name')` in `helpers.php`).

## What changed
- **Layout:** floating glass pill nav on desktop; icon tab bar on mobile. Signed-out visitors get a split
  sign-in screen (the layout detects this automatically).
- **Selections:** role switcher, gender, weekday, slot length and payment method are now chips / cards.
  Booking is a 4-step flow: doctor picker with search, 14-day date strip, time slots grouped
  Morning / Afternoon / Evening, and a sticky summary.
- **Lists:** appointments are rows with calendar tiles and live status filters; admin tables turn into
  cards on phones; patients and doctors have live search.
- **Feedback:** toasts replace the inline message box; a styled dialog replaces `confirm()`.
- **Payment:** card number / expiry auto-format, live card preview, test-card shortcuts.
- **Receipt:** ticket layout, prints cleanly in black on white.

## Files
```
css/variables.css   design tokens (edit this to re-theme)
css/base.css        reset, type, utilities
css/layout.css      nav, tab bar, page shell, grids (md 768 / lg 1024 / xl 1280)
css/components.css  buttons, fields, chips, badges, tables, rows, toasts, dialog
css/pages.css       auth, booking, payment, receipt, availability   (new file)
css/print.css       receipt printing
js/ui.js            toasts, confirmDialog(), filters, password toggle (always loaded first)
js/book.js pay.js appointments.js status.js
helpers.php         formatting + icon(), initials(), greeting(), dateBlock()
templates/          layouts, partials (nav, flash, auth-hero) and every page
```

## Notes
- Needs PHP 7.4+ (arrow functions), same as the previous templates.
- `ui.js` is loaded automatically by the layout; pages that still list it in `$scripts` are de-duplicated.
- Helper names added to `helpers.php`: `icon`, `initials`, `firstName`, `greeting`, `dateBlock`, `brandMark`.
  None clash with existing functions in the project.
