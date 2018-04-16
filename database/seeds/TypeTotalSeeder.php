<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TypeTotalSeeder extends Seeder
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
            ['group_id' => 3, 'name' => '小半车', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 3, 'name' => '半车', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 3, 'name' => '大半车', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 3, 'name' => '2t積みきり', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 3, 'name' => '2t×2', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 3, 'name' => '2Tロング', 'created_at' => $dt, 'updated_at' => $dt],
        ]);
    }
}
