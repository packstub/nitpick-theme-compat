import { BASE, saveTheme, signIn, sleep } from './lib.mjs';

// Under a theme, with dark mode stored, reports whether the page is dark and offers Filament's
// light/dark switcher: a theme that turns dark mode off should stay light.
// Usage: node scripts/probe-dark.mjs <theme>
const [THEME] = process.argv.slice(2);
saveTheme(THEME);
const { browser, page } = await signIn();
await page.goto(BASE, { waitUntil: 'networkidle2' });
await page.evaluate(() => { localStorage.setItem('filament:theme:mode', 'dark'); localStorage.setItem('theme', 'dark'); });
await page.goto(BASE, { waitUntil: 'networkidle2' });
await sleep(1000);
console.log(THEME, await page.evaluate(() => ({ dark: document.documentElement.classList.contains('dark'), switcher: document.querySelectorAll('.fi-theme-switcher').length })));
await browser.close();
