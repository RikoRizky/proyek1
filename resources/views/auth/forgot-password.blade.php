<x-guest-layout>
    <div class="space-y-6 text-center">
        <!-- Ikon Informatif -->
        <div class="flex justify-center">
            <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Pesan Utama -->
        <div>
            <h2 class="text-lg font-medium text-gray-900">Perlu Bantuan Akses?</h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('Silakan hubungi admin untuk melakukan reset password akun Anda.') }}
            </p>
        </div>

        <!-- Tombol Kembali menggunakan komponen Button bawaan -->
       <div class="flex items-center justify-center pt-4">
    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        {{ __('Kembali ke Login') }}
    </a>
</div>
    </div>
</x-guest-layout>