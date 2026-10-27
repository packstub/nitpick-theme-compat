import { BASE, saveTheme, signIn, sleep } from './lib.mjs';

// Opens Filament's own Delete modal on an edit page under a theme, to tell how the theme stacks
// modals from how the Appearance modal is stacked.
// Usage: node scripts/probe-modal.mjs <theme> <png>
const [THEME, PNG] = process.argv.slice(2);
saveTheme(THEME);
const { browser, page } = await signIn();
await page.goto(`${BASE}/orders/1/edit`, { waitUntil: 'networkidle2' });
await sleep(1500);
const del = await page.evaluateHandle(() => [...document.querySelectorAll('.fi-header button, header button')].find((b) => /Delete/.test(b.textContent)));
await del.asElement().click();
await page.waitForSelector('.fi-modal-window', { visible: true });
await sleep(800);
console.log(await page.evaluate(() => ['.fi-sidebar', '.fi-modal-window-ctn', '.fi-modal-close-overlay', '.fi-topbar'].map((sel) => {
  const e = document.querySelector(sel);
  return e ? `${sel} z=${getComputedStyle(e).zIndex} ${getComputedStyle(e).position}` : `${sel} –`;
})));
if (PNG) await page.screenshot({ path: PNG });
await browser.close();
