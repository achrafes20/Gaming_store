<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->withErrors(['code' => 'Invalid or expired coupon.']);
        }

        // Ex: total panier simulé ici à 100
        $total = 100;
        $newTotal = $coupon->apply($total);
        $discount = $total - $newTotal;

        // Optionnel : décrémenter usage_limit
        if ($coupon->usage_limit) {
            $coupon->decrement('usage_limit');
        }

        session()->put('coupon', $coupon->code);
        session()->put('discount', $discount);

        return back()->with('success', 'Coupon applied successfully!');
    }
}

