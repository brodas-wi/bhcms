<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $article = Article::create([
            'title' => 'Introducción a Laravel',
            'content' => 'Este es un artículo introductorio sobre Laravel...'
        ]);

        // Asign tags and categories to articles
        $categories = Category::all();
        $tags = Tag::all();

        $article->categories()->attach($categories->random(2)->pluck('id'));
        $article->tags()->attach($tags->random(2)->pluck('id'));
    }
}

