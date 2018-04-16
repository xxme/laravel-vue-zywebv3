<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TypeCarefulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dt = Carbon::now();
        DB::table('types')->insert([
            ['group_id' => 2, 'name' => '地面需要养生', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '空吊作业', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '有垃圾处理现场报价', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '只搬大件', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '定金已付', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => 'タワー  塔楼', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '高级公寓', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '内置复式楼', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '停车位置远', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '家门到车距离远', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '台车通路有台阶', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '定金未付  暂时不生效状态', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '不要忘记自行车', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 2, 'name' => '新筑  一户建', 'created_at' => $dt, 'updated_at' => $dt],
        ]);
    }
}
