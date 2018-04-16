<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TypeWorkSeeder extends Seeder
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
            ['group_id' => 1, 'name' => 'Aコース', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => 'Bコース', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => 'Sコース', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '見積もり已完成  正在考虑', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '見積もり', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '送货', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '回收', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '处理', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '空调', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '解体(洗)', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '解体(空)', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '邮寄纸箱10', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '邮寄纸箱20', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '邮寄纸箱30', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '邮寄纸箱40', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '邮寄纸箱50', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '清扫', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '客人来店', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '休息', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 1, 'name' => '其它', 'created_at' => $dt, 'updated_at' => $dt],
        ]);
    }
}
