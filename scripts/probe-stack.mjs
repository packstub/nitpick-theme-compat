import { BASE, openAppearance, saveTheme, signIn, sleep } from './lib.mjs';

// Under a theme, opens the Appearance modal and lists what decides how it stacks: its ancestors
// that start a stacking context or a containing block, and every CSS rule setting the modal
// container's z-index, with its stylesheet.
// Usage: node scripts/probe-stack.mjs <theme> [<png>]
const [THEME, PNG] = process.argv.slice(2);
saveTheme(THEME);
const { browser, page } = await signIn();
await page.goto(BASE, { waitUntil: 'networkidle2' });
await sleep(1500);
await (await openAppearance(page)).click();
await page.waitForSelector('.pst-theme-picker', { visible: true });
await sleep(800);
console.log(await page.evaluate(() => {
  const out = [];
  for (let e = document.querySelector('.pst-theme-picker'); e && e !== document.documentElement; e = e.parentElement) {
    const cs = getComputedStyle(e);
    const flags = [
      cs.zIndex !== 'auto' && `z=${cs.zIndex}`, cs.position !== 'static' && cs.position,
      cs.transform !== 'none' && 'transform', cs.filter !== 'none' && 'filter', cs.backdropFilter !== 'none' && 'backdrop-filter',
      cs.contain !== 'none' && `contain=${cs.contain}`, cs.isolation === 'isolate' && 'isolate', cs.overflow !== 'visible' && `overflow=${cs.overflow}`,
      cs.willChange !== 'auto' && `will-change=${cs.willChange}`, cs.containerType !== 'normal' && `container=${cs.containerType}`,
    ].filter(Boolean);
    if (flags.length) out.push(`${e.tagName.toLowerCase()}.${[...e.classList].slice(0, 3).join('.')} ${flags.join(' ')}`);
  }
  const ctn = document.querySelector('.pst-theme-picker').closest('.fi-modal-window-ctn');
  const walk = (rules, href) => {
    for (const r of rules) {
      try { if (r.selectorText && ctn.matches(r.selectorText) && r.style.zIndex) out.push(`${href.split('/').slice(-2).join('/')} :: ${r.selectorText.slice(0, 160)} { z-index: ${r.style.zIndex} }`); } catch {}
      if (r.cssRules) walk(r.cssRules, href);
    }
  };
  for (const sheet of document.styleSheets) { try { walk(sheet.cssRules, sheet.href ?? 'inline'); } catch {} }
  return out;
}));
if (PNG) await page.screenshot({ path: PNG });
await browser.close();
