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
        $table=config('entrust.users_table');
        DB::table($table)->insert([
            'name'=>'admin',
            'email'=>'3040722030@qq.com',
            'password'=>bcrypt('admin888')
        ]);


        $users = factory(App\User::class, 105)->make()->toArray();
        DB::table($table)->insert($users);

    }
}
