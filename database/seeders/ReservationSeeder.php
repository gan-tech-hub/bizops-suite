<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Customer;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            Reservation::create([
                'title'       => '打ち合わせ - ' . $customer->name,
                'start'       => Carbon::now()->addDays(rand(0, 10))->setTime(rand(9, 18), 0),
                'end'         => Carbon::now()->addDays(rand(0, 10))->setTime(rand(9, 18), 0)->addHour(),
                'location'    => '会議室' . chr(rand(65, 67)), // A〜C
                'description' => 'テスト用の予約データです',
                'staff'       => ['佐藤', '鈴木', '高橋'][array_rand(['佐藤', '鈴木', '高橋'])],
                'customer_id' => $customer->id,
                'color'       => ['#f87171', '#60a5fa', '#34d399'][array_rand(['#f87171', '#60a5fa', '#34d399'])],
            ]);
        }
/*
        \App\Models\Reservation::truncate();
        
        Reservation::create([
            'title' => '商談（田中様）',
            'start' => '2025-09-05 10:00:00',
            'end'   => '2025-09-05 11:00:00',
            'description' => '新サービスの提案',
            'location' => '茨城支店 応接室A',
        ]);

        Reservation::create([
            'title' => '定例ミーティング',
            'start' => '2025-09-06 14:00:00',
            'end'   => '2025-09-06 15:00:00',
            'description' => '今週の進捗確認',
            'location' => 'オンライン',
        ]);

        Reservation::create([
            'title' => '社内研修',
            'start' => '2025-09-08 09:00:00',
            'end'   => '2025-09-08 12:00:00',
            'description' => '新入社員向け「就業規則説明会」',
            'location' => '東京本社 会議室A',
        ]);

        Reservation::create([
            'title' => '顧客A 打ち合わせ',
            'start' => '2025-09-10 13:30:00',
            'end'   => '2025-09-10 15:00:00',
            'description' => '受付システム不具合について',
            'location' => '株式会社AA（神奈川）',
        ]);

        Reservation::create([
            'title' => '出張（大阪）',
            'start' => '2025-09-12 08:00:00',
            'end'   => '2025-09-13 20:00:00',
            'description' => '技術フォーラム出店',
            'location' => '大阪ホール',
        ]);
*/
    }
}
