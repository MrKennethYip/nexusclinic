Add a new practitioner to the website using data from `data/practitioners/$ARGUMENTS.json`.

Read that JSON file first, then perform every step below in order.

---

## Step 1 — Parse the JSON

Extract all fields. Derive these additional values:
- `full_name` = `{first_name} {last_name}`
- `slug` = `image_key` (used for file paths and URLs, e.g. `ada_chindah`)
- `img_sml` = `{s3_base_url}/images/profile/sml/{image_key}.jpg`
- `img_med` = `{s3_base_url}/images/profile/med/{image_key}.jpg`
- `img_lrg` = `{s3_base_url}/images/profile/lrg/{image_key}.jpg`
- `css_classes` = space-joined CSS filter classes for each service (see mapping below)
- `profile_url_from_root` = `profiles/{slug}`
- `profile_url_from_service` = `../profiles/{slug}`

**Service → CSS class + service page + icon + label mapping:**

| `services` value | CSS class    | Service page                  | Icon filename              | Sidebar label                    |
|------------------|--------------|-------------------------------|----------------------------|----------------------------------|
| `massage`        | `rmt`        | `service/massage.php`         | `massage-icon.jpg`         | `Registered Massage Therapy`     |
| `chiropractic`   | `chiro`      | `service/chiropractic.php`    | `chiro-icon.jpg`           | `Chiropractic Services`          |
| `physiotherapy`  | `physio`     | `service/physiotherapy.php`   | `physio-icon.jpg`          | `Physiotherapy`                  |
| `acupuncture`    | `acupuncture`| `service/acupuncture.php`     | `acupuncture-icon.jpg`     | `Acupuncture`                    |
| `sports-massage` | `rmt`        | `service/sports-massage.php`  | `massage-icon.jpg`         | `Sports Massage Therapy`         |
| `telehealth`     | `telehealth` | `service/telehealth.php`      | `telehealth-icon.jpg`      | `Virtual Telehealth`             |

`css_classes` is all matched CSS classes joined with a space, e.g. `rmt chiro` for `["massage","chiropractic"]`.

Icons are served from `https://s3.ca-central-1.amazonaws.com/nexusmassageclinic/icons/{icon_filename}`.

---

## Step 2 — Create `profiles/{slug}.php`

Create the file using this exact template, substituting all `{{ }}` placeholders:

