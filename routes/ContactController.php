<?php

namespace App\Http\Controllers;

use App\Models\Inbox;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'category' => 'required|string',
            'message' => 'required|string',
        ]);

        // Simpan ke tabel inbox
        Inbox::create($request->all());

        return redirect()->back()->with('success', 'Pesan berhasil dikirim! Kami akan segera menghubungi Anda.');
    }
}
