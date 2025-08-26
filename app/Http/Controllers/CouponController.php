<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Coupon_User;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function remove()
    {
        if (session()->has('coupon')) {
            $couponCode = session('coupon.code');
            $coupon = Coupon::where('code', $couponCode)->first();
            $coupon->usage_limit++;
            if ($coupon) {
                Coupon_User::where('user_id', Auth::id())
                    ->where('coupon_id', $coupon->id)
                    ->delete();
            }
            $coupon->save();
            session()->forget('coupon');
        }

        return back()->with('success', 'Coupon removed successfully!');
    }
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);
        $coupon = Coupon::where('code', $request->code)->first();
        if (!$coupon || !$coupon->isValid()) {
            return back()->withErrors(['code' => 'Invalid or expired coupon.']);
        }
        if ($coupon->users()->where('user_id', Auth::id())->exists()) {
            return back()->withErrors(['code1' => 'You have already used this coupon.']);
        }
        if ($coupon->usage_limit && $coupon->users()->count() >= $coupon->usage_limit) {
            return back()->withErrors(['code2' => 'This coupon has reached its usage limit.']);
        }
        $cartProducts = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $cartProducts->sum(fn($item) => $item->product->price * $item->quantity);

        $discount = $coupon->calculateDiscount($total);
        $newTotal = $total - $discount;
        if ($coupon->usage_limit) {
            $coupon->decrement('usage_limit');
        }
        $coupon->users()->attach(Auth::id());
        session()->put('coupon', [
            'code'     => $coupon->code,
            'discount' => $discount,
            'type'     => $coupon->type,
            'value'    => $coupon->discount,
            'total'    => $total,
            'newTotal' => $newTotal
        ]);
        return back()->with('success', 'Coupon applied successfully!');
    }
    public function addcoupon()
    {
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
            $currentCoupon = Coupon::findOrFail($request->id);
            $currentCoupon->code = $request->code;
            $currentCoupon->discount = $request->discount;
            $currentCoupon->type = $request->type;
            $currentCoupon->usage_limit = $request->usage_limit;
            $currentCoupon->expires_at = $request->expires_at;
            $currentCoupon->save();
            return redirect()->back()->with('success', 'Coupon saved successfully!');
        } else {

            $newCoupon = new Coupon();
            $newCoupon->code = $request->code;
            $newCoupon->discount = $request->discount;
            $newCoupon->type = $request->type;
            $newCoupon->usage_limit = $request->usage_limit;
            $newCoupon->expires_at = $request->expires_at;
            $newCoupon->save();
            return redirect('/coupons')->with('success', 'Coupon saved successfully!');
        }

    }
    public function coupons()
    {
      $result = Coupon::all();

        return view('coupons', ['coupons' => $result]);
    }
    public function RemoveCoupon($couponid = null)
    {
        if ($couponid != null) {
            $currentCoupon = Coupon::find($couponid);
            $currentCoupon->delete();
            return redirect()->back()->with('success', 'Coupon deleted successfully!');

        } else {
            abort(403, "please enter product id in the route");
        }
    }
}
