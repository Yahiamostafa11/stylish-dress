# WebsiteFlexi — SYSTEM RULES

This file defines mandatory engineering rules for this project.
All AI-generated and human-written code must comply.

---

## 1. General Principles

- Do NOT break existing flows documented in PROJECT_BRAIN.md.
- Always prefer minimal, isolated changes.
- No refactoring unless explicitly requested.
- Preserve backward compatibility.
- Every change must consider vendor, owner, and admin roles.

---

## 2. Coding Rules

### PHP (WordPress)

- All AJAX handlers MUST:
  - Verify nonce
  - Check capability / role
  - Sanitize inputs
  - Return via wp_send_json_*

- Never access $_POST / $_GET directly without sanitization.
- Use wpdb only through $wpdb with prepare().
- Prefer WordPress APIs over raw SQL.
- No direct echo in handlers (except debug mode).
- All new hooks must be documented.

### JavaScript

- No global variables unless registered in PROJECT_BRAIN.md.
- Wrap new modules in closures or namespaces.
- All AJAX calls must use localized objects.
- Always handle:
  - success
  - error
  - timeout

- No inline JS in PHP unless unavoidable.

### CSS

- No inline styles.
- Prefer class-based styling.
- Do not modify owner-style.css without impact analysis.
- New components must be isolated.

---

## 3. Naming Conventions

### Files

- PHP: kebab-case.php
- JS: kebab-case.js
- CSS: kebab-case.css

### Functions

- Prefix: wf_ (WebsiteFlexi)
- Example: wf_validate_vendor_product()

### AJAX Actions

- Format:
  wf_{module}_{action}

  Example:
  wf_product_create

### Variables

- camelCase in JS
- snake_case in PHP
- No single-letter variables outside loops.

---

## 4. Security Rules

### Authentication & Authorization

- Every sensitive action must verify:
  - nonce
  - user role
  - object ownership

- Vendors must never access other vendors’ data.

### Data Validation

- All input must be sanitized and validated.
- All output must be escaped (esc_html, esc_attr).

### File Uploads

- Restrict MIME types.
- Enforce size limits.
- Store outside public folders when possible.

### Database

- No dynamic SQL without prepare().
- All schema changes require migration logic.

---

## 5. Performance Rules

- Avoid heavy queries in loops.
- Use caching (transients) for repeated reads.
- Avoid time() for asset versioning in production.
- Lazy-load large UI modules.

---

## 6. Logging & Debugging

- Use WP_DEBUG_LOG for server errors.
- Never leave var_dump / console.log in production.
- Wrap debug code with environment checks.

---

## 7. Change Management

Before any change:

1. Check PROJECT_BRAIN.md
2. Identify affected flow
3. Validate security
4. Test vendor + admin flows

After change:

- Update PROJECT_BRAIN.md
- Add note in CHANGELOG_AI.md

---

## 8. AI Usage Policy

When using AI:

- Always load PROJECT_BRAIN.md first.
- Always load SYSTEM_RULES.md second.
- Do not generate code violating these rules.
- Ask for clarification if conflict exists.

Default AI prompt:

"Read PROJECT_BRAIN.md and SYSTEM_RULES.md before answering."

---

## 9. Release Checklist

Before deployment:

- ✅ No debug output
- ✅ All nonces verified
- ✅ No hardcoded IDs
- ✅ Security scan passed
- ✅ Brain updated

---

## End of Rules
