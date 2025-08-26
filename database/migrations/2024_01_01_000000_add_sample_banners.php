<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSampleBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add sample banners for video platform
        DB::table('banners')->insert([
            [
                'name' => 'Content Creators Banner',
                'slug' => 'creators',
                'short_description' => 'Discover amazing content creators',
                'description' => 'Watch free intro videos and explore premium content from talented creators',
                'image' => 'images.png',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Creator Profile Banner',
                'slug' => 'creator-profile',
                'short_description' => 'Creator Profile',
                'description' => 'Learn more about this content creator',
                'image' => 'images.png',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Video Platform Banner',
                'slug' => 'video-platform',
                'short_description' => 'Video Platform',
                'description' => 'Watch and learn from amazing content creators',
                'image' => 'images.png',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove sample banners
        DB::table('banners')->whereIn('slug', ['creators', 'creator-profile', 'video-platform'])->delete();
    }
}
