const fs = require('fs');
const content = fs.readFileSync('resources/js/Pages/Admin/Dashboard.vue', 'utf8');
const lines = content.split('\n');

let level = 0;
let inTemplate = false;
let inScript = false;

for (let i = 0; i < lines.length; i++) {
    const line = lines[i];
    if (line.includes('<template>')) {
        inTemplate = true;
        continue;
    }
    if (line.includes('</template>')) {
        inTemplate = false;
        continue;
    }
    if (line.includes('<script')) {
        inScript = true;
    }
    
    if (inTemplate) {
        // very basic tag counting for divs
        const opens = (line.match(/<div(\s|>)/g) || []).length;
        const closes = (line.match(/<\/div>/g) || []).length;
        
        level += opens;
        level -= closes;
        
        if (opens > 0 || closes > 0) {
            console.log(`Line ${i+1}: Open ${opens}, Close ${closes} -> Level: ${level}`);
        }
    }
}
console.log('Final level in template:', level);
