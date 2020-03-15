<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create an admin
        factory(User::class)->create(['email' => 'admin@coupon.test'])
            ->roles()->create(['slug' => 'admin']);

        //Create two normal users
        factory(User::class)->create(['email' => 'user1@coupon.test']);
        factory(User::class)->create(['email' => 'user2@coupon.test']);
    }
}
