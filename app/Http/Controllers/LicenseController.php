<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::latest()->get()->map(function ($item) {
            $isFilePdf = str_ends_with(strtolower($item->file), '.pdf');

            return [
                'name' => $item->name,
                'code' => $item->code,
                'type' => $item->type,
                'file_url' => asset('images/licenses/' . $item->file),
                'is_pdf' => $isFilePdf,
                'thumbnail' => $isFilePdf ? asset('images/local/pdf-icon.png') : asset('images/licenses/' . $item->file),
            ];
        });

        return response()->json($licenses);
    }
}
