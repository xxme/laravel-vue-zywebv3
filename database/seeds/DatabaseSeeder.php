<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call('GroupDatasSeeder');
        $this->call('TypeGroupsSeeder');
        $this->call('TypeWorkSeeder');
        $this->call('TypeAboutgoodsSeeder');
        $this->call('TypeCarefulSeeder');
        $this->call('TypeTotalSeeder');
    }
}
