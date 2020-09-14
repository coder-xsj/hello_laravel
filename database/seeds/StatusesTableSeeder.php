<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Status;
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
