const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const reportDir = __dirname;
const outputFile = path.join(reportDir, 'rapport-mine-adventure.md');
const tempDir = path.join(reportDir, 'temp-mermaid');
const cssFile = path.join(reportDir, 'pdf-styles.css');

// CSS pour le PDF
const pdfStyles = `
body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 11pt;
  line-height: 1.6;
  color: #1a1a1a;
}

h1 { font-size: 24pt; page-break-before: always; margin-top: 0; }
h1:first-of-type { page-break-before: avoid; }
h2 { font-size: 18pt; margin-top: 1.5em; }
h3 { font-size: 14pt; margin-top: 1.2em; }
h4 { font-size: 12pt; margin-top: 1em; }

/* Éviter les coupures de page dans les éléments */
h1, h2, h3, h4, h5, h6 {
  page-break-after: avoid;
}

p, li {
  orphans: 3;
  widows: 3;
}

/* Images et diagrammes */
img {
  max-width: 100%;
  height: auto;
  display: block;
  margin: 1em auto;
  page-break-inside: avoid;
}

/* Diagrammes Mermaid */
img[alt^="Diagramme"] {
  max-width: 100%;
  width: auto;
  page-break-inside: avoid;
  page-break-before: auto;
  page-break-after: auto;
}

/* Diagramme 5 (Architecture) : réduire la taille */
img[alt="Diagramme 5"] {
  max-width: 50%;
}

/* Diagramme 9 (MCD) : réduire la taille */
img[alt="Diagramme 9"] {
  max-width: 50%;
}

/* Diagramme 11 (Judge0 Isolation) : réduire la taille */
img[alt="Diagramme 11"] {
  max-width: 50%;
}

/* Screenshots mobile - taille réduite */
img[src*="mobile"], img[alt*="mobile"], img[alt*="Mobile"] {
  max-width: 180px !important;
  height: auto !important;
  display: inline-block;
  margin: 0.5em 1em;
  page-break-inside: avoid;
}

/* Blocs de code */
pre {
  background: #f5f5f5;
  padding: 1em;
  border-radius: 4px;
  font-size: 9pt;
  overflow-x: auto;
  page-break-inside: avoid;
}

code {
  font-family: 'JetBrains Mono', 'Fira Code', monospace;
  font-size: 9pt;
}

/* Séparateurs de section */
hr {
  border: none;
  border-top: 1px solid #ddd;
  margin: 2em 0;
  page-break-after: avoid;
}

/* Listes */
ul, ol {
  page-break-inside: avoid;
}
`;

// Write CSS file
fs.writeFileSync(cssFile, pdfStyles);

// Order of files
const files = [
  '00-sommaire.md',
  '01-introduction.md',
  '02-presentation-projet.md',
  '03-gestion-projet.md',
  '04-specifications-fonctionnelles.md',
  '05-specifications-techniques.md',
  '06-ccp1-frontend/01-environnement-travail.md',
  '06-ccp1-frontend/02-maquettage.md',
  '06-ccp1-frontend/03-interfaces-statiques.md',
  '06-ccp1-frontend/04-interfaces-dynamiques.md',
  '07-ccp2-backend/01-base-donnees.md',
  '07-ccp2-backend/02-acces-donnees.md',
  '07-ccp2-backend/03-composants-metier.md',
  '07-ccp2-backend/04-deploiement.md',
  '08-securite.md',
  '9-bilan.md',
];

// Create temp directory
if (!fs.existsSync(tempDir)) {
  fs.mkdirSync(tempDir, { recursive: true });
}

// Mermaid config for consistent readable text
const mermaidConfig = {
  theme: 'default',
  themeVariables: {
    fontSize: '16px',
    fontFamily: 'Inter, sans-serif'
  },
  flowchart: {
    fontSize: 14,
    nodeSpacing: 50,
    rankSpacing: 50,
    curve: 'basis'
  },
  sequence: {
    fontSize: 14,
    actorFontSize: 14,
    noteFontSize: 13,
    messageFontSize: 14
  },
  er: {
    fontSize: 14,
    entityPadding: 15
  },
  gitGraph: {
    fontSize: 14
  }
};
const mermaidConfigFile = path.join(tempDir, 'mermaid-config.json');
fs.writeFileSync(mermaidConfigFile, JSON.stringify(mermaidConfig));

let mermaidCounter = 0;
let combinedContent = '';

for (const file of files) {
  const filePath = path.join(reportDir, file);
  if (!fs.existsSync(filePath)) {
    console.warn(`Warning: ${file} not found, skipping...`);
    continue;
  }

  let content = fs.readFileSync(filePath, 'utf-8');

  // Extract and convert mermaid diagrams to images
  const mermaidRegex = /```mermaid\n([\s\S]*?)```/g;
  const matches = [...content.matchAll(mermaidRegex)];

  // Process matches in reverse order to preserve indices, but use global numbering
  const baseCounter = mermaidCounter;
  for (let i = matches.length - 1; i >= 0; i--) {
    const match = matches[i];
    const globalDiagramNumber = baseCounter + i + 1; // Global number based on document order
    mermaidCounter++;
    const mermaidCode = match[1];
    const mermaidFile = path.join(tempDir, `diagram-${globalDiagramNumber}.mmd`);
    const svgFile = path.join(tempDir, `diagram-${globalDiagramNumber}.svg`);

    // Write mermaid code to file
    fs.writeFileSync(mermaidFile, mermaidCode);

    // Convert to SVG using mmdc with config for consistent fonts, auto-sized
    try {
      execSync(`mmdc -i "${mermaidFile}" -o "${svgFile}" -b transparent -c "${mermaidConfigFile}" -s 2`, { stdio: 'pipe' });
      // Replace mermaid block with image reference
      const relativeSvgPath = path.relative(reportDir, svgFile);
      const startIndex = match.index;
      const endIndex = match.index + match[0].length;
      content = content.substring(0, startIndex) + `![Diagramme ${globalDiagramNumber}](${relativeSvgPath})` + content.substring(endIndex);
      console.log(`  Converted diagram ${globalDiagramNumber}: ${mermaidCode.split('\n')[0].trim()}`);
    } catch (error) {
      console.error(`Error converting diagram ${globalDiagramNumber}:`, error.message);
    }
  }

  // Fix image paths - make them relative to report directory
  content = content.replace(/!\[(.*?)\]\(\.\.\/(imgs\/[^)]+)\)/g, '![$1]($2)');

  combinedContent += content + '\n\n';
}

// Write combined markdown
fs.writeFileSync(outputFile, combinedContent);
console.log(`Combined markdown written to: ${outputFile}`);
console.log(`Total mermaid diagrams converted: ${mermaidCounter}`);

// Convert to PDF using md-to-pdf with custom stylesheet
console.log('Converting to PDF...');
try {
  execSync(`npx md-to-pdf "${outputFile}" --stylesheet "${cssFile}" --pdf-options '{"format": "A4", "margin": {"top": "20mm", "bottom": "20mm", "left": "20mm", "right": "20mm"}, "printBackground": true}'`, {
    cwd: reportDir,
    stdio: 'inherit'
  });
  console.log('PDF generated successfully!');
} catch (error) {
  console.error('Error generating PDF:', error.message);
}