<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CreatorPricingRule;
use Spatie\Permission\Models\Role;

class CreatorPricingRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with Creator role
        $creatorRole = Role::where('name', 'Creator')->first();
        
        if (!$creatorRole) {
            $this->command->info('Creator role not found. Skipping pricing rules seeding.');
            return;
        }

        $creators = User::role('Creator')->get();

        foreach ($creators as $creator) {
            // Check if creator already has pricing rules
            if (!$creator->pricingRules()->exists()) {
                CreatorPricingRule::create([
                    'creator_id' => $creator->id,
                    'videos_sold_threshold' => 15,
                    'max_price_cap' => 19.99,
                    'min_price_floor' => 0.99,
                    'custom_pricing_enabled' => false,
                    'pricing_tiers' => [
                        'bulk_5' => [
                            'count' => 5,
                            'discount' => 10,
                            'description' => 'Buy 5 videos, get 10% off'
                        ],
                        'bulk_10' => [
                            'count' => 10,
                            'discount' => 20,
                            'description' => 'Buy 10 videos, get 20% off'
                        ]
                    ]
                ]);

                $this->command->info("Created pricing rules for creator: {$creator->name}");
            }
        }

        $this->command->info('Creator pricing rules seeding completed!');
    }
}
