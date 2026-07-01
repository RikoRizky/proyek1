<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DiscussionController extends Controller
{
    /**
     * Display the discussion form page.
     */
    public function show(): View
    {
        return view('home.discussion');
    }

    /**
     * Handle mock form submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:20',
            'perusahaan' => 'required|string|max:255', // PT
            'jabatan' => 'required|string|max:255',
            'kebutuhan' => 'required|array|min:1|max:3',
            'sistem_saat_ini' => 'required|string',
            'investasi' => 'required|string',
        ]);

        // Redirect back with success message
        return redirect()->route('discussion')->with('success', 'Formulir diskusi berhasil dikirim! Tim kami akan segera menghubungi Anda.');
    }
}
