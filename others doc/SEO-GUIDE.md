# Pola SEO (meta tags dinamis + sitemap) — reusable buat project lain

Dokumen ini ngejelasin pola SEO yang dipakai di project Studio Dinding, ditulis
biar bisa langsung dipakai jadi acuan pas mau nerapin hal yang sama di project
lain (misal Svashta Home / mirjov). Copy file ini ke project lain, terus minta
Claude nerapin pola di bawah ini ke struktur project itu.

## Masalah yang mau diselesaikan

Search engine (Google) dan bot preview link (WhatsApp, Instagram, Facebook,
Twitter/X) **tidak menjalankan JavaScript** waktu mereka scan sebuah URL.
Mereka cuma baca HTML mentah yang dikirim server. Jadi:

- Kalau `<title>` dan `<meta name="description">` isinya generic/sama di
  semua halaman → Google gak bisa bedain halaman project A vs project B vs
  artikel blog C, dan preview link share di WhatsApp juga keliatan sama semua.
- Kalau konten halaman (judul, gambar, deskripsi) di-render lewat JavaScript
  setelah halaman dimuat (client-side rendering / CSR) → bot yang gak jalanin
  JS cuma bakal liat halaman kosong / placeholder `{{ }}`.

**Cek dulu sebelum nerapin pola ini di project lain**: apakah halaman detail
di project itu (halaman produk/artikel/proyek per-item) sudah di-render
server-side PHP beneran (isi HTML-nya sudah lengkap pas `view-source`), atau
masih CSR kayak Studio Dinding (`.dc.html` + `support.js`)? Kalau sudah
server-rendered PHP, kamu **gak perlu bikin file wrapper baru** — tinggal
suntik blok meta tag di `<head>` file PHP yang udah ada. Kalau masih CSR,
ikuti pola lengkap di bawah (bikin file `.php` baru yang wrap konten CSR-nya).

## Bagian 1 — Meta tags statis (halaman yang kontennya jarang berubah)

Untuk halaman kayak Homepage, About, List Blog/Artikel — taruh langsung di
`<head>`, hardcode di HTML (gak perlu ambil dari database):

```html
<title>Nama Bisnis — Deskripsi Singkat/Layanan/Lokasi</title>
<meta name="description" content="1-2 kalimat, maks ~160 karakter, jelasin bisnisnya apa + lokasi + value proposition.">
<link rel="canonical" href="https://domain.com/nama-file-halaman-ini.html">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Nama Bisnis">
<meta property="og:title" content="Sama kayak <title> atau versi lebih pendek">
<meta property="og:description" content="Sama kayak meta description">
<meta property="og:image" content="https://domain.com/assets/img/logo-atau-foto-representatif.png">
<meta property="og:url" content="https://domain.com/nama-file-halaman-ini.html">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" type="image/png" href="assets/img/favicon.png">
```

Ganti isinya per halaman (title/description/canonical/og:url beda-beda tiap
file), tapi strukturnya sama persis.

## Bagian 2 — Meta tags dinamis (halaman detail per-item: produk/proyek/artikel)

Ini bagian yang butuh perubahan arsitektur kalau halamannya masih CSR.

### Kalau masih CSR (pola Studio Dinding — `.dc.html` + JS runtime)

1. **Bikin file PHP baru** menggantikan file `.dc.html` lama (misal
   `project.dc.html` → `project.php`, tetap pakai query string yang sama,
   `?slug=...`).
2. Di baris paling atas file PHP itu, **sebelum HTML apapun**: query database
   ambil item berdasarkan slug, hitung title/description/image/url:

   ```php
   <?php
   require __DIR__ . '/../shared/config.php';
   $slug = $_GET['slug'] ?? '';
   $stmt = $pdo->prepare("SELECT * FROM items WHERE slug = ? LIMIT 1");
   $stmt->execute([$slug]);
   $item = $stmt->fetch();

   $pageTitle = ($item && !empty($item['seo_title']))
       ? $item['seo_title']
       : ($item ? $item['name'] . ' — Nama Bisnis' : 'Nama Bisnis');
   $pageDescription = ($item && !empty($item['seo_description']))
       ? $item['seo_description']
       : ($item ? mb_strimwidth(strip_tags($item['short_desc'] ?? ''), 0, 160, '...') : 'Deskripsi default.');
   $pageImage = $item && !empty($item['cover_img']) ? $item['cover_img'] : (SITE_URL . '/assets/img/logo.png');
   $pageUrl = SITE_URL . '/item.php?slug=' . urlencode($slug);
   ?>
   <!DOCTYPE html>
   <html>
   <head>
   <meta charset="utf-8">
   <title><?= htmlspecialchars($pageTitle) ?></title>
   <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
   <link rel="canonical" href="<?= htmlspecialchars($pageUrl) ?>">
   <meta property="og:type" content="article">
   <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
   <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
   <meta property="og:image" content="<?= htmlspecialchars($pageImage) ?>">
   <meta property="og:url" content="<?= htmlspecialchars($pageUrl) ?>">
   <meta name="twitter:card" content="summary_large_image">
   <link rel="icon" type="image/png" href="assets/img/favicon.png">
   <script src="./support.js"></script>
   <script src="./cms-client.js"></script>
   </head>
   <body>
   <!-- SISA HTML/component CSR-nya SAMA PERSIS kayak file .dc.html lama, copy-paste apa adanya -->
   ```

