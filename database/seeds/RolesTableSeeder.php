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
        $table=config('entrust.roles_table');
        DB::table($table)->insert([
            'name' => 'general_administrator',
            'display_name' => '普通管理员'
        ]);
        DB::table($table)->insert([
            'name' => 'system_administrator',
            'display_name' => '系统管理员'
        ]);
        DB::table($table)->insert([
            'name' => 'super_administrator',
            'display_name' => '超级管理员'
        ]);

    }
}
