<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成共 100 条微博假数据。
        $statuses = factory(Status::class)->times(100)->create();
    }
}
