<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Built entirely from the "order.created" event payload (orders-service) —
 * this service owns no order data of its own, see docs/architecture.md.
 */
class OrderConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $order) {}

    public function build()
    {
        return $this->subject('Your Order is Confirmed!')
            ->view('emails.order-confirmed', ['order' => $this->order]);
    }
}
