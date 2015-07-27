<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Dream;

class DatabaseSeeder extends Seeder {

    protected $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        User::create([
            'name' => 'admin',
            'email' => 'admin@use.fr',
            'password' => bcrypt('admin'),
            'admin' => true
        ]);

        User::create([
            'name' => 'user',
            'email' => 'user@use.fr',
            'password' => bcrypt('user')
        ]);

        foreach (range(1, 10) as $i) {
            Dream::create([
                'content' => $i . ' ' . $this->lorem,
                'user_id' => rand(1, 2)
            ]);
        }
    }

}
