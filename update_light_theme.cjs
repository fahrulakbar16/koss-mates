const fs = require('fs');
const path = require('path');

const files = [
    'resources/js/Pages/Welcome.vue',
    'resources/js/Layouts/PublicLayout.vue'
];

files.forEach(file => {
    const filePath = path.join(__dirname, file);
    let content = fs.readFileSync(filePath, 'utf8');

    // Remove dark classes like dark:bg-gray-900
    content = content.replace(/dark:[A-Za-z0-9\-\/\.\[\]]+\s?/g, '');
    
    // Replace rounded-xl and rounded-2xl on buttons to match design.md 71px pill radius
    // We only want to target buttons/inputs if possible, but globally replacing rounded-xl with rounded-full
    // in Welcome might be too aggressive if it hits cards.
    // Let's explicitly replace btn-premium rounded classes:
    content = content.replace(/btn-premium[a-zA-Z0-9\s\-]+rounded-xl/g, match => match.replace('rounded-xl', 'rounded-full'));
    content = content.replace(/rounded-xl text-sm font-semibold/g, 'rounded-full text-sm font-semibold');
    content = content.replace(/rounded-xl bg-primary-600/g, 'rounded-full bg-primary-600');

    fs.writeFileSync(filePath, content);
    console.log(`Updated ${file}`);
});
