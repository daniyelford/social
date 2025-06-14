<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TelegramChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('telegram_channels')->insert([
            [
                'category_id' => 1,
                'channel_id' => 100123456789,
                'username' => 'examplechannel1',
                'title' => 'کانال تست اول',
                'participants_count' => 15000,
                'about' => 'این یک کانال تلگرام تستی است برای اهداف توسعه.',
                'photo_path' => 'banner.png',
                'admins' => json_encode([
                    [
                        'id'=> '1234',
                    'username'=> '@alimmdi',
                    'first_name'=> 'first_name',
                    'last_name'=> 'last_name',
                    'is_self'=> true
                    ],
                    [

                        'id'=> '12345',
                        'username'=> '@mmdalimmdi',
                        'first_name'=> 'first_name',
                        'last_name'=> 'last_name',
                        'is_self'=> false
                    ]
                ]),
                'price' => 250000,
                'influencer_score' => 78.5,
                'payment_type' => 24,
                'insight' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'channel_id' => 100987654321,
                'username' => 'examplechannel2',
                'title' => 'کانال تست دوم',
                'participants_count' => 45000,
                'about' => 'توضیحات مربوط به کانال دوم.',
                'photo_path' => 'banner.png',
                'admins' => json_encode([
                    [
                        'id'=> '1234',
                    'username'=> '@alimmdi',
                    'first_name'=> 'first_name',
                    'last_name'=> 'last_name',
                    'is_self'=> true
                    ],
                    [

                        'id'=> '12345',
                        'username'=> '@mmdalimmdi',
                        'first_name'=> 'first_name',
                        'last_name'=> 'last_name',
                        'is_self'=> false
                    ]
                ]),
                'price' => 500000,
                'influencer_score' => 85.2,
                'payment_type' => 24,
                'insight' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
