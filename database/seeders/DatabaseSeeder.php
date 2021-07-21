<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('posts')->truncate();

         \App\Models\User::factory(10)->create()->each(function
        ($user){
            $user->posts()->save(Post::factory()->make());
        });
         //\App\Models\Post::factory(10)->create();



    }
}
