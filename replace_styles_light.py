import re

with open('resources/js/Pages/Welcome.vue', 'r') as f:
    content = f.read()

# Replace dark theme colors with light theme colors
replacements = [
    (r'\bbg-black\b', 'bg-[#ffffff]'),
    (r'\btext-white\b', 'text-[#1a1a1a]'),
    (r'\btext-\[#2ac0ff\]\b', 'text-[#012a4a]'),
    (r'\btext-\[#2ac0ff\]/70\b', 'text-[#012a4a]/70'),
    (r'\bbg-\[#012a4a\]\b', 'bg-[#ffffff]'),
    (r'\bbg-\[#012a4a\]/10\b', 'bg-[#0092ff]/10'),
    (r'\bbg-\[#012a4a\]/20\b', 'bg-[#0092ff]/20'),
    (r'\bbg-\[#0092ff\]/10\b', 'bg-[#eef8ff]'),
    (r'\bbg-\[#0092ff\]/20\b', 'bg-[#eef8ff]'),
    (r'\bborder-\[#2ac0ff\]/20\b', 'border-[#0092ff]/20'),
    (r'\bborder-\[#2ac0ff\]/30\b', 'border-[#0092ff]/30'),
    (r'\bborder-white\b', 'border-[#1a1a1a]/10'),
    (r'\bborder-white/10\b', 'border-[#1a1a1a]/10'),
    (r'\bborder-white/20\b', 'border-[#1a1a1a]/20'),
    (r'\bborder-white/30\b', 'border-[#1a1a1a]/30'),
    (r'\btext-\[#eef8ff\]\b', 'text-[#ffffff]'),
    (r'\bfrom-\[#012a4a\]\b', 'from-[#0092ff]/20'),
    (r'\bto-black\b', 'to-[#ffffff]'),
    (r'\bfrom-\[#2ac0ff\] to-\[#0092ff\]\b', 'from-[#012a4a] to-[#0092ff]'),
    (r'\bbg-gradient-to-t from-black/80 via-black/40\b', 'bg-gradient-to-t from-[#1a1a1a]/80 via-[#1a1a1a]/40'),
    (r'\bshadow-lg\b', 'shadow-[0_12px_16px_-4px_rgba(0,0,0,0.08)]'),
]

for old, new in replacements:
    content = re.sub(old, new, content)

with open('resources/js/Pages/Welcome.vue', 'w') as f:
    f.write(content)

print("Light styles replaced successfully.")
