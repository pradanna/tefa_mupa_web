<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Galleri;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        // Mengambil data gallery dari database, urutkan dari yang terbaru
        $gallery = Galleri::latest()->get();

        return view('gallery.index', compact('gallery'));
    }
}
