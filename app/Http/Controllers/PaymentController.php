<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'card_number' => 'required|digits_between:13,19',
            'expiry_date' => 'required|regex:/^(0[1-9]|1[0-2])\/\d{2}$/',
            'cvv'        => 'required|digits_between:3,4',
            'card_name'  => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($orderId);

        // ⚠️ En production il ne faut jamais stocker les infos brutes de carte bancaire,
        // il faut utiliser une passerelle (Stripe, PayPal, etc.)
        $payment = Payments::create([
            'user_id'     => Auth::id(),
            'order_id'    => $order->id,
            'card_number' => $request->card_number,
            'expiry_date' => $request->expiry_date,
            'cvv'         => $request->cvv,
            'card_name'   => $request->card_name,
            'status'      => 'success', // pour l'instant on force le succès
        ]);

        // Tu peux mettre à jour la commande comme "payée"
        $order->status = 'paid';
        $order->save();

        return redirect()->route('orders.show', $order->id)
                         ->with('success', '✅ Paiement effectué avec succès !');
    }
}
