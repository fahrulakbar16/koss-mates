<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500: Terjadi Kesalahan | KosMates</title>
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
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center relative z-10">
        <div class="mb-8">
            <h1 class="text-9xl font-extrabold text-primary-100 select-none">500</h1>
        </div>

        <div class="-mt-16">
            <h2 class="text-3xl font-bold mb-4">Terjadi Kesalahan Server</h2>
            <p class="text-gray-500 mb-10 leading-relaxed">
                Maaf, sedang terjadi kendala pada sistem kami. Tim teknis kami sedang berusaha memperbaikinya secepat mungkin.
            </p>

            <a href="/" class="inline-flex items-center justify-center px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl transition-all duration-300 shadow-xl shadow-primary-600/30">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
