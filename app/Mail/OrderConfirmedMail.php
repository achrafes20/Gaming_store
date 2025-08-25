<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $somme;

    public function __construct(Order $order, $somme)
    {
        $this->order = $order;
        $this->somme = $somme;
    }

    public function build()
    {
        return $this->subject('Your Order is Confirmed!')
                    ->view('emails.order.confirmed');
    }
}
