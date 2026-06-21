<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email KosMates</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f7f6;
            padding: 40px 0;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .email-header {
            text-align: center;
            padding: 30px 20px;
            background-color: #ffffff;
            border-bottom: 1px solid #f0f0f0;
        }
        .email-header img {
            max-height: 60px;
            width: auto;
        }
        .email-body {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        h1 {
            color: #1a1a1a;
            font-size: 22px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 700;
        }
        p {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 15px;
            color: #555555;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
        .email-footer {
            text-align: center;
            padding: 25px 20px;
            background-color: #f9fafb;
            color: #888888;
            font-size: 13px;
            border-top: 1px solid #f0f0f0;
        }
        .note {
            font-size: 13px;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <div class="email-header">
                <img src="{{ isset($message) ? $message->embed(public_path('KosMates/Asset 1.png')) : asset('KosMates/Asset 1.png') }}" alt="KosMates Logo">
            </div>
            <div class="email-body">
                <h1>Halo, Penyewa KosMates! 👋</h1>
                <p>Terima kasih telah bergabung dengan <strong>KosMates</strong>. Kami sangat senang dapat menyambut Anda!</p>
                <p>Sebelum Anda dapat mulai menggunakan berbagai fitur menarik untuk kebutuhan hunian Anda, mohon selesaikan proses verifikasi alamat email dengan mengklik tombol di bawah ini:</p>
                
                <div class="button-container">
                    <a href="{{ $url }}" class="button">Verifikasi Email Sekarang</a>
                </div>
                
                <p class="note">Sebagai informasi, tautan verifikasi ini hanya berlaku selama 60 menit.</p>
                <p>Jika Anda merasa tidak pernah mendaftar akun di KosMates, silakan abaikan pesan email ini.</p>
                
                <p style="margin-bottom: 0;">Salam Hangat,<br><strong>Tim KosMates</strong></p>
            </div>
            <div class="email-footer">
                &copy; {{ date('Y') }} KosMates. Semua hak cipta dilindungi.
            </div>
        </div>
    </div>
</body>
</html>
