<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Технологии', 'slug' => 'tehnologii'],
            ['name' => 'Путешествия', 'slug' => 'puteshestviya'],
            ['name' => 'Кулинария', 'slug' => 'kulinariya'],
            ['name' => 'Спорт', 'slug' => 'sport'],
            ['name' => 'Искусство', 'slug' => 'iskusstvo'],
        ];

        foreach($categories as $category){
            Category::create($category);
        }
    }
}
