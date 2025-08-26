<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with Creator role
        $creators = User::role('Creator')->get();

        foreach ($creators as $creator) {
            // Create wallet if it doesn't exist
            Wallet::firstOrCreate(
                ['creator_id' => $creator->id],
                [
                    'balance' => 0.00,
                    'pending_balance' => 0.00,
                    'total_earned' => 0.00,
                    'total_paid_out' => 0.00,
                ]
            );
        }

        $this->command->info("Created wallets for {$creators->count()} creators!");
    }
}
