<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Stateless preview: web-bff sends the coupon code + current cart total,
     * gets back the discount, and resends the code at checkout time.
     * (No server-side session shared across services.)
     */
    public function preview(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);
        $userId = $request->attributes->get('auth_user')['id'];

        $coupon = Coupon::where('code', $data['code'])->first();

        if (! $coupon || ! $coupon->isValid()) {
            return response()->json(['message' => 'Invalid or expired coupon.'], 422);
        }

        if ($coupon->usedBy($userId)) {
            return response()->json(['message' => 'You have already used this coupon.'], 422);
        }

        $discount = $coupon->calculateDiscount($data['total']);

        return [
            'code' => $coupon->code,
            'discount' => $discount,
            'new_total' => max(0, $data['total'] - $discount),
        ];
    }

    public function index()
    {
        return Coupon::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:coupons,code',
            'discount' => 'required|numeric',
            'type' => 'required|in:fixed,percent',
            'usage_limit' => 'nullable|integer',
            'expires_at' => 'required|date',
        ]);

        return response()->json(Coupon::create($data), 201);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code' => 'required|unique:coupons,code,'.$coupon->id,
            'discount' => 'required|numeric',
            'type' => 'required|in:fixed,percent',
            'usage_limit' => 'nullable|integer',
            'expires_at' => 'required|date',
        ]);

        $coupon->update($data);

        return $coupon;
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->noContent();
    }
}
