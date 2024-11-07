<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Negocios'],
            ['name' => 'Personal'],
            ['name' => 'E-commerce'],
        ];

        foreach ($categories as $category) {
            $this->createCategory($category['name']);
        }
    }

    protected function createCategory($name)
    {
        $slug = $this->generateUniqueSlug($name);
        Category::create([
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    protected function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 2;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
