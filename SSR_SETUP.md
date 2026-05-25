# Inertia SSR Setup Guide

Setup Inertia Server-Side Rendering (SSR) untuk landing page telah dikonfigurasi.

## File yang Telah Dibuat/Dimodifikasi

1. **resources/js/ssr.js** - Entry point untuk SSR rendering
2. **vite.config.js** - Konfigurasi SSR build
3. **app/Http/Middleware/HandleInertiaRequests.php** - Middleware dengan SSR support
4. **package.json** - Scripts untuk build SSR

## Cara Menggunakan SSR

### 1. Build SSR Assets

```bash
npm run build:ssr
```

Atau untuk development dengan watch mode:

```bash
npm run dev:ssr
```

### 2. Enable SSR di HandleInertiaRequests

SSR sudah dikonfigurasi di `HandleInertiaRequests` middleware dengan property `$ssrView = 'app'`.

### 3. Jalankan SSR Server (Opsional)

Untuk production, Anda bisa menjalankan SSR server terpisah menggunakan Node.js. Namun, dengan konfigurasi saat ini, SSR akan otomatis aktif ketika:

- SSR assets sudah di-build (`npm run build:ssr`)
- Middleware sudah dikonfigurasi dengan benar
- Request datang ke server

## Manfaat SSR

1. **SEO yang Lebih Baik**: Konten HTML sudah di-render di server, sehingga search engine bisa langsung membaca konten
2. **Initial Load Time**: Konten langsung tersedia tanpa menunggu JavaScript selesai
3. **Social Media Sharing**: Meta tags dan konten sudah tersedia untuk preview

## Catatan Penting

- SSR hanya aktif ketika SSR assets sudah di-build
- Pastikan untuk menjalankan `npm run build:ssr` setelah perubahan pada komponen Vue
- Untuk development, gunakan `npm run dev:ssr` untuk auto-rebuild
- SSR memerlukan Node.js environment yang sama dengan development

## Troubleshooting

Jika SSR tidak bekerja:

1. Pastikan SSR assets sudah di-build: `npm run build:ssr`
2. Check console untuk error
3. Pastikan semua dependencies sudah terinstall: `npm install`
4. Pastikan `@vue/server-renderer` sudah terinstall

