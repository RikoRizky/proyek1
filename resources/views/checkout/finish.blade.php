<x-public-layout 
    title="Status Pembayaran" 
    description="Status pembayaran paket berlangganan SILADATA Anda."
>
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100">
            @if(!$transaction)
                <div class="text-red-500 mb-4 text-6xl">❓</div>
                <h1 class="text-2xl font-bold text-slate-900 mb-2">Transaksi Tidak Ditemukan</h1>
                <p class="text-slate-600 mb-6">Kami tidak dapat menemukan transaksi Anda.</p>
                <a href="{{ route('home') }}" class="inline-block rounded-xl bg-violet-600 px-6 py-3 font-semibold text-white hover:bg-violet-500">Kembali ke Beranda</a>
            @elseif($transaction->status === 'success')
                <div class="text-emerald-500 mb-4 text-6xl">🎉</div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Pembayaran Berhasil!</h1>
                <p class="text-slate-600 mb-6">Terima kasih, pembayaran untuk <b>Paket {{ $transaction->package_name }}</b> telah kami terima.</p>
                
                @if(!Auth::check() && !$transaction->user_id)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 mb-8 text-left">
                        <h3 class="font-bold text-emerald-900 mb-2">Langkah Selanjutnya</h3>
                        <p class="text-emerald-700 text-sm">Silakan buat akun administrator Perguruan Tinggi (Perti) Anda sekarang untuk mulai mengelola dokumen akreditasi.</p>
                    </div>

                    <a href="{{ route('register-perti.form', $transaction->registration_token) }}" class="inline-block w-full justify-center rounded-xl bg-emerald-600 px-6 py-4 text-lg font-bold text-white shadow-lg shadow-emerald-600/20 hover:bg-emerald-500 hover:-translate-y-0.5 transition-all">
                        Buat Akun Perguruan Tinggi
                    </a>
                @else
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 mb-8 text-left">
                        <h3 class="font-bold text-emerald-900 mb-2">Paket Diperbarui</h3>
                        <p class="text-emerald-700 text-sm">Paket berlangganan Anda telah berhasil diperpanjang/diperbarui. Anda dapat melanjutkan aktivitas Anda.</p>
                    </div>

                    <a href="{{ route('dashboard') }}" class="inline-block w-full justify-center rounded-xl bg-slate-900 px-6 py-4 text-lg font-bold text-white shadow-lg shadow-slate-900/20 hover:bg-slate-800 hover:-translate-y-0.5 transition-all">
                        Kembali ke Dashboard
                    </a>
                @endif
            @elseif($transaction->status === 'pending')
                <div class="text-amber-500 mb-4 text-6xl">⏳</div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Menunggu Pembayaran</h1>
                <p class="text-slate-600 mb-6">Silakan selesaikan pembayaran Anda menggunakan metode yang dipilih. Jika sudah membayar, harap tunggu beberapa saat hingga sistem memverifikasi.</p>
                
                <a href="{{ route('checkout.payment', $transaction->order_id) }}" class="inline-block rounded-xl bg-violet-600 px-6 py-3 font-semibold text-white hover:bg-violet-500">Lihat Cara Bayar</a>
            @else
                <div class="text-red-500 mb-4 text-6xl">❌</div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Pembayaran Gagal / Dibatalkan</h1>
                <p class="text-slate-600 mb-6">Pembayaran Anda tidak dapat diselesaikan atau telah kedaluwarsa.</p>
                
                <a href="{{ route('harga') }}" class="inline-block rounded-xl bg-slate-900 px-6 py-3 font-semibold text-white hover:bg-slate-800">Coba Beli Lagi</a>
            @endif
        </div>
    </div>
</x-public-layout>
