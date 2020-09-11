<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 为了批量填充数据，当然要暂时性关闭安全保护，填充完毕后重新打开保护。
        Model::unguard();
        $this->call(UsersTableSeeder::class);
        Model::reguard();
    }
}
