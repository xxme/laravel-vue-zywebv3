<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TypeGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = Carbon::now();
        DB::table('type_groups')->insert([
            ['name' => '工作分类', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '特别注意', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '物品总量', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '物品结构', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '散件数量', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '无法装箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '带挂衣量', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '被子套数', 'created_at' => $dt, 'updated_at' => $dt],
            ['name' => '车辆信息', 'created_at' => $dt, 'updated_at' => $dt],
        ]);
    }
}
