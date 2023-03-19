<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(5)
            ->has(
                Account::factory()
                    ->count(5)
                    ->has(
                        Contact::factory()
                        ->count(5)
                    )
            )
            ->create();
    }
}
