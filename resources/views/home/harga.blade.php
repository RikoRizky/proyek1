<x-public-layout title="Harga Layanan">
    {{-- Header Section --}}
    <div class="relative bg-white/70 border-b border-slate-200/60 py-16 backdrop-blur-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex rounded-full bg-violet-100 px-4 py-1 text-sm font-semibold text-violet-700">
                Harga Layanan
            </span>
            <h1 class="mt-4 text-4xl font-extrabold text-slate-900 tracking-tight sm:text-5xl">
                Pilih Paket Terbaik untuk Kampus Anda
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">
                SILADATA menawarkan berbagai pilihan paket berlangganan yang fleksibel, dirancang untuk memudahkan manajemen dokumen akreditasi dari program studi hingga tingkat universitas.
            </p>
        </div>
    </div>

    {{-- Paket Berlangganan Grid --}}
    @php
    $packages = [
        [
            'name' => 'Starter',
            'price' => 'Rp 499.000',
            'description' => '1 Perguruan Tinggi & 1 Program Studi',
            'features' => [
                'Upload hingga 100 dokumen',
                'Penyimpanan 5 GB',
                'Dashboard Monitoring',
                'Kategori Dokumen LAM'
            ]
        ],
        [
            'name' => 'Basic',
            'price' => 'Rp 1.499.000',
            'description' => 'Hingga 5 Program Studi',
            'features' => [
                'Upload hingga 1.000 dokumen',
                'Penyimpanan 25 GB',
                'Multi User',
                'Export Laporan'
            ]
        ],
        [
            'name' => 'Professional',
            'price' => 'Rp 3.999.000',
            'featured' => true,
            'description' => 'Pilihan Terbaik',
            'features' => [
                'Hingga 20 Program Studi',
                'Upload 10.000 dokumen',
                'Penyimpanan 100 GB',
                'Approval Dokumen',
                'Backup Otomatis'
            ]
        ],
        [
            'name' => 'Enterprise',
            'price' => 'Hubungi Kami',
            'description' => 'Universitas Besar',
            'features' => [
                'Program Studi Tak Terbatas',
                'Dokumen Tak Terbatas',
                'SSO',
                'Dedicated Support'
            ]
        ]
    ];
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-4 items-stretch">
            @foreach($packages as $package)
                <div class="
                    flex flex-col justify-between rounded-3xl p-8 transition-all duration-300
                    hover:-translate-y-1 hover:shadow-2xl h-full
                    {{ isset($package['featured']) ? 'bg-gradient-to-b from-violet-600 to-indigo-600 text-white shadow-xl scale-105 z-10' : 'bg-white border border-slate-200/80 shadow-sm' }}
                ">
                    <div>
                        @if(isset($package['featured']))
                            <div class="mb-4 inline-flex rounded-full bg-yellow-400 px-3 py-1 text-xs font-bold text-slate-900 uppercase tracking-wider">
                                ⭐ PILIHAN TERBAIK
                            </div>
                        @endif

                        <h3 class="text-2xl font-bold {{ isset($package['featured']) ? 'text-white' : 'text-slate-900' }}">
                            {{ $package['name'] }}
                        </h3>

                        <p class="mt-2 text-sm {{ isset($package['featured']) ? 'text-violet-100' : 'text-slate-500' }}">
                            {{ $package['description'] }}
                        </p>

                        <div class="mt-6">
                            <span class="text-3xl font-extrabold {{ isset($package['featured']) ? 'text-white' : 'text-slate-900' }}">
                                {{ $package['price'] }}
                            </span>
                            @if($package['price'] !== 'Hubungi Kami')
                                <span class="text-sm {{ isset($package['featured']) ? 'text-violet-200' : 'text-slate-500' }}">/tahun</span>
                            @endif
                        </div>

                        <div class="mt-6 border-t {{ isset($package['featured']) ? 'border-violet-500/50' : 'border-slate-100' }} pt-6">
                            <p class="text-xs font-bold uppercase tracking-wider {{ isset($package['featured']) ? 'text-violet-200' : 'text-slate-400' }}">Fitur Utama:</p>
                            <ul class="mt-4 space-y-3">
                                @foreach($package['features'] as $feature)
                                    <li class="flex items-start gap-2.5 text-sm">
                                        <span class="text-emerald-500 {{ isset($package['featured']) ? 'text-yellow-400' : '' }} font-bold">✓</span>
                                        <span class="{{ isset($package['featured']) ? 'text-violet-50' : 'text-slate-600' }}">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8">
                        @if($package['price'] === 'Hubungi Kami')
                            <a href="https://wa.me/62881023300457?text=Halo%20SILADATA,%20saya%20tertarik%20dengan%20Paket%20Enterprise" 
                               target="_blank"
                               class="block w-full text-center rounded-xl py-3 font-semibold transition-colors duration-200 bg-slate-900 text-white hover:bg-slate-800 shadow-lg shadow-slate-900/10">
                                Hubungi Sales
                            </a>
                        @else
                            <a href="https://wa.me/62881023300457?text=Halo%20SILADATA,%20saya%20tertarik%20untuk%20berlangganan%20Paket%20{{ $package['name'] }}"
                               target="_blank"
                               class="block w-full text-center rounded-xl py-3 font-semibold transition-colors duration-200
                                {{ isset($package['featured'])
                                    ? 'bg-white text-violet-700 hover:bg-slate-50 shadow-lg shadow-black/10'
                                    : 'bg-violet-600 text-white hover:bg-violet-700 shadow-lg shadow-violet-600/15'
                                }}">
                                Mulai Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FAQ Section --}}
        <div class="mt-24 border-t border-slate-200 pt-16 max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-slate-900">Pertanyaan yang Sering Diajukan</h2>
            <div class="mt-10 space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Bagaimana proses pembayaran berlangganan SILADATA?</h3>
                    <p class="mt-2 text-slate-600">Pembayaran dapat dilakukan melalui transfer bank resmi institusi setelah Anda berdiskusi dan menerbitkan invoice kerja sama.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Apakah saya bisa melakukan upgrade paket di tengah jalan?</h3>
                    <p class="mt-2 text-slate-600">Ya, Anda dapat melakukan upgrade paket berlangganan kapan saja dengan penyesuaian biaya prorata sesuai sisa masa aktif langganan Anda.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Apakah data kami aman dan terjamin kerahasiaannya?</h3>
                    <p class="mt-2 text-slate-600">Keamanan data merupakan prioritas utama kami. Seluruh dokumen disimpan di infrastruktur cloud yang terenkripsi dan dicadangkan secara berkala.</p>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
