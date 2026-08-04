<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function showForm($package)
    {
        $validPackages = ['Starter', 'Pro', 'Enterprise'];
        if (!in_array($package, $validPackages)) {
            return redirect()->route('harga')->with('error', 'Paket tidak valid.');
        }

        return view('checkout.index', compact('package'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'package' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        $package = $request->package;
        $prices = [
            'Starter' => 1500000,
            'Pro' => 3500000,
            'Enterprise' => 7500000,
        ];

        if (!array_key_exists($package, $prices)) {
            return redirect()->route('harga')->with('error', 'Paket tidak valid.');
        }

        $orderId = 'TRX-' . time() . '-' . uniqid();
        $amount = $prices[$package];

        // Create transaction record
        $transaction = \App\Models\Transaction::create([
            'order_id' => $orderId,
            'package_name' => $package,
            'amount' => $amount,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'status' => 'pending',
            'registration_token' => \Illuminate\Support\Str::random(60),
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        return redirect()->route('checkout.payment', $transaction->order_id);
    }

    public function payment($orderId)
    {
        $transaction = \App\Models\Transaction::where('order_id', $orderId)->firstOrFail();

        if ($transaction->status === 'success') {
            return redirect()->route('checkout.finish')->with('success', 'Pembayaran sudah selesai.');
        }

        // Set up Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->order_id,
                'gross_amount' => $transaction->amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->customer_name,
                'email' => $transaction->customer_email,
            ],
            'item_details' => [
                [
                    'id' => 'PKG-'.$transaction->package_name,
                    'price' => $transaction->amount,
                    'quantity' => 1,
                    'name' => 'Paket Langganan ' . $transaction->package_name,
                ]
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return view('checkout.payment', compact('transaction', 'snapToken'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function finish(Request $request)
    {
        // Midtrans redirects here after payment (can be success, pending, failed)
        $orderId = $request->order_id;
        $transaction = \App\Models\Transaction::where('order_id', $orderId)->first();

        // Cek status ke Midtrans langsung jika di database masih pending
        // (Berfungsi sebagai penawar jika webhook telat atau tidak masuk karena localhost)
        if ($transaction && $transaction->status === 'pending') {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            
            try {
                $status = \Midtrans\Transaction::status($orderId);
                $trxStatus = $status->transaction_status;
                
                if ($trxStatus == 'settlement' || ($trxStatus == 'capture' && $status->fraud_status != 'challenge')) {
                    $transaction->update(['status' => 'success']);
                    
                    try {
                        \Illuminate\Support\Facades\Mail::to($transaction->customer_email)->send(new \App\Mail\PaymentSuccessMail($transaction));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to send payment success email: ' . $e->getMessage());
                    }

                    // Jika perpanjangan akun
                    if ($transaction->user_id) {
                        $user = \App\Models\User::find($transaction->user_id);
                        if ($user) {
                            $currentValidUntil = $user->package_valid_until;
                            $newValidUntil = now()->addYear();
                            if ($currentValidUntil && $currentValidUntil->isFuture()) {
                                $newValidUntil = $currentValidUntil->addYear();
                            }
                            $user->update([
                                'active_package' => $transaction->package_name,
                                'package_valid_until' => $newValidUntil,
                            ]);
                            $transaction->update(['is_registered' => true]);
                        }
                    }
                } elseif ($trxStatus == 'deny' || $trxStatus == 'cancel' || $trxStatus == 'expire') {
                    $transaction->update(['status' => 'failed']);
                }
            } catch (\Exception $e) {
                // Biarkan pending jika API gagal dijangkau
            }
        }

        return view('checkout.finish', compact('transaction'));
    }

    public function upgradePackages()
    {
        $packages = [
            [
                'name' => 'Starter',
                'price_label' => 'Rp 1.500.000',
                'price' => 1500000,
                'description' => 'Institusi kecil yang baru mencoba',
                'features' => [
                    'Maksimal 3 Akun Prodi',
                    'Upload Dokumen via Link (Google Drive)',
                    'Dashboard Monitoring Progress'
                ]
            ],
            [
                'name' => 'Pro',
                'price_label' => 'Rp 3.500.000',
                'price' => 3500000,
                'featured' => true,
                'description' => 'Terbaik untuk institusi menengah',
                'features' => [
                    'Semua Fitur Paket Starter',
                    'Maksimal 10 Akun Prodi',
                    'Bisa Upload File (Storage 10GB)',
                    'Cetak Laporan PDF'
                ]
            ],
            [
                'name' => 'Enterprise',
                'price_label' => 'Rp 7.500.000',
                'price' => 7500000,
                'description' => 'Universitas besar dengan banyak prodi',
                'features' => [
                    'Semua Fitur Paket Pro',
                    'Akun Program Studi Tidak Terbatas',
                    'Bisa Upload File (Storage 50GB)'
                ]
            ]
        ];

        return view('checkout.upgrade', compact('packages'));
    }

    public function processUpgrade(Request $request)
    {
        $request->validate([
            'package' => 'required|string',
        ]);

        $packageName = $request->package;
        $amount = 0;

        // Pricing logic
        if ($packageName === 'Starter') {
            $amount = 1500000;
        } elseif ($packageName === 'Pro') {
            $amount = 3500000;
        } elseif ($packageName === 'Enterprise') {
            $amount = 7500000;
        } else {
            return back()->with('error', 'Paket tidak valid.');
        }

        $user = auth()->user();

        // Create transaction in database explicitly linked to logged in user
        $transaction = \App\Models\Transaction::create([
            'order_id' => 'TRX-' . time() . '-' . uniqid(),
            'package_name' => $packageName,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'amount' => $amount,
            'status' => 'pending',
            'user_id' => $user->id,
            // is_registered should be false, or true since they are already registered? 
            // Wait, is_registered means 'has completed perti registration after this payment'.
            // For upgrades, they don't need to do perti registration. 
            // So we can set it to true to avoid showing them the registration button.
            'is_registered' => true, 
        ]);

        return redirect()->route('checkout.payment', $transaction->order_id);
    }
}
