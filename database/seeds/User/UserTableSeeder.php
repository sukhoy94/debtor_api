<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    private $password = 'test1234';
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 100; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $this->password,
            ]);
        }
               
    }
}
