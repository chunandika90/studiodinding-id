// @ts-check
const { test, expect } = require('@playwright/test');
const AxeBuilder = require('@axe-core/playwright').default;

const START_PATH = process.env.START_PATH || '/';

test.describe('Aksesibilitas', () => {
  test('halaman utama lolos pemeriksaan axe (WCAG 2.1 A dan AA)', async ({ page }, testInfo) => {
    await page.goto(START_PATH);
    await page.waitForLoadState('networkidle').catch(() => {});

    const hasil = await new AxeBuilder({ page })
      .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa'])
      .analyze();

    await testInfo.attach('axe-violations', {
      body: JSON.stringify(hasil.violations, null, 2),
      contentType: 'application/json',
    });

    const ringkas = hasil.violations.map(
      (v) => `[${v.impact}] ${v.id}: ${v.help} (${v.nodes.length} elemen)`
    );
    // Dampak critical dan serious menggagalkan tes. Sisanya cukup dilaporkan.
    const berat = hasil.violations.filter((v) => ['critical', 'serious'].includes(v.impact || ''));
    if (ringkas.length) {
      await testInfo.attach('ringkasan-temuan', { body: ringkas.join('\n'), contentType: 'text/plain' });
    }
    expect(berat.map((v) => v.id), 'pelanggaran aksesibilitas berat').toEqual([]);
  });
});
