Remove a practitioner from the website. The argument is the practitioner's slug (e.g. `jane_doe`).

---

## Step 1 — Resolve the slug and service pages

Set `slug` = `$ARGUMENTS` (e.g. `jane_doe`).

If `data/practitioners/{slug}.json` exists, read it and extract the `services` array to know which service pages need updating.

If that file does not exist, check all service pages for the slug: `massage`, `chiropractic`, `physiotherapy`, `acupuncture`, `sports-massage`, `telehealth`.

**Service → page mapping:**

| `services` value | Service page                 |
|------------------|------------------------------|
| `massage`        | `service/massage.php`        |
| `chiropractic`   | `service/chiropractic.php`   |
| `physiotherapy`  | `service/physiotherapy.php`  |
| `acupuncture`    | `service/acupuncture.php`    |
| `sports-massage` | `service/sports-massage.php` |
| `telehealth`     | `service/telehealth.php`     |

---

## Step 2 — Archive the profile page

Rename `profiles/{slug}.php` to `profiles/[x] {slug}.php` (prefix the filename with `[x] `).

---

## Step 3 — Remove from `team.php`

In `team.php`, find and remove the entire `<!-- Item -->` … `<!-- End Item -->` block that contains `href="profiles/{slug}"`. Remove the block and any blank line immediately preceding it.

The block looks like this:
```html

        <!-- Item -->
         <div class="cbp-item ...">
           <a class="cbp-caption" href="profiles/{slug}">
             ...
           </a>
             <div class="py-3 blurb">
               ...
             </div>
         </div>
         <!-- End Item -->
```

---

## Step 4 — Remove from each service page

For each service page identified in Step 1, find and remove the entire `<!-- Item -->` … `<!-- End Item -->` block that contains `href="../profiles/{slug}"`. Remove the block and any blank line immediately preceding it.

The block looks like this:
```html

          <!-- Item -->
          <div class="cbp-item ...">
            <a class="cbp-caption" href="../profiles/{slug}">
              ...
            </a>
              <div class="py-3 blurb">
                ...
              </div>
          </div>
          <!-- End Item -->
```

---

## Step 5 — Remove from `sitemap.xml`

In `sitemap.xml`, find and remove the `<url>` block whose `<loc>` contains `/profiles/{slug}`:

```xml
  <url>
    <loc>https://nexushealthclinic.com/profiles/{slug}</loc>
    <lastmod>...</lastmod>
    <priority>0.64</priority>
  </url>
```

---

## Step 6 — Confirm

After completing all removals, report:
- The profile page archived (renamed with `[x] ` prefix)
- Which service pages were updated
- Confirmation that `team.php` and `sitemap.xml` were updated
- Remind the user to manually remove the practitioner's photos from S3 if no longer needed:
  - `{s3_base_url}/images/profile/med/{slug}.jpg`
  - `{s3_base_url}/images/profile/lrg/{slug}.jpg`
- Remind the user to remove `data/practitioners/{slug}.json` if it should be archived or deleted
