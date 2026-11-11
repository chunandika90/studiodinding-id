# Tes End-to-End (Playwright)

Folder ini berdiri sendiri dan tidak mengubah dependency aplikasi utama. Isinya tes otomatis berbasis browser untuk QA dan audit: halaman terbuka, tidak ada error JavaScript, link internal tidak rusak, dan pemeriksaan aksesibilitas (axe-core, WCAG 2.1 A/AA).

## Persiapan sekali

```bash
cd e2e
npm install
npx playwright install chromium
```

Di Linux server tambahkan `--with-deps` pada perintah terakhir agar library sistem ikut terpasang.

## Menjalankan aplikasi yang dites

Dari folder `website` di repo ini:

```bash
php -S localhost:8000
```

Pastikan aplikasi bisa dibuka di http://localhost:8000 sebelum tes dijalankan. Pakai database atau data dummy, jangan data produksi.

## Menjalankan tes

```bash
npm test
```

Varian lain:

| Perintah | Fungsi |
|---|---|
| `npm run test:ui` | Mode interaktif, lihat tiap langkah |
| `npm run test:headed` | Browser terlihat, bukan headless |
| `npm run test:mobile` | Hanya profil layar HP |
| `npm run report` | Buka laporan HTML hasil tes terakhir |

Target lain, misalnya staging:

```bash
BASE_URL=https://staging.contoh.com npm test
```

Halaman awal yang dites bisa diganti lewat `START_PATH`, bawaan `/`.

Tes aksesibilitas kemungkinan gagal pada run pertama. Itu temuan audit, bukan kerusakan tes. Daftar lengkapnya ada di lampiran `axe-violations` dan `ringkasan-temuan` pada laporan HTML.

## Struktur

```
e2e/
  playwright.config.js   konfigurasi: baseURL, browser, laporan
  tests/
    smoke.spec.js        halaman terbuka, tanpa error JS, link internal utuh
    a11y.spec.js         audit aksesibilitas dengan axe-core
```

## Menambah tes modul

Buat file baru di `tests/`, misalnya `tests/login.spec.js`. Contoh pola:

```javascript
const { test, expect } = require('@playwright/test');

test('login berhasil dengan akun valid', async ({ page }) => {
  await page.goto('/login');
  await page.fill('[name="email"]', process.env.TEST_EMAIL);
  await page.fill('[name="password"]', process.env.TEST_PASSWORD);
  await page.click('button[type="submit"]');
  await expect(page).toHaveURL(/dashboard/);
});
```

Kredensial tes selalu lewat environment variable, jangan ditulis di file.

Kalau pakai Claude Code, prompt yang cocok:

> Baca modul [nama modul] di repo ini, lalu tulis test Playwright di folder e2e/tests yang mencakup alur sukses, validasi input, dan kasus error. Jalankan dan perbaiki yang gagal kalau penyebabnya di test. Laporkan kalau penyebabnya bug di aplikasi.

## GitHub Actions

Workflow `.github/workflows/playwright.yml` dijalankan manual dari tab Actions dengan input `base_url`. Laporan HTML tersimpan sebagai artifact. Workflow ini tidak jalan otomatis saat push supaya tidak mengganggu CI yang sudah ada.
