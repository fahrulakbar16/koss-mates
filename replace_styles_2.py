import re

with open('resources/js/Pages/Welcome.vue', 'r') as f:
    content = f.read()

replacements = [
    (r'\bborder-primary-100\b', 'border-[#2ac0ff]/20'),
    (r'\bbg-primary-400\b', 'bg-[#2ac0ff]'),
    (r'\bbg-primary-500\b', 'bg-[#0092ff]'),
    (r'\bfrom-primary-500 to-primary-600\b', 'from-[#0092ff] to-[#012a4a]'),
    (r'\bshadow-primary-600/30\b', 'shadow-[#0092ff]/30'),
    (r'\btext-primary-100\b', 'text-[#2ac0ff]'),
]

for old, new in replacements:
    content = re.sub(old, new, content)

with open('resources/js/Pages/Welcome.vue', 'w') as f:
    f.write(content)

print("Remaining styles replaced successfully.")
