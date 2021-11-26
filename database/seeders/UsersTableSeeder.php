<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //创建 50 个假用户
		User::factory()->count(50)->create();
		$user = User::find(1);
		$user->name = 'Summer';
		$user->email = 'summer@example.com';
        $user->is_admin = true;
		$user->save();
    }
}
