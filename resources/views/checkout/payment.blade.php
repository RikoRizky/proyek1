<x-public-layout 
    title="Selesaikan Pembayaran" 
    description="Selesaikan pembayaran paket berlangganan SILADATA."
>
    <!-- Midtrans Snap Script -->
    @if(env('MIDTRANS_IS_PRODUCTION', false))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    @endif

    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100">
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Tagihan Pembayaran</h1>
            <p class="text-slate-500 mb-8">Selesaikan pembayaran untuk mengaktifkan paket Anda.</p>
            
            <div class="bg-slate-50 rounded-2xl p-6 mb-8 text-left border border-slate-200">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-slate-600">ID Pesanan</span>
                    <span class="font-mono text-slate-900 font-medium">{{ $transaction->order_id }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-slate-600">Paket</span>
                    <span class="text-slate-900 font-bold">{{ $transaction->package_name }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-slate-600">Nama</span>
                    <span class="text-slate-900 font-medium">{{ $transaction->customer_name }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                    <span class="text-slate-600 font-semibold">Total Pembayaran</span>
                    <span class="text-2xl text-violet-700 font-extrabold">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" class="w-full justify-center rounded-xl bg-slate-900 px-6 py-4 text-lg font-bold text-white shadow-lg shadow-slate-900/20 hover:bg-slate-800 hover:-translate-y-0.5 transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600">
                Pilih Metode Pembayaran
            </button>
        </div>
    </div>

    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function(){
        // SnapToken acquired from previous step
        snap.pay('{{ $snapToken }}', {
          // Optional
          onSuccess: function(result){
            /* You may add your own js here, this is just example */
            window.location.href = "{{ route('checkout.finish') }}?order_id={{ $transaction->order_id }}";
          },
          // Optional
          onPending: function(result){
            /* You may add your own js here, this is just example */
            window.location.href = "{{ route('checkout.finish') }}?order_id={{ $transaction->order_id }}";
          },
          // Optional
          onError: function(result){
            /* You may add your own js here, this is just example */
            window.location.href = "{{ route('checkout.finish') }}?order_id={{ $transaction->order_id }}";
          }
        });
      };
    </script>
</x-public-layout>
