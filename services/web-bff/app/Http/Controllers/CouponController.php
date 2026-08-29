<?php

namespace App\Http\Controllers;

use App\Services\OrdersClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    public function apply(Request $request, OrdersClient $orders)
    {
        $request->validate(['code' => 'required|string', 'total' => 'required|numeric']);

        $result = $orders->previewCoupon($request->code, $request->total);

        if ($result['status'] !== 200) {
            return back()->withErrors(['code' => $result['body']->message ?? 'Invalid or expired coupon.']);
        }

        Session::put('coupon', [
            'code' => $result['body']->code,
            'discount' => $result['body']->discount,
            'newTotal' => $result['body']->new_total,
        ]);

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function remove()
    {
        Session::forget('coupon');

        return back()->with('success', 'Coupon removed successfully!');
    }

    public function addcoupon()
    {
        return view('addcoupon');
    }

    public function storecoupon(Request $request, OrdersClient $orders)
    {
        $request->validate([
            'code' => 'required',
            'discount' => 'required|numeric',
            'type' => 'required|in:fixed,percent',
            'usage_limit' => 'nullable|integer',
            'expires_at' => 'required|date',
        ]);

        $orders->createCoupon($request->only('code', 'discount', 'type', 'usage_limit', 'expires_at'));

        return redirect('/coupons')->with('success', 'Coupon saved successfully!');
    }

    public function coupons(OrdersClient $orders)
    {
        $result = collect($orders->coupons()['body']);

        return view('coupons', ['coupons' => $result]);
    }

    public function RemoveCoupon($couponid, OrdersClient $orders)
    {
        $orders->deleteCoupon($couponid);

        return back()->with('success', 'Coupon deleted successfully!');
    }
}
