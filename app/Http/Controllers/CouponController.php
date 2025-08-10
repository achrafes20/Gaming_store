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
    public function addcoupon(){
        return view('addcoupon');
    }


     public function storecoupon(Request $request)
{
    $request->validate([
        'code' => 'required|unique:coupons,code,' . $request->id,
        'discount' => 'required|numeric',
        'type' => 'required|in:fixed,percent',
        'usage_limit' => 'nullable|integer',
        'expires_at' => 'required|date',
    ]);

    if ($request->id) {
        // Modification
        $currentCoupon = Coupon::findOrFail($request->id);
        $currentCoupon->code = $request->code;
        $currentCoupon->discount = $request->discount;
        $currentCoupon->type = $request->type;
        $currentCoupon->usage_limit = $request->usage_limit;
        $currentCoupon->expires_at = $request->expires_at;
        $currentCoupon->save();
    } else {
        // Création
        $newCoupon = new Coupon();
        $newCoupon->code = $request->code;
        $newCoupon->discount = $request->discount;
        $newCoupon->type = $request->type;
        $newCoupon->usage_limit = $request->usage_limit;
        $newCoupon->expires_at = $request->expires_at;
        $newCoupon->save();
    }

    return redirect('/ProductsTable');
}

public function coupons(){

    $result=Coupon::all();

    return view('coupons',['coupons'=>$result]);


}

 public function RemoveCoupon($couponid = null)
    {
        if ($couponid != null) {
            $currentCoupon = Coupon::find($couponid);
            $currentCoupon->delete();
            return redirect('/product');
        } else {
            abort(403, "please enter product id in the route");
        }
    }
}

