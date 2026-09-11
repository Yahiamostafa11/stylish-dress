# Styliiiish Landing Style Guide

## Theme Base (eKart aligned)
- Primary color: `rgb(213, 21, 34)`
- Primary hex equivalent: `#d51522`
- Primary hover: `#b70f1a`
- Secondary color: `#17273B`
- Border line: `rgba(189, 189, 189, 0.4)`
- Soft background: `#f6f7fb`
- Card background: `#ffffff`
- Muted text: `#5a6678`

## CSS Tokens (keep unchanged unless requested)
```css
:root {
  --wf-main-rgb: 213, 21, 34;
  --wf-main-color: rgb(var(--wf-main-rgb));
  --wf-secondary-color: #17273B;
  --bg: #f6f7fb;
  --card: #ffffff;
  --text: #17273B;
  --muted: #5a6678;
  --line: rgba(189, 189, 189, 0.4);
  --primary: var(--wf-main-color);
  --primary-2: #b70f1a;
  --secondary: var(--wf-secondary-color);
}
```

## Direction & Language
- Layout direction: `RTL`
- HTML requirement:
```html
<html lang="ar" dir="rtl">
```
- Keep Arabic-first UX for landing sections.

## Footer Policy (Homepage)
Always keep legal/footer blocks on homepage:
- Quick Links
- Official Info
- Legal links
- Site link (`https://styliiiish.com/`)
- Copyright line

## Current Source of Truth
- Homepage implementation: `resources/views/home.blade.php`
- This guide must be used for all future landing edits.
