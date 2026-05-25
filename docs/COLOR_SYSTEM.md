# Color System Documentation

## Overview

Proyek ini menggunakan sistem warna terpusat yang memungkinkan kustomisasi warna melalui settings dan konsistensi di seluruh aplikasi.

## Primary Color Palette

Sistem warna menggunakan primary color sebagai warna utama dengan palet lengkap:

- `primary-50` hingga `primary-950`: Shades dari primary color
- `primary-DEFAULT` atau `primary-500`: Warna primary default

## Cara Menggunakan

### 1. Menggunakan Tailwind Classes

```vue
<!-- Background -->
<div class="bg-primary-500">Primary background</div>
<div class="bg-primary-600 hover:bg-primary-700">Hover effect</div>

<!-- Text -->
<p class="text-primary-600">Primary text</p>

<!-- Border -->
<input class="border-primary-500 focus:ring-primary-500" />

<!-- Button -->
<button class="bg-primary-600 hover:bg-primary-700 text-white">
    Click me
</button>
```

### 2. Menggunakan CSS Variables

```vue
<style>
.custom-element {
    background-color: var(--color-primary);
    color: var(--color-primary-600);
    border-color: var(--color-primary-500);
}
</style>
```

### 3. Menggunakan Composable (Dynamic Colors)

```vue
<script setup>
import { useColors } from '@/Composables/useColors';

const { colors, withOpacity } = useColors();

// colors.value.primary - akan mengambil dari settings atau default
// withOpacity('primary', 0.1) - untuk opacity
</script>

<template>
    <div :style="{ backgroundColor: colors.primary }">
        Dynamic color from settings
    </div>
    <div :style="{ backgroundColor: withOpacity('primary', 0.1) }">
        With opacity
    </div>
</template>
```

## Color Palettes

### Primary (Blue)
- Default: `#465fff`
- Digunakan untuk: Buttons, links, focus states, primary actions

### Secondary (Purple)
- Default: `#8B5CF6`
- Digunakan untuk: Secondary actions, accents

### Accent (Green)
- Default: `#10B981`
- Digunakan untuk: Success states, positive actions

### Success
- Default: `#12b76a`
- Digunakan untuk: Success messages, confirmations

### Warning
- Default: `#f79009`
- Digunakan untuk: Warning messages, cautions

### Error
- Default: `#f04438`
- Digunakan untuk: Error messages, destructive actions

### Info
- Default: `#0ba5ec`
- Digunakan untuk: Informational messages

## Kustomisasi Warna

Warna dapat dikustomisasi melalui Settings page di admin panel. Warna yang diubah akan otomatis diterapkan ke seluruh aplikasi melalui CSS variables yang diupdate oleh `ThemeProvider.vue`.

## Best Practices

1. **Gunakan Primary Color untuk Actions Utama**
   - Buttons utama
   - Links penting
   - Focus states
   - Active states

2. **Gunakan Semantic Colors untuk Feedback**
   - Success untuk konfirmasi
   - Error untuk kesalahan
   - Warning untuk peringatan
   - Info untuk informasi

3. **Hindari Hardcoded Colors**
   - ❌ `bg-indigo-600`
   - ✅ `bg-primary-600`

4. **Gunakan Shades yang Tepat**
   - Lighter shades (50-300) untuk backgrounds
   - Medium shades (400-600) untuk interactive elements
   - Darker shades (700-950) untuk text dan emphasis

## Migration dari Hardcoded Colors

Jika menemukan hardcoded colors seperti `indigo-*` atau `blue-*`, ganti dengan:

- `indigo-*` → `primary-*`
- `blue-*` → `primary-*` (untuk consistency)

Contoh:
```vue
<!-- Before -->
<button class="bg-indigo-600 hover:bg-indigo-700">

<!-- After -->
<button class="bg-primary-600 hover:bg-primary-700">
```
