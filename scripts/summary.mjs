import fs from 'node:fs';

// One line per theme from check-themes.mjs output (its stdout, saved to a file):
// reached and left, errors, anything left behind on Aurora's page.
// Usage: node scripts/summary.mjs <log file>
for (const line of fs.readFileSync(process.argv[2], 'utf8').split('\n').filter(Boolean)) {
  const [id, ...rest] = line.split(' ');
  let r;
  try { r = JSON.parse(rest.join(' ')); } catch { console.log(line.slice(0, 200)); continue; }
  if (r.crashed) { console.log(`${id}: CRASH ${r.crashed.slice(0, 150)}`); continue; }
  const errors = [...r.errorsUnder, ...r.errorsBack];
  const left = Object.entries(r.leftovers).filter(([k, v]) => k !== 'theme' && v && !(v.added?.length === 0 && v.removed?.length === 0));
  console.log(`${id}: ${r.into.reached && r.back.reached ? 'ok' : 'NOT REACHED'}`
    + `${errors.length ? ` | errors: ${errors.slice(0, 3).join('; ')}` : ''}`
    + `${left.length ? ` | left behind: ${JSON.stringify(Object.fromEntries(left))}` : ''}`);
}
