<x-public-layout 
    title="Checkout Paket" 
    description="Isi data untuk melanjutkan pembayaran paket berlangganan SILADATA."
>
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100">
            <h1 class="text-2xl font-bold text-slate-900 mb-6">Informasi Pemesanan</h1>
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="mb-8 p-4 bg-violet-50 rounded-xl text-violet-900 font-semibold">
                Paket yang Dipilih: <span class="font-extrabold">{{ $package }}</span>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="package" value="{{ $package }}">
                
                <div class="space-y-6">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium leading-6 text-gray-900">Nama Pendaftar / Institusi</label>
                        <div class="mt-2">
                            <input id="customer_name" name="customer_name" type="text" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-violet-600 sm:text-sm sm:leading-6">
                        </div>
                        @error('customer_name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="customer_email" class="block text-sm font-medium leading-6 text-gray-900">Email Utama (Untuk Notifikasi & Tagihan)</label>
                        <div class="mt-2">
                            <input id="customer_email" name="customer_email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-violet-600 sm:text-sm sm:leading-6">
                        </div>
                        @error('customer_email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-violet-600 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-violet-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Lanjutkan ke Pembayaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-public-layout>
