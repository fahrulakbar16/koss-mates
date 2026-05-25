const fs = require('fs');
const path = require('path');

const files = [
    'resources/js/Pages/Welcome.vue',
    'resources/js/Layouts/PublicLayout.vue'
];

files.forEach(file => {
    const filePath = path.join(__dirname, file);
    let content = fs.readFileSync(filePath, 'utf8');

    // Make all buttons and search inputs rounded-full (pill shape) to match design.md
    content = content.replace(/rounded-xl/g, 'rounded-full');
    content = content.replace(/rounded-2xl/g, 'rounded-[24px]'); // slightly smaller card radius for lighter feel

    fs.writeFileSync(filePath, content);
    console.log(`Updated radii in ${file}`);
});
