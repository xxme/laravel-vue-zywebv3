<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TypeTruckSeeder extends Seeder
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
            ['group_id' => 5, 'name' => '薛团队', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 5, 'name' => '旭团队', 'created_at' => $dt, 'updated_at' => $dt],
            ['group_id' => 5, 'name' => '刘团队', 'created_at' => $dt, 'updated_at' => $dt],
        ]);
    }
}
