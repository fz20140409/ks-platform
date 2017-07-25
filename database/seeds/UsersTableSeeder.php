<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'=>'admin',
            'email'=>'3040722030@qq.com',
            'password'=>bcrypt('admin888')
        ]);


        $users = factory(App\User::class, 105)->make()->toArray();
        DB::table('users')->insert($users);

    }
}
