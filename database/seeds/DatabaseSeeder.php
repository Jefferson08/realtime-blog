<?php

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

        //Creating 5 categories
        factory(App\Category::class, 5)->create();

        //Creating 5 users
        factory(App\User::class, 5)->create()->each(function ($user) {
            //Foreach user, creating 3 posts
            for ($i=0; $i < 3; $i++) { 
                $post = factory(App\Post::class)->make();
                $user->posts()->save($post);
                factory(App\Tag::class)->create(['post_id' => $post]); //Foreach post, creating your tag
            }
        });
    }
}
