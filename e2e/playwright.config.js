// @ts-check
const { defineConfig, devices } = require('@playwright/test');

// Ganti target lewat env: BASE_URL=https://staging.contoh.com npx playwright test
const baseURL = process.env.BASE_URL || 'http://localhost:8000';

module.exports = defineConfig({
  testDir: './tests',
  timeout: 30_000,
  expect: { timeout: 7_000 },
  fullyParallel: true,
  retries: process.env.CI ? 1 : 0,
  workers: process.env.CI ? 2 : undefined,
  reporter: [['list'], ['html', { open: 'never' }]],
  use: {
    baseURL,
    locale: 'id-ID',
    timezoneId: 'Asia/Jakarta',
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
  },
  projects: [
    { name: 'chromium', use: { ...devices['Desktop Chrome'] } },
    { name: 'mobile', use: { ...devices['Pixel 7'] } },
  ],
  // Aplikasi harus sudah jalan sebelum tes (lihat README.md).
  // Kalau mau Playwright yang menyalakan, isi webServer di sini.
  metadata: { startPath: '/' },
});
