<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'locale',
            'value' => 'ja',
        ]);

        Setting::create([
            'key' => 'currency',
            'value' => 'yen',
        ]);

        Setting::create([
            'key' => 'timezone',
            'value' => 'Asia/tokyo',
        ]);
    }
}
