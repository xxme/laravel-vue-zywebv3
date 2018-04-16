<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TypeAboutgoodsSeeder extends Seeder
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
            ['group_id' => 4, 'name' => '大冰箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'ドラム', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '大橱柜', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '大盆栽', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '复杂床', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '床拆装', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'L型沙发', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '桌拆装', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '空气净化机', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '煤油炉', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '无法装箱(非大件) 0-10', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '无法装箱(非大件) 10-20', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '无法装箱(非大件) 20-30', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '无法装箱(非大件) 30-40', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '散件0-10箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '散件10-20箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '散件20-30箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '散件30-40箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '散件40-50箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '散件50-60箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '散件60-70箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '散件70-80箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => '散件80-100箱', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'ハンガBOX×1', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'ハンガBOX×2', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'ハンガBOX×3', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'ハンガBOX×4', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'ハンガBOX×5', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'ハンガBOX×6', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'ハンガBOX×7', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 4, 'name' => 'ハンガBOX×8', 'created_at' => $dt, 'updated_at' => $dt],
        ]);
    }
}
