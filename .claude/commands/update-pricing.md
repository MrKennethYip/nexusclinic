Update service pricing across all relevant pages. The argument is the service slug (e.g. `rmt_massage`).

Read `data/pricing/$ARGUMENTS.json` first, then perform every step below in order.

---

## Available service slugs

| Slug | Service |
|------|---------|
| `rmt_massage` | RMT Massage |
| `hot_stone_massage` | Hot Stone Massage |
| `manual_lymphatic_drainage` | Manual Lymphatic Drainage Massage |
| `indian_head_massage` | Indian Head Massage |
| `sports_massage` | Sports Massage Therapy |
| `aromatherapy_massage` | Aromatherapy Massage |
| `prenatal_massage` | Prenatal Massage |
| `chiropractic` | Chiropractic |
| `physiotherapy` | Physiotherapy |
| `pelvic_health_physiotherapy` | Pelvic Health Physiotherapy |
| `acupuncture` | Acupuncture |
| `cosmetic_acupuncture` | Cosmetic Acupuncture |
| `shiatsu_tuina` | Shiatsu / Tuina Massage |
| `herbs_nutrition` | Herbs & Nutrition |
| `orthotics` | Orthotics |

---

## Step 1 — Read the JSON

From `data/pricing/$ARGUMENTS.json`, extract:
- `name` — human-readable service name
- `heading_text` — text to locate this service's pricing card in each file
- `files` — list of PHP files to update
- `prices` — array of `{label, price}` pairs representing the desired state
- `section_divider` _(optional)_ — text of a `<span class="u-divider ...">` inside the content block that splits it into sub-sections
- `target_section` _(optional, only used when `section_divider` is present)_ — either `"before"` or `"after"`, indicating which sub-section to update

Any field starting with `_` is a note for context only — do not act on it directly.

---

## Step 2 — Update each file

For each file path in `files`, read the file and perform the following:

### 2a — Locate the pricing card

Find the `<strong>` heading element that contains `heading_text`. Rules for matching:
- Case-insensitive
- Strip any `<br>` or `<br/>` tags from the element before comparing
- For `heading_text = "PHYSIOTHERAPY"`: match the first card whose heading does **not** also contain "PELVIC"
- For `heading_text = "ACUPUNCTURE"`: match the card whose heading does **not** also contain "HERBS"
- For `heading_text = "CHIROPRACTIC"`: match only if not inside a sentence (it should be the entire content of the `<strong>` element, not part of a paragraph)

### 2b — Find the content block

Within that pricing card, find the `<!-- Content -->` comment. The price rows are in the `<ul class="list-unstyled">` block between `<!-- Content -->` and `<!-- End Content -->`.

### 2c — Narrow to the target sub-section (if applicable)

If `section_divider` is present in the JSON:

1. Within the `<ul class="list-unstyled">` block, locate the `<span class="u-divider ...">` element whose text matches `section_divider`.
2. If `target_section` is `"before"`: only consider `<li>` rows that appear **before** that `<span>`.
3. If `target_section` is `"after"`: only consider `<li>` rows that appear **after** that `<span>`.
4. If no divider is found in a file (e.g. `service/acupuncture.php` has no cosmetic divider), treat the entire content block as the target and update all matching rows normally.

If `section_divider` is absent, the entire content block is the target.

### 2d — Update price rows

For each `{label, price}` pair in `prices`:

1. Find the `<li class="u-pricing-v2__list-item ...">` element within the target sub-section whose text matches `label`. When matching:
   - Normalize: treat a hyphen (`-`) and a space as equivalent (e.g. `Follow-Up` matches `Follow Up`)
   - Match case-insensitively

2. Replace only the content inside the `<strong>...</strong>` tag on that line with the new `price` value. Preserve everything else on the line exactly (indentation, class names, the label text, ` - `, surrounding HTML).

3. If no matching row is found for a label, **skip it** and note it in the final report — do not add a new row.

### 2e — Do not touch

Leave these unchanged in every file:
- `<li>` rows containing `&nbsp` (padding rows)
- `<span class="u-divider ...">` sub-section dividers (e.g., `TELEHEALTH`, `COSMETIC ACUPUNCTURE`)
- `<li class="small ...">` note/footnote rows
- All HTML outside the `<!-- Content -->` … `<!-- End Content -->` block

---

## Step 3 — Confirm

After updating all files, report:
- Which files were updated
- Which price rows were changed (old value → new value) per file
- Any labels from the JSON that were **not found** in a file (skipped)
- Remind the user to run `gulp dist` before deploying to regenerate minified assets
