<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if categories already exist
        if (Category::count() > 0) {
            $this->command->info('Categories already exist. Skipping seeder.');
            return;
        }

        $categories = [
            [
                'title' => 'Skilled Trades',
                'description' => 'Explore careers in skilled trades including Electrician, Plumber, Welder, and other hands-on professions. These careers offer stable employment, good earning potential, and the satisfaction of working with your hands.',
                'image' => null,
                'status' => 1,
            ],
            [
                'title' => 'Tech & IT',
                'description' => 'Discover opportunities in technology and information technology fields such as Web Developer, Data Analyst, UX Designer, Software Engineer, and more. Stay ahead in the digital age with these in-demand careers.',
                'image' => null,
                'status' => 1,
            ],
            [
                'title' => 'Healthcare',
                'description' => 'Learn about healthcare careers including Nurse, Medical Assistant, Radiologist, Doctor, and other medical professions. Make a difference in people\'s lives while building a rewarding career.',
                'image' => null,
                'status' => 1,
            ],
            [
                'title' => 'Design & Creative',
                'description' => 'Explore creative careers such as Graphic Designer, Animator, Illustrator, Web Designer, and other artistic professions. Turn your creativity into a fulfilling career.',
                'image' => null,
                'status' => 1,
            ],
            [
                'title' => 'Business & Admin',
                'description' => 'Discover business and administrative careers including Accountant, HR Professional, Marketing Coordinator, Business Analyst, and more. Build a successful career in the corporate world.',
                'image' => null,
                'status' => 1,
            ],
            [
                'title' => 'Master Chef',
                'description' => 'Explore culinary careers including Chef, Pastry Chef, Food Critic, Restaurant Manager, and other food service professions. Turn your passion for food into a delicious career.',
                'image' => null,
                'status' => 1,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'created_by' => 1, // Assuming admin user ID is 1
                'title' => $categoryData['title'],
                'slug' => Str::slug($categoryData['title']),
                'description' => $categoryData['description'],
                'image' => $categoryData['image'],
                'status' => $categoryData['status'],
            ]);
        }

        $this->command->info('Categories seeded successfully!');
    }
}