```php
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Title -->
  <title>{{ seo.title }}</title>

  <meta name="Description" content="{{ seo.description }}">
  <meta name="keywords" content="{{ seo.keywords }}">

  <!-- Common Header -->
  <?php include('../includes/common-header.php') ?>
  <!-- End Common Header -->

  <style>
    @media (min-width: 768px) {
      .custom-top-spacing {
        padding-top: 8.125rem;
      }
    }
    @media (max-width: 1024px) {
      .custom-top-spacing {
        padding-top: 15rem;
      }
    }
  </style>

</head>

<body>

  <!-- ========== HEADER ========== -->
  <?php include('../includes/nav.php') ?>
  <!-- ========== END HEADER ========== -->

  <!-- ========== MAIN ========== -->
  <main id="content" role="main">

    <!-- Author Section -->
    <div class="position-relative text-center custom-top-spacing u-gradient-overlay-half-info-v1 u-bg-img-hero">
      <div class="row justify-content-md-center">

        <div class="col-md-8 col-lg-7 col-xl-5">

          <div class="animated bounceIn">
          <!-- Info -->
          <div class="mb-4">
            <h1 class="display-4 font-size-48--md-down text-white font-weight-bold">{{ display_name | uppercase }}</h1>
          </div>

          <ul class="list-inline">
            <li class="list-inline-item">
              <a class="u-text-light" href="mailto:{{ email }}">
                <span class="far fa-envelope mr-2"></span>
                {{ email }}
              </a>
            </li>
          </ul>
          <!-- End Info -->

          <!-- Avatar -->
          <img class="img-fluid u-xl-avatar u-xl-avatar--bordered u-avatar-v1 rounded-circle mx-auto" src="{{ img_med }}" alt="{{ display_name }} - Profile">
          <!-- End Avatar -->
          </div>
        </div>

        <!-- SVG Background -->
        <figure class="position-absolute-bottom-0 z-index-minus-1">
          <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            width="100%" height="200px" viewBox="20 -20 300 100" style="margin-bottom: -8px;" xml:space="preserve">
            <path class="u-fill-white" opacity="0.4" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729
              c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" />
            <path class="u-fill-white" opacity="0.4" d="M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729
              c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z" />
            <path class="u-fill-white" opacity="0" d="M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716
              H42.401L43.415,98.342z" />
            <path class="u-fill-white" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428
              c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" />
          </svg>
        </figure>
        <!-- End SVG Background Section -->
      </div>

    </div>
    <!-- End Author Section -->



    <!-- Content Section -->
    <div class="u-bg-light-blue-50">
      <div class="container u-space-2">
        <div class="row">
          <div class="col-lg-3 mb-7 mb-lg-0">
            <!-- Profile Card -->
            <div class="card p-1 mb-4" style="min-width: 225px;">
              <div class="card-body text-center">
                <div class="mb-2">

                  <a class="btn btn-block btn-danger transition-3d-hover" href="https://nexusclinic.clinicsense.com/book/" target="_blank" onclick="handleClick('sidebarBtn');">Book Practitioner<span class="fa fa-angle-right ml-2"></span></a>
                  <br>

                  <a class="btn btn-block u-btn-primary--air transition-3d-hover" href="mailto:{{ email }}">
                    <span class="far fa-envelope mr-2"></span>
                    Send a Message
                  </a>
                </div>
              </div>
            </div>
            <!-- End Profile Card -->

            <!-- Contacts  -->
            <div class="card p-1 mb-4" style="min-width: 225px;">
              <div class="card-body">
                <h4 class="h6 mb-0 font-weight-bold">SERVICES:</h4>

                <hr>

                {{ SERVICES_SIDEBAR }}

              </div>
            </div>
            <!-- End Contacts  -->

            <!-- Hours  -->
            <div class="card p-1 mb-4" style="min-width: 225px;">
              <div class="card-body">
                <h4 class="h6 mb-0 font-weight-bold">HOURS OF AVAILABILITY:</h4>

                <hr>

                <!-- User -->
                <span class="d-flex align-items-start">

                  <div class="ml-2">
                    {{ HOURS_ROWS }}
                  </div>
                </span>
                <!-- End User -->
                  
              </div>
            </div>
            <!-- End Hours  -->

          </div>

          <div class="col-lg-9">
              <!-- Title -->
              <div class="mb-3">
                <h5 class="text-primary font-weight-bold">Tell us about yourself?</h5>
              </div>
              <!-- End Title -->

              <!-- Text -->
              {{ bio.about | paragraphs }}
              <!-- End Text -->

              <!-- Title -->
              <div class="mb-3">
                <h5 class="text-primary font-weight-bold">Which cases do you enjoy treating? Why?</h5>
              </div>
              <!-- End Title -->

              <!-- Text -->
              {{ bio.cases | paragraphs }}
              <!-- End Text -->

              <!-- Title -->
              <div class="mb-3">
                <h5 class="text-primary font-weight-bold">What are the top 3 treatments that you specialize in?</h5>
              </div>
              <!-- End Title -->

              <!-- Text -->
              {{ bio.specializations | paragraphs }}
              <!-- End Text -->

              <!-- Title -->
              <div class="mb-3">
                <h5 class="text-primary font-weight-bold">What are you proud of in your practice?</h5>
              </div>
              <!-- End Title -->

              <!-- Text -->
              {{ bio.proud_of | paragraphs }}
              <!-- End Text -->

              <!-- Title -->
              <div class="mb-3">
                <h5 class="text-primary font-weight-bold">Why should patients choose you as their provider instead of others in the area?</h5>
              </div>
              <!-- End Title -->

              <!-- Text -->
              {{ bio.why_choose | paragraphs }}
              <!-- End Text -->

          </div>

        </div>

      </div>
    </div>
    <!-- End Content Section -->

    <!-- Social Section -->
    <div class="u-bg-light-blue-50 u-space-2-bottom">
      <div class="container">
        <!-- Review Widget -->
        
        <div class="embedsocial-reviews" data-ref="0c8cb12f4327698a9a08fed0accae436258c57d2"></div> <script> (function(d, s, id){ var js; if (d.getElementById(id)) {return;} js = d.createElement(s); js.id = id; js.src = "https://embedsocial.com/embedscript/ri.js"; d.getElementsByTagName("head")[0].appendChild(js); }(document, "script", "EmbedSocialReviewsScript")); </script>

        <!-- End Review Widget -->
      </div>
    </div>

  </main>
  <!-- ========== END MAIN ========== -->

  <!-- ========== FOOTER ========== -->
  <?php include('../includes/footer.php')?>
  <!-- ========== END FOOTER ========== -->

  <!-- Common Footer -->
  <?php include('../includes/common-footer.php') ?>
  <!-- End Common Footer -->

</body>
</html>
```

### Placeholder expansion rules

**`{{ display_name | uppercase }}`** — the display_name value in ALL CAPS.

