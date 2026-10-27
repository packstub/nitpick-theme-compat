import puppeteer from 'puppeteer-core';
import { execFileSync } from 'node:child_process';

// Shared by the lab's scripts: where the panel is, a signed-in browser, the user menu, the saved theme.
export const ROOT = new URL('..', import.meta.url).pathname;
export const BASE = process.env.LAB_URL ?? 'https://nitpick-theme-compat.test/admin';
export const CHROME = process.env.CHROME_PATH ?? '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
export const W = 1280;
export const H = 800;
export const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

// The signed-in user's saved theme for the admin panel, straight in the database.
export const saveTheme = (id) => execFileSync('sqlite3', [`${ROOT}database/database.sqlite`,
  `DELETE FROM theme_preferences; INSERT INTO theme_preferences (user_type, user_id, panel_id, theme_id) VALUES ('App\\Models\\User', 1, 'admin', '${id}');`]);

// A headless Chrome signed in as the seeded user. Chrome's password prompt blocks input on the
// tab that signed in, so the returned page is a fresh tab.
export const signIn = async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: true, acceptInsecureCerts: true, defaultViewport: { width: W, height: H, deviceScaleFactor: 1 } });
  const login = await browser.newPage();
  await login.goto(`${BASE}/login`, { waitUntil: 'networkidle2' });
  await login.type('input[type=email]', 'jane@lab.test');
  await login.type('input[type=password]', 'password');
  await Promise.all([login.waitForNavigation({ waitUntil: 'networkidle2' }), login.click('button[type=submit]')]);
  const page = await browser.newPage();
  page.setDefaultTimeout(30_000);
  await login.close();
  return { browser, page };
};

// Opens the user menu and returns its visible Appearance item, retrying while Alpine boots.
// A theme may render the menu itself: MeUI in the sidebar, Yavuz in its own topbar.
export const openAppearance = async (page) => {
  for (let i = 0; i < 20; i++) {
    const item = await page.evaluateHandle(() => [...document.querySelectorAll('.fi-dropdown-panel button, .fi-dropdown-panel a')].find((e) => /Appearance/.test(e.textContent) && e.getClientRects().length > 0));
    if (item.asElement()) return item.asElement();
    const trigger = await page.evaluateHandle(() => [...document.querySelectorAll('.fi-user-menu-trigger, .meui-profile-card, .fi-theme-user-menu button')].find((e) => e.getClientRects().length > 0));
    if (trigger.asElement()) await trigger.asElement().click().catch(() => {});
    await sleep(500);
  }
  throw new Error('Appearance item not found in the user menu');
};
