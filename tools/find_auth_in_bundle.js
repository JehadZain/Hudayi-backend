const fs = require('fs');
const path = require('path');

// Usage: node find_auth_in_bundle.js <bundle-file>
const file = process.argv[2] || 'C:\\Users\\rafat\\AppData\\Local\\Temp\\analytics.js';
const content = fs.readFileSync(file, 'utf8');

const patterns = [
  /\/(?:api|API)\/[-A-Za-z0-9_/.]*/g,
  /signIn|signin|login|sign_out|signOut|session|nextauth|csrf|csrfToken/gi,
  /NEXT_PUBLIC_[A-Z0-9_]+/g,
  new RegExp("(https?:\\/\\/[^\\\"']+\\/api\\/[^\\\"']+)", "g"),
  /fetch\(/g,
];

function unique(arr){ return Array.from(new Set(arr)); }

const results = {};
for(const p of patterns){
  const key = p.toString();
  results[key] = [];
  let m;
  while((m = p.exec(content)) !== null){
    results[key].push({ match: m[0], index: m.index, context: content.slice(Math.max(0,m.index-120), m.index+120) });
  }
  results[key] = unique(results[key].map(r=>JSON.stringify(r))).map(s=>JSON.parse(s));
}

// Also try to extract __NEXT_DATA__ from HTML if present in the bundle (sometimes inlined)
const nextDataRe = /__NEXT_DATA__"\>\s*(\{[\s\S]*?\})\s*<\//g;
let nd = [];
let m;
while((m = nextDataRe.exec(content)) !== null){
  try{ nd.push(JSON.parse(m[1])); }catch(e){}
}

const out = { file, totalLength: content.length, findings: results, nextData: nd };
fs.writeFileSync(path.join(process.cwd(), 'bundle_auth_findings.json'), JSON.stringify(out, null, 2));
console.log('Wrote bundle_auth_findings.json with summary.');
