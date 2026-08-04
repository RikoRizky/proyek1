<x-public-layout 
    title="Registrasi Akun Perguruan Tinggi" 
    description="Buat akun admin untuk Perguruan Tinggi Anda."
>
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100">
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Buat Akun Perguruan Tinggi</h1>
            <p class="text-slate-500 mb-8">Pendaftaran khusus untuk pembeli <b>Paket {{ $transaction->package_name }}</b>.</p>

            <form action="{{ route('register-perti.process', $transaction->registration_token) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nama Perguruan Tinggi</label>
                        <div class="mt-2">
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-violet-600 sm:text-sm sm:leading-6">
                        </div>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="kode_pt" class="block text-sm font-medium leading-6 text-gray-900">Kode PT (Opsional)</label>
                        <div class="mt-2">
                            <input id="kode_pt" name="kode_pt" type="text" value="{{ old('kode_pt') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-violet-600 sm:text-sm sm:leading-6">
                        </div>
                        @error('kode_pt') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email Login</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" value="{{ old('email', $transaction->customer_email) }}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-violet-600 sm:text-sm sm:leading-6">
                        </div>
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Kata Sandi</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-violet-600 sm:text-sm sm:leading-6">
                        </div>
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Konfirmasi Kata Sandi</label>
                        <div class="mt-2">
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-violet-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="flex w-full justify-center rounded-md bg-emerald-600 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Daftar Akun Sekarang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-public-layout>
