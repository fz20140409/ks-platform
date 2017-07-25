<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
            'name' => 'general_administrator',
            'display_name' => '普通管理员'
        ]);
        DB::table('roles')->insert([
            'name' => 'system_administrator',
            'display_name' => '系统管理员'
        ]);
        DB::table('roles')->insert([
            'name' => 'super_administrator',
            'display_name' => '超级管理员'
        ]);

    }
}
