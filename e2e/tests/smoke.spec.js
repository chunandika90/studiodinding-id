// @ts-check
const { test, expect } = require('@playwright/test');

const START_PATH = process.env.START_PATH || '/';

test.describe('Smoke', () => {
  test('halaman utama terbuka dan merespons normal', async ({ page }, testInfo) => {
    const pageErrors = [];
    const consoleErrors = [];
    page.on('pageerror', (e) => pageErrors.push(e.message));
    page.on('console', (m) => { if (m.type() === 'error') consoleErrors.push(m.text()); });

    const response = await page.goto(START_PATH);
    expect(response, 'tidak ada respons dari server').toBeTruthy();
    expect(response.status(), 'status HTTP harus di bawah 400').toBeLessThan(400);

    await page.waitForLoadState('networkidle').catch(() => {});
    await expect(page.locator('body')).toBeVisible();

    const shot = await page.screenshot({ fullPage: true });
    await testInfo.attach('halaman-utama', { body: shot, contentType: 'image/png' });

    if (consoleErrors.length) {
      await testInfo.attach('console-errors', {
        body: consoleErrors.join('\n'),
        contentType: 'text/plain',
      });
    }
    // Error JavaScript yang tidak tertangkap dianggap gagal. Error console hanya dilampirkan.
    expect(pageErrors, 'ada error JavaScript di halaman').toEqual([]);
  });

  test('tidak ada link internal yang rusak di halaman utama', async ({ page, request }) => {
    await page.goto(START_PATH);
    const origin = new URL(page.url()).origin;
    const hrefs = await page.$$eval('a[href]', (as) => as.map((a) => a.href));
    const internal = [...new Set(hrefs)]
      .filter((h) => h.startsWith(origin) && !/#|mailto:|tel:|javascript:/.test(h))
      .slice(0, 25);

    const broken = [];
    for (const url of internal) {
      const res = await request.get(url, { maxRedirects: 5 }).catch(() => null);
      if (!res || res.status() >= 400) broken.push(url + ' -> ' + (res ? res.status() : 'tidak ada respons'));
    }
    expect(broken, 'link internal yang rusak').toEqual([]);
  });
});
