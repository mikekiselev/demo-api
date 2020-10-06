<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 15; $i++) {
            $post = Post::firstOrCreate(['title' => "post {$i}"]);
            $j = rand(1, 15);
            $tag = Tag::find($j);
            $post->tags()->save($tag);
            $tag = Tag::find(16 - $j);
            $post->tags()->save($tag);
        }
    }
}