3. **File `.dc.html` lama jangan dihapus** — ganti isinya jadi redirect stub
   kecil, biar link lama yang udah kesebar gak 404:

   ```html
   <!DOCTYPE html>
   <html><head><script>location.replace('item.php' + location.search);</script></head>
   <body></body></html>
   ```

4. **Update semua link internal** yang nunjuk ke `item.dc.html?slug=` jadi
   `item.php?slug=`.

### Kalau sudah server-rendered PHP asli

Tinggal suntik blok `<title>`/meta tag yang sama kayak di atas (bagian PHP di
poin 2) ke `<head>` file yang udah ada, pakai variabel yang udah tersedia di
situ (biasanya udah ada `$item`/`$product`/`$post` dari query yang sudah
jalan). Gak perlu bikin file baru atau redirect stub.

## Bagian 3 — Field SEO opsional di CMS

Supaya admin bisa override title/description per-item tanpa perlu edit kode:

1. **Tambah 2 kolom di tabel item** (produk/proyek/artikel):
   ```sql
   ALTER TABLE items ADD COLUMN seo_title VARCHAR(255) NULL AFTER <kolom_terkait>;
   ALTER TABLE items ADD COLUMN seo_description VARCHAR(300) NULL AFTER seo_title;
   ```
2. **Endpoint admin API** (create/update item): terima `seoTitle`/
   `seoDescription` dari body request, simpan pakai pola "kosong → NULL":
   ```php
   'seo_title' => trim((string) ($b['seoTitle'] ?? '')) ?: null,
   'seo_description' => trim((string) ($b['seoDescription'] ?? '')) ?: null,
   ```
3. **Form/modal admin**: tambah 2 input di bawah field konten utama,
   dikasih section header "SEO (OPSIONAL)" + penjelasan singkat + contoh
   penulisan, biar admin ngerti gunanya:
   ```html
   <div style="border-top:1px solid #eee; padding-top:20px;">
     <div>SEO (OPSIONAL)</div>
     <div style="font-size:12px; color:#888;">Kosongin biar otomatis dari nama + deskripsi di atas. Isi kalau mau kontrol sendiri buat Google & preview link share.</div>

     <label>SEO TITLE</label>
     <div style="font-size:11px; color:#888;">Judul di tab browser & hasil pencarian Google. Idealnya 50–60 karakter — sebutkan nama item + kata kunci relevan, tutup dengan nama brand.<br>Contoh: "Nama Produk X — Nama Bisnis"</div>
     <input placeholder="Nama Item — Nama Bisnis">

     <label>SEO DESCRIPTION</label>
     <div style="font-size:11px; color:#888;">Ringkasan 1–2 kalimat di bawah judul pada hasil pencarian & preview link. Maks ~160 karakter — bikin orang penasaran klik.<br>Contoh: "Deskripsi singkat yang menonjolkan keunggulan/lokasi/keunikan item ini."</div>
     <textarea placeholder="Muncul di hasil pencarian Google & preview link (maks ~160 karakter)"></textarea>
   </div>
   ```
4. Di halaman detail (Bagian 2), pakai `$item['seo_title'] ?: $fallback` dan
   `$item['seo_description'] ?: $fallback`.

## Bagian 4 — Sitemap.xml + robots.txt

### `sitemap.php` (server root, dideploy sejajar file publik lain)

```php
<?php
require __DIR__ . '/../shared/config.php'; // sesuaikan path ke config
header('Content-Type: application/xml; charset=utf-8');

$urls = [
    ['loc' => SITE_URL . '/index.html', 'priority' => '1.0'],
    ['loc' => SITE_URL . '/about.html', 'priority' => '0.8'],
    // ...halaman statis lain
];

$items = $pdo->query("SELECT slug FROM items WHERE published = 1")->fetchAll();
foreach ($items as $it) {
    $urls[] = ['loc' => SITE_URL . '/item.php?slug=' . urlencode($it['slug']), 'priority' => '0.9'];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n    <loc>" . htmlspecialchars($u['loc']) . "</loc>\n    <priority>{$u['priority']}</priority>\n  </url>\n";
}
echo '</urlset>' . "\n";
```

### `.htaccess` — biar URL-nya `/sitemap.xml`, bukan `/sitemap.php`

```apache
RewriteEngine On
RewriteRule ^sitemap\.xml$ sitemap.php [L]
```

### `robots.txt`

```
User-agent: *
Allow: /

Sitemap: https://domain-project-ini.com/sitemap.xml
```

## Checklist penerapan di project baru

- [ ] Cek dulu: halaman detail per-item CSR atau udah server-rendered PHP?
- [ ] Meta tags statis di semua halaman non-detail (home, about, list)
- [ ] Meta tags dinamis di halaman detail (bikin `.php` wrapper kalau CSR,
      atau suntik langsung kalau udah PHP)
- [ ] Redirect stub di file lama kalau bikin file baru (jangan hapus file lama)
- [ ] Update semua link internal ke URL baru
- [ ] Kolom `seo_title`/`seo_description` di tabel yang relevan
- [ ] Endpoint admin API simpan 2 kolom itu
- [ ] Field SEO opsional + guidance/contoh di form admin
- [ ] `sitemap.php` + `.htaccess` rewrite + `robots.txt`
- [ ] Test: `view-source` di halaman detail, pastikan `<title>`/meta udah
      keisi bener (bukan `{{ }}` kosong), test share link ke WhatsApp buat
      liat preview-nya, buka `/sitemap.xml` pastikan XML valid.
