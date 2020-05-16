<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\User::class, 20)->create()->each(function ($user) {
            $user->profile()->save(factory(App\Profile::class)->make());
            $user->contact()->save(factory(App\Contact::class)->make());
        });
    }
}
