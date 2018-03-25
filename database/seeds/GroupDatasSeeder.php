<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GroupDatasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = Carbon::now();
        DB::table('user_groups')->insert([
            ['name' => '管理员', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '员工', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '代理', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '代理申请中', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '注册用户', 'created_at' => $dt, 'updated_at' => $dt],
        ]);
    }
}
