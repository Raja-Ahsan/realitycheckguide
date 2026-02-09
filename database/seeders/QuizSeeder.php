<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizCategory;
use App\Models\QuizQuestion;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample categories
        $categories = [
            [
                'name' => 'General Knowledge',
                'description' => 'Test your general knowledge with questions about various topics including history, science, geography, and more.',
                'is_active' => true,
            ],
            [
                'name' => 'Science & Technology',
                'description' => 'Explore questions about physics, chemistry, biology, computer science, and technological advancements.',
                'is_active' => true,
            ],
            [
                'name' => 'History & Geography',
                'description' => 'Challenge yourself with questions about world history, historical events, countries, capitals, and geographical features.',
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Entertainment',
                'description' => 'Test your knowledge about sports, movies, music, celebrities, and entertainment industry.',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = QuizCategory::create($categoryData);

            // Add sample questions for each category
            $this->addSampleQuestions($category);
        }
    }

    private function addSampleQuestions(QuizCategory $category)
    {
        $questions = [];

        switch ($category->name) {
            case 'General Knowledge':
                $questions = [
                    [
                        'question' => 'What is the capital of France?',
                        'option_a' => 'London',
                        'option_b' => 'Paris',
                        'option_c' => 'Berlin',
                        'option_d' => 'Madrid',
                        'correct_option' => 'B',
                    ],
                    [
                        'question' => 'Which planet is known as the Red Planet?',
                        'option_a' => 'Venus',
                        'option_b' => 'Mars',
                        'option_c' => 'Jupiter',
                        'option_d' => 'Saturn',
                        'correct_option' => 'B',
                    ],
                    [
                        'question' => 'What is the largest mammal in the world?',
                        'option_a' => 'African Elephant',
                        'option_b' => 'Blue Whale',
                        'option_c' => 'Giraffe',
                        'option_d' => 'Hippopotamus',
                        'correct_option' => 'B',
                    ],
                ];
                break;

            case 'Science & Technology':
                $questions = [
                    [
                        'question' => 'What is the chemical symbol for gold?',
                        'option_a' => 'Go',
                        'option_b' => 'Gd',
                        'option_c' => 'Au',
                        'option_d' => 'Ag',
                        'correct_option' => 'C',
                    ],
                    [
                        'question' => 'Which programming language was created by Guido van Rossum?',
                        'option_a' => 'Java',
                        'option_b' => 'Python',
                        'option_c' => 'C++',
                        'option_d' => 'JavaScript',
                        'correct_option' => 'B',
                    ],
                    [
                        'question' => 'What does CPU stand for?',
                        'option_a' => 'Central Processing Unit',
                        'option_b' => 'Computer Processing Unit',
                        'option_c' => 'Central Program Unit',
                        'option_d' => 'Computer Program Unit',
                        'correct_option' => 'A',
                    ],
                ];
                break;

            case 'History & Geography':
                $questions = [
                    [
                        'question' => 'In which year did World War II end?',
                        'option_a' => '1944',
                        'option_b' => '1945',
                        'option_c' => '1946',
                        'option_d' => '1947',
                        'correct_option' => 'B',
                    ],
                    [
                        'question' => 'Which country has the most natural lakes?',
                        'option_a' => 'Russia',
                        'option_b' => 'Canada',
                        'option_c' => 'United States',
                        'option_d' => 'Finland',
                        'correct_option' => 'B',
                    ],
                    [
                        'question' => 'Who was the first person to walk on the moon?',
                        'option_a' => 'Buzz Aldrin',
                        'option_b' => 'Neil Armstrong',
                        'option_c' => 'John Glenn',
                        'option_d' => 'Alan Shepard',
                        'correct_option' => 'B',
                    ],
                ];
                break;

            case 'Sports & Entertainment':
                $questions = [
                    [
                        'question' => 'Which country won the FIFA World Cup in 2018?',
                        'option_a' => 'Germany',
                        'option_b' => 'Brazil',
                        'option_c' => 'France',
                        'option_d' => 'Argentina',
                        'correct_option' => 'C',
                    ],
                    [
                        'question' => 'Who directed the movie "Inception"?',
                        'option_a' => 'Steven Spielberg',
                        'option_b' => 'Christopher Nolan',
                        'option_c' => 'Martin Scorsese',
                        'option_d' => 'Quentin Tarantino',
                        'correct_option' => 'B',
                    ],
                    [
                        'question' => 'Which band released the album "Abbey Road"?',
                        'option_a' => 'The Rolling Stones',
                        'option_b' => 'The Beatles',
                        'option_c' => 'Led Zeppelin',
                        'option_d' => 'Pink Floyd',
                        'correct_option' => 'B',
                    ],
                ];
                break;
        }

        foreach ($questions as $questionData) {
            QuizQuestion::create(array_merge($questionData, [
                'category_id' => $category->id,
                'is_active' => true,
            ]));
        }
    }
}
