<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404: Halaman Tidak Ditemukan | KosMates</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff1f1',
                            100: '#ffdfdf',
                            200: '#ffc5c5',
                            300: '#ff9d9d',
                            400: '#ff6666',
                            500: '#fa5252',
                            600: '#e93636',
                            700: '#c32828',
                            800: '#a12424',
                            900: '#852424',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #fa5252 0%, #ff9d9d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex items-center justify-center p-6">
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary-50 rounded-full blur-3xl opacity-60"></div>
    </div>

    <div class="max-w-md w-full text-center relative z-10">
        <div class="mb-8">
            <h1 class="text-9xl font-extrabold text-primary-100 select-none leading-none">404</h1>
        </div>

        <div class="-mt-16 relative">
            <h2 class="text-3xl font-bold mb-4">Halaman Tidak Ditemukan</h2>
            <p class="text-gray-500 mb-10 leading-relaxed">
                Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan ke alamat lain.
            </p>

            <a href="/" class="inline-flex items-center justify-center px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-xl shadow-primary-600/30 hover:scale-[1.02] active:scale-[0.98]">
                Kembali ke Beranda
            </a>
        </div>

        <div class="mt-16 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-400">&copy; {{ date('Y') }} KosMates. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
