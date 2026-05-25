import re

with open('resources/js/Pages/Welcome.vue', 'r') as f:
    content = f.read()

# Add font import to style block
style_import = """<style scoped>
@import url('https://api.fontshare.com/v2/css?f[]=clash-grotesk@400,500,600,700&display=swap');

/* High-performance Smoothing */"""
content = content.replace("<style scoped>\n/* High-performance Smoothing */", style_import)

# Replace standard Tailwind classes with brand tokens
replacements = [
    (r'\btext-gray-900\b', 'text-white'),
    (r'\btext-gray-800\b', 'text-white'),
    (r'\btext-gray-600\b', 'text-[#2ac0ff]'),
    (r'\btext-gray-500\b', 'text-[#2ac0ff]'),
    (r'\btext-gray-400\b', 'text-[#2ac0ff]/70'),
    (r'\bbg-gray-50\b', 'bg-black'),
    (r'\bbg-gray-100\b', 'bg-[#012a4a]'),
    (r'\bbg-white\b', 'bg-[#012a4a]'),
    (r'\bbg-white/10\b', 'bg-[#0092ff]/10'),
    (r'\bbg-white/20\b', 'bg-[#0092ff]/20'),
    (r'\bborder-gray-100\b', 'border-[#2ac0ff]/20'),
    (r'\bborder-gray-300\b', 'border-[#2ac0ff]/30'),
    (r'\bbg-primary-600\b', 'bg-[#0092ff]'),
    (r'\bhover:bg-primary-700\b', 'hover:bg-[#2ac0ff]'),
    (r'\btext-primary-600\b', 'text-[#2ac0ff]'),
    (r'\btext-primary-500\b', 'text-[#2ac0ff]'),
    (r'\btext-primary-700\b', 'text-[#eef8ff]'),
    (r'\bbg-primary-50\b', 'bg-[#012a4a]'),
    (r'\bfrom-primary-600\b', 'from-[#012a4a]'),
    (r'\bto-primary-800\b', 'to-black'),
    (r'\bto-primary-500\b', 'to-[#0092ff]'),
    (r'\bshadow-xl\b', 'shadow-[0_4px_20px_0px_rgba(0,0,0,0.15)]'),
    (r'\bshadow-sm\b', 'shadow-[0_2px_2px_0px_rgba(0,0,0,0.15)]'),
    (r'\brounded-full\b', 'rounded-[71px]'),
    (r'\brounded-\[24px\]\b', 'rounded-[71px]'),
    (r'\brounded-3xl\b', 'rounded-[71px]'),
    (r'\brounded-\[32px\]\b', 'rounded-[71px]'),
    (r'\bscroll-smooth\b', 'scroll-smooth font-[\'Clash_Grotesk\',sans-serif] bg-black text-white'),
]

for old, new in replacements:
    content = re.sub(old, new, content)

with open('resources/js/Pages/Welcome.vue', 'w') as f:
    f.write(content)

print("Styles replaced successfully.")
