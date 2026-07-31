# Deploying Studio Dinding: site + CMS on two subdomains

Public site and admin panel live on separate subdomains, sharing one database
and one `shared/` folder that sits outside both document roots.

- **`new.studiodinding.id`** — the public site. Deploy the contents of `website/`.
- **`cms.studiodinding.id`** — the admin panel. Deploy the contents of `cms/`.
- **`shared/`** — PHP config/helpers, never web-accessible. Deploy as a sibling
  folder to both of the above (NOT inside either document root).

Both `website/index.html` and `cms/index.html` are already named correctly —
no renaming needed on upload, just copy the files as-is.

## First-time setup (skip if already done)

1. **Create the `cms.studiodinding.id` subdomain** — cPanel → Domains → Create
   A New Domain. Note the exact document root folder name cPanel gives it.
2. **Create the database** — cPanel → MySQL Databases → create a database +
   user with full privileges. Note host/db name/user/password.
3. **Import the base schema** — phpMyAdmin → select your database → Import →
   `shared/db/schema.sql` → Go. Creates all tables + seeds them with the
   site's original content.
4. **Configure `shared/config.local.php`** — copy from
   `shared/config.local.example.php`, fill in DB credentials + exact
   subdomain folder names (`site_dir_base`, `cms_dir_base`) from File Manager.
   Never zip/screenshot/share this file — real credentials live in it.
5. **Upload**: `shared/` as a sibling of both subdomain folders (not inside
   either doc root) · `website/` contents into `new.studiodinding.id` · `cms/`
   contents into `cms.studiodinding.id`.
6. **Create the admin login** — visit `https://cms.studiodinding.id/api/setup.php`
   once (creates `vodbpxic_admin` / `<lihat config.local.php>`), then **delete
   `cms/api/setup.php` from the server** — it's a standing risk if left in
   place (unauthenticated by design, refuses to re-run once an admin exists,
   but delete it anyway).

## Incremental SQL — run once each, in any order (do NOT re-run schema.sql, it DROPs every table)

Paste each into phpMyAdmin's SQL tab:

- `shared/db/add-about-hero.sql` — About page hero headline/photo/intro table
- `shared/db/add-contact-info.sql` — WhatsApp/email/address/Instagram table
- `shared/db/add-ticket-status.sql` — adds `status` column to contact_submissions
- `shared/db/add-categories-blog-collab.sql` — project categories, blog_posts,
  collaborators tables, plus `attachment` column on contact_submissions
- `shared/db/add-seo-fields.sql` — optional `seo_title`/`seo_description`
  override columns on `projects` and `blog_posts`

If any of these were already run in an earlier session, re-running is safe —
they use `CREATE TABLE IF NOT EXISTS` / `INSERT IGNORE`. The one exception:
the `ALTER TABLE ... ADD COLUMN` lines will error with "Duplicate column
name" if already applied — that's expected, just skip that line and continue.

## What's fully wired vs. not yet

| Area | Status |
|---|---|
| Homepage hero slides | ✅ full CRUD from the dashboard |
| Projects/Portfolio (incl. gallery photos, categories) | ✅ full CRUD |
| Project categories | ✅ manage from "Kelola Kategori" inside the Projects section |
| About page team members | ✅ full CRUD |
| About page hero headline/photo/intro quote | ✅ wired, own save button |
| Contact details (WhatsApp/email/address/Instagram) | ✅ wired, own save button |
| Contact form → inbox | ✅ real form + file attachment, ticket status (new/in progress/closed), date-range filter |
| Blog / Journal | ✅ full CRUD in dashboard, public list + single-post pages |
| Collaborator logos on homepage | ✅ full CRUD in dashboard |
| Additional admin accounts | ✅ "Admin Users" section — add/remove logins |
| One-time migration from live `.dc.html` files → DB | ✅ "Setup Sekali Jalan" card on Dashboard — safe to re-run any time |
| Image thumbnail generation (separate detail/grid sizes) | ❌ not built — uploads are auto-downscaled to max 2000px on the longest side, but only one size, no dedicated thumbnail variant |
| Favicon / browser tab title | ✅ both subdomains show the Studio Dinding logo + "Studio Dinding" / "CMS Studio Dinding" |
| SEO: meta description + Open Graph tags | ✅ static on homepage/About/Journal list, dynamic per-project and per-article (server-rendered before any JS runs) — editable via optional SEO Title/Description fields in the Project and Blog Article modals |
| SEO: sitemap.xml + robots.txt | ✅ `sitemap.xml` auto-lists every published project/article from the DB |
| Report Tiket (separate sidebar section) | ✅ date-range filtered ticket report with status summary, independent from the Contact & Inbox list |

## Known architecture notes (read before debugging weird issues)

- **Image paths are always absolute URLs** (`https://new.studiodinding.id/...`),
  never root-relative — required because the CMS previews images from a
  different subdomain than where they're actually served. If you ever add a
  new image field, follow this convention (`to_absolute_site_url()` in
  `shared/migrate.php`, `UPLOAD_URL_BASE` in `shared/upload.php`).
- **Project pages are one dynamic file** (`website/project.php?slug=...`,
  articles are `website/blog-post.php?slug=...`), not one static file per
  item — renaming a project/article in the CMS never breaks its link, since
  the slug (not the name) drives the URL. The old `project.dc.html` /
  `blog-post.dc.html` filenames still exist as tiny JS redirect stubs in case
  an old link is out there — don't delete them.
- **Why `.php` instead of `.dc.html` for those two**: search engines and
  link-preview bots (WhatsApp, Instagram, etc.) don't run JavaScript, so they
  only ever see whatever's in the raw HTML `<head>`. PHP fills in the real
  per-item `<title>`/meta description/Open Graph tags server-side, before any
  JS runs — the actual page content below is still 100% the same
  client-side-rendered `.dc.html` component it always was. Any future
  per-item page (another content type with its own SEO-worthy detail page)
  should follow this same pattern rather than staying a plain `.dc.html`.
- **`index.html` in both `website/` and `cms/` is managed by you directly** —
  future updates will tell you which files changed, not touch the naming.

## Verifying it works

1. Open `https://new.studiodinding.id/` — slides, projects, team, About hero,
   contact details, blog list, and collaborator logos should all render.
2. Log into `https://cms.studiodinding.id/` (`vodbpxic_admin` / `<lihat config.local.php>`),
   confirm Dashboard stats (Total Projects/Team Members) are non-zero.
3. Edit a project name, save, reload that project's page on the live site —
   the new name should show and the URL should still work (slug-based).
4. Submit the public contact form with an attachment, confirm it shows up in
   the dashboard's Contact & Inbox with the file downloadable and a status
   badge, and that changing its status sticks.
5. Upload a new photo anywhere in the dashboard, confirm it renders correctly
   both in the dashboard preview AND on the public site (cross-domain image
   check).
6. Check the browser DevTools Console + Network tab on both domains for any
   red/failed requests.
