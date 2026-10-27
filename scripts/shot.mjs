import { BASE, saveTheme, signIn, sleep } from './lib.mjs';

// Screenshots one panel path, under a theme if given.
// Usage: node scripts/shot.mjs <path> <png> [<theme>]
const [PATH, PNG, THEME] = process.argv.slice(2);
if (THEME) saveTheme(THEME);
const { browser, page } = await signIn();
await page.goto(`${BASE}${PATH}`, { waitUntil: 'networkidle2' });
await sleep(1500);
await page.screenshot({ path: PNG });
await browser.close();