**Multi-paragraph bio fields** — any `bio.*` field may contain `\n\n` to delimit paragraphs. When rendering, split the value on `\n\n` and wrap each chunk in its own `<p>` tag instead of a single `<p>`. For example, a field with two paragraphs becomes:
```html
              <p>First paragraph text.</p>
              <p>Second paragraph text.</p>
```

**`{{ SERVICES_SIDEBAR }}`** — one block per service, in the order listed in `services`:
```html
                <!-- User -->
                <div class="card-header border-0 py-3 d-flex align-items-center">
                  <img src="https://s3.ca-central-1.amazonaws.com/nexusmassageclinic/icons/{icon_filename}" class="rounded-circle u-avatar align-self-start mr-3">
                  <div>
                    <a href="../service/{service_slug}">
                      <span class="d-block text-dark small font-weight-bold">{sidebar_label}</span>
                    </a>
                  </div>
                </div>
                <!-- End User -->
```
where `{service_slug}` is the value from the `services` array (e.g. `massage`, `chiropractic`).

**`{{ HOURS_ROWS }}`** — one `<p>` per entry in `hours`:
```html
                    <p class="text-dark"><strong>{day}:</strong><span class="ml-3"></span>{time}</p>
```

---

## Step 3 — Update `team.php`

Insert the following block immediately **before** this comment anchor in `team.php`:
```
        <!-- Item
          <div class="cbp-item">
            <a class="cbp-caption" href="careers">
```

Block to insert (note: team.php uses `data-srcset` and `responsively-lazy`, unlike service pages):
```html

        <!-- Item -->
         <div class="cbp-item {{ css_classes }}">
           <a class="cbp-caption" href="profiles/{{ slug }}">
             <img class="rounded p-2 responsively-lazy" style="border: #898A8D 2px solid;" src="{{ img_med }}" data-srcset="{{ img_med }} 500w, {{ img_med }} 1000w, {{ img_lrg }} 1500w" sizes="(min-width: 993px) 1500px,(min-width: 768px) and (max-width: 992px) 1000px, (max-width: 767px) 500px" alt="{{ display_name }} - {{ title }}">
           </a>
             <div class="py-3 blurb">
               <a href="profiles/{{ slug }}">
                 <h4 class="h6 text-dark mb-0">{{ display_name }}</h4>
               </a>
               <p class="small mb-0">
                 {{ title }}<br><br>
               </p>
             </div>
         </div>
         <!-- End Item -->
```

---

## Step 4 — Update each service page

For each service in the `services` array, insert into the corresponding service page (e.g. `service/massage.php`).

Insert the following block immediately **before** this closing anchor (which ends the practitioner grid in every service page):
```
        </div>
        <!-- End Content -->
      </div>       
    </div>
    <!-- End Portfolio 1 Section -->
```

Block to insert (note: service pages use `srcset`, not `data-srcset`):
```html

          <!-- Item -->
          <div class="cbp-item {{ css_classes }}">
            <a class="cbp-caption" href="../profiles/{{ slug }}">
              <img class="rounded p-2" style="border: #898A8D 2px solid;" src="{{ img_med }}" srcset="{{ img_med }} 500w, {{ img_med }} 1000w, {{ img_lrg }} 1500w" sizes="(min-width: 993px) 1500px,(min-width: 768px) and (max-width: 992px) 1000px, (max-width: 767px) 500px" alt="{{ display_name }} - {{ title }}">
            </a>
              <div class="py-3 blurb">
                <a href="../profiles/{{ slug }}">
                  <h4 class="h6 text-dark mb-0">{{ display_name }}</h4>
                </a>
                <p class="small mb-0">
                   {{ bio.why_choose }} <br><br><br><br><br><br>
                </p>
              </div>
          </div>
          <!-- End Item -->
```

---

## Step 5 — Update `sitemap.xml`

Insert the following block immediately **before** `</urlset>` at the end of `sitemap.xml`. Use today's date in ISO 8601 format for `lastmod` (e.g. `2026-05-09T00:00:00+00:00`):

```xml
  <url>
    <loc>https://nexushealthclinic.com/profiles/{{ slug }}</loc>
    <lastmod>{{ TODAY_ISO8601 }}</lastmod>
    <priority>0.64</priority>
  </url>
```

---

## Step 6 — Confirm

After completing all edits, report:
- The profile page path created
- Which service pages were updated
- Confirmation that `team.php` and `sitemap.xml` were updated
- Remind the user to upload the practitioner's photos to S3 at:
  - `{{ img_sml }}`
  - `{{ img_med }}`
  - `{{ img_lrg }}`
