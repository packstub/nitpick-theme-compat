import { BASE, openAppearance, saveTheme, signIn, sleep } from './lib.mjs';
// Opens the Appearance modal under a theme and reports how it stacks against the sidebar.
const [THEME, PNG] = process.argv.slice(2);
saveTheme(THEME);
const { browser, page } = await signIn();
await page.goto(`${BASE}`, { waitUntil: 'networkidle2' });
await sleep(1500);
const item = await openAppearance(page);
await item.click();
await page.waitForSelector(".fi-modal-window", { visible: true });
await sleep(800);
console.log(await page.evaluate(() => ['.fi-sidebar', '.fi-modal:has(.pst-theme-picker) > .fi-modal-window-ctn', '.fi-modal:has(.pst-theme-picker) > .fi-modal-close-overlay'].map((sel) => {
  const e = document.querySelector(sel);
  return e ? `${sel} z=${getComputedStyle(e).zIndex}` : `${sel} –`;
})));
if (PNG) await page.screenshot({ path: PNG });
await browser.close();
