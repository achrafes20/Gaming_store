<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

/** A few contact-form submissions ("Review" here means a testimonial/contact message, not a product review — that's ReviewProduct in catalog-service). */
class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Sarah Chen', '0612345678', 'sarah.chen@example.com', 'Great experience', 'Fast shipping and the packaging was excellent. Will shop here again!'],
            ['Marcus Johnson', '0623456789', 'marcus.j@example.com', 'Question about warranty', 'Do gaming laptops come with an extended warranty option?'],
            ['Priya Patel', '0634567890', 'priya.p@example.com', 'Love the selection', 'Best gaming gear selection I have found online. Keep it up!'],
        ] as [$name, $phone, $email, $subject, $message]) {
            Review::create([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
            ]);
        }
    }
}
