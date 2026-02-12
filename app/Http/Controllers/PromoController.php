<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        // Ambil data promo yang belum kadaluarsa
        $promotions = Promotion::where('expired', '>=', now())
            ->orderBy('expired', 'asc')
            ->get()
            ->map(function ($promo) {
                return [
                    'id' => $promo->id,
                    'name' => $promo->name,
                    'desc' => $promo->desc,
                    'image' => asset('images/promotions/' . $promo->image),
                    'code' => $promo->code,
                    'expired_formatted' => \Carbon\Carbon::parse($promo->expired)->locale('id')->isoFormat('D MMMM Y'),
                ];
            });

        return response()->json($promotions);
    }
}
