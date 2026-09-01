<?php

namespace Database\Seeders;

use App\Models\Sub;
use Illuminate\Database\Seeder;

/** A few newsletter subscribers, so the admin isn't looking at an empty list. */
class SubSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'newsletter.fan@example.com',
            'deals.hunter@example.com',
            'earlyaccess@example.com',
        ] as $email) {
            Sub::create(['email' => $email]);
        }
    }
}
