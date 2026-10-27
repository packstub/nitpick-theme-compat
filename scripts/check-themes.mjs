import fs from 'node:fs';
import { BASE, ROOT, W, openAppearance, saveTheme, signIn, sleep } from './lib.mjs';

// For each theme id, picks it from Aurora the way a user does (user menu → Appearance → card),
// screenshots the panel under it (dashboard, list, edit, list in dark mode), picks Aurora again
// and compares the page with how it started, so anything the theme leaves behind shows.
// Usage: node scripts/check-themes.mjs <id> [<id> ...]   (results in storage/lab/results, or LAB_OUT)
const IDS = process.argv.slice(2);
const OUT = process.env.LAB_OUT ?? `${ROOT}storage/lab/results`;
fs.mkdirSync(OUT, { recursive: true });

const { browser, page } = await signIn();

let log;
page.on('pageerror', (e) => log?.errors.push(`pageerror: ${e.message}`));
page.on('console', (m) => m.type() === 'error' && log?.errors.push(`console: ${m.text()}`));
page.on('response', (r) => r.status() >= 400 && log?.errors.push(`HTTP ${r.status()} ${r.url()}`));
page.on('requestfailed', (r) => log?.errors.push(`failed: ${r.url()} ${r.failure()?.errorText}`));

const settle = async () => {
  await page.waitForNetworkIdle({ idleTime: 500, timeout: 15_000 }).catch(() => {});
  const t0 = Date.now();
  // The cover should be gone within a few seconds of the page settling.
  while (Date.now() - t0 < 8000) {
    const covered = await page.evaluate(() => !!document.querySelector('#nitpick-cover-layer') || document.documentElement.hasAttribute('data-nitpick-cover')).catch(() => true);
    if (!covered) return true;
    await sleep(250);
  }
  return false;
};
const go = async (path) => {
  await page.goto(`${BASE}${path}`, { waitUntil: 'networkidle2' });
  await settle();
  await sleep(700);
};
const snapshot = () => page.evaluate(() => {
  const html = document.documentElement, cs = getComputedStyle(html), body = getComputedStyle(document.body);
  return {
    theme: html.dataset.packstubTheme ?? null,
    dark: html.classList.contains('dark'),
    sheets: [...document.querySelectorAll('link[rel=stylesheet]')].map((l) => l.getAttribute('href').replace(/\?.*$/, '')),
    styleTags: document.querySelectorAll('style').length,
    scripts: [...document.querySelectorAll('script[src]')].map((s) => s.getAttribute('src').replace(/\?.*$/, '')),
    primary: cs.getPropertyValue('--primary-500').trim(),
    gray: cs.getPropertyValue('--gray-500').trim(),
    font: body.fontFamily,
    bodyBg: body.backgroundColor,
    htmlAttrs: [...html.attributes].map((a) => a.name).filter((n) => !['data-packstub-theme', 'style'].includes(n)).sort(),
    bodyClass: document.body.className,
    overflowX: html.scrollWidth > innerWidth + 1,
    sidebar: !!document.querySelector('.fi-sidebar'),
    topbar: !!document.querySelector('.fi-topbar'),
  };
});
const shot = async (name) => {
  await page.mouse.move(W - 4, 4);
  await sleep(300);
  await page.screenshot({ path: `${OUT}/${log.id}/${name}.png` });
};
// User menu → Appearance → the theme's card; a pick of a plugin theme reloads the page.
const pick = async (id) => {
  await (await openAppearance(page)).click();
  await page.waitForSelector(`.pst-theme-option input[value="${id}"]`, { visible: true });
  await sleep(300);
  const card = await page.evaluateHandle((v) => document.querySelector(`.pst-theme-option input[value="${v}"]`).closest('label'), id);
  const t0 = Date.now();
  await card.asElement().click();
  while (Date.now() - t0 < 15_000) {
    const now = await page.evaluate(() => document.documentElement.dataset.packstubTheme).catch(() => null);
    if (now === id) break;
    await sleep(200);
  }
  await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 4000 }).catch(() => {});
  const lifted = await settle();
  return { ms: Date.now() - t0, lifted, reached: (await page.evaluate(() => document.documentElement.dataset.packstubTheme)) === id };
};
const diff = (a, b) => ({
  added: b.filter((x) => !a.includes(x)),
  removed: a.filter((x) => !b.includes(x)),
});

const results = {};
for (const id of IDS) {
  log = { id, errors: [] };
  fs.mkdirSync(`${OUT}/${id}`, { recursive: true });
  saveTheme('aurora');
  try {
    await go('');
    await page.evaluate(() => { localStorage.removeItem('filament:theme:mode'); localStorage.removeItem('theme'); });
    await go('');
    const before = await snapshot();
    log.errors = [];

    const into = await pick(id);
    await shot('1-picker');
    await page.keyboard.press('Escape');
    await sleep(400);
    const under = await snapshot();
    await shot('2-dashboard');
    await go('/orders');
    await shot('3-orders');
    await go('/orders/1/edit');
    await shot('4-edit');
    await page.evaluate(() => { localStorage.setItem('filament:theme:mode', 'dark'); localStorage.setItem('theme', 'dark'); });
    await go('/orders');
    const darkSnap = await snapshot();
    await shot('5-orders-dark');
    await page.evaluate(() => { localStorage.setItem('filament:theme:mode', 'light'); localStorage.setItem('theme', 'light'); });
    await go('');
    const errorsUnder = [...new Set(log.errors)];
    log.errors = [];

    const back = await pick('aurora');
    await page.keyboard.press('Escape');
    await sleep(400);
    await page.evaluate(() => { localStorage.removeItem('filament:theme:mode'); localStorage.removeItem('theme'); });
    await go('');
    const after = await snapshot();
    await shot('6-back-to-aurora');

    results[id] = {
      into, back, errorsUnder, errorsBack: [...new Set(log.errors)],
      under: { ...under, sheets: diff(before.sheets, under.sheets), scripts: diff(before.scripts, under.scripts) },
      dark: { dark: darkSnap.dark, bodyBg: darkSnap.bodyBg, overflowX: darkSnap.overflowX },
      leftovers: {
        sheets: diff(before.sheets, after.sheets), scripts: diff(before.scripts, after.scripts),
        styleTags: after.styleTags - before.styleTags,
        primary: before.primary === after.primary ? null : [before.primary, after.primary],
        font: before.font === after.font ? null : [before.font, after.font],
        bodyBg: before.bodyBg === after.bodyBg ? null : [before.bodyBg, after.bodyBg],
        htmlAttrs: diff(before.htmlAttrs, after.htmlAttrs), bodyClass: before.bodyClass === after.bodyClass ? null : [before.bodyClass, after.bodyClass],
        theme: after.theme,
      },
    };
  } catch (e) {
    results[id] = { crashed: e.message, errors: [...new Set(log.errors)] };
    await page.screenshot({ path: `${OUT}/${id}/crash.png` }).catch(() => {});
  }
  console.log(id, JSON.stringify(results[id]));
}
fs.writeFileSync(`${OUT}/results-${new Date().toISOString().replace(/[:.]/g, '-')}.json`, JSON.stringify(results, null, 1));
await browser.close();
