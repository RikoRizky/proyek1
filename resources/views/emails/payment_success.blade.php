<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Berhasil - SILADATA</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px;">
    <h2>Pembayaran Berhasil!</h2>
    <p>Halo <strong>{{ $transaction->customer_name }}</strong>,</p>
    <p>Terima kasih, pembayaran untuk <strong>Paket {{ $transaction->package_name }}</strong> telah kami terima.</p>

    @if(!$transaction->user_id && !$transaction->is_registered)
    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <h3 style="margin-top: 0;">Langkah Selanjutnya</h3>
        <p>Silakan buat akun administrator Perguruan Tinggi (Perti) Anda untuk mulai mengelola dokumen akreditasi.</p>
        <a href="{{ route('register-perti.form', $transaction->registration_token) }}" style="display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Buat Akun Perguruan Tinggi</a>
    </div>
    @else
    <p>Paket langganan Anda telah berhasil diperpanjang/diupgrade.</p>
    @endif

    <p>Terima kasih telah menggunakan SILADATA.</p>
    <p>Tim SILADATA</p>
</body>
</html>
