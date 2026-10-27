import { BASE, openAppearance, saveTheme, signIn, sleep } from './lib.mjs';

// Timeline of one pick from <from> to <to>: page loads, the theme attribute, the cover and the
// modal, from the click on, so a slow or stuck swap shows where it waits.
// Usage: node scripts/probe-pick.mjs <from> <to>
const [FROM, TO] = process.argv.slice(2);
saveTheme(FROM);
const { browser, page } = await signIn();
await page.goto(BASE, { waitUntil: 'networkidle2' });
await sleep(2000);

let t0 = 0;
const t = () => `${Date.now() - t0}ms`;
page.on('framenavigated', (f) => f === page.mainFrame() && t0 && console.log(t(), 'navigated', f.url()));
page.on('load', () => t0 && console.log(t(), 'load'));
page.on('console', (m) => t0 && console.log(t(), 'console', m.type(), m.text().slice(0, 200)));

await (await openAppearance(page)).click();
await page.waitForSelector(`.pst-theme-option input[value="${TO}"]`, { visible: true });
await sleep(400);
const card = await page.evaluateHandle((v) => document.querySelector(`.pst-theme-option input[value="${v}"]`).closest('label'), TO);
t0 = Date.now();
await card.asElement().click();

let last = '';
while (Date.now() - t0 < 15_000) {
  const now = await page.evaluate(() => {
    const html = document.documentElement;
    return `${html.dataset.packstubTheme} cover=${!! document.querySelector('#nitpick-cover-layer') || html.hasAttribute('data-nitpick-cover')} still=${html.hasAttribute('data-nitpick-still')} modal=${!! document.querySelector('.fi-modal-window .pst-theme-picker')}`;
  }).catch(() => 'loading');
  if (now !== last) console.log(t(), now);
  last = now;
  await sleep(100);
}
await browser.close();
