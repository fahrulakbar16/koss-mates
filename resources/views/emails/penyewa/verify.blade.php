@component('mail::message')
# Halo, Penyewa Tharahub! 👋

Terima kasih telah bergabung dengan **Tharahub**. Kami sangat senang dapat menyambut Anda!

Sebelum Anda dapat mulai menggunakan berbagai fitur menarik untuk kebutuhan hunian Anda, mohon selesaikan proses verifikasi alamat email dengan mengklik tombol di bawah ini:

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Verifikasi Email Sekarang
@endcomponent

*Sebagai informasi, tautan verifikasi ini hanya berlaku selama 60 menit.*

Jika Anda merasa tidak pernah mendaftar akun di Tharahub, silakan abaikan pesan email ini.

Salam Hangat,<br>
**Tim {{ config('app.name', 'Tharahub') }}**
@endcomponent
