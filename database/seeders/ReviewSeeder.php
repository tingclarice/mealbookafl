<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('reviews')->insert([
            [
                'user_id' => 1, 'meal_id' => 1, 'rate' => 5,
                'message' => 'Nasi gorengnya juara! Bumbunya pas, porsinya banyak, dan telurnya setengah matang sempurna. Pasti pesan lagi!',
                'created_at' => $now->copy()->subDays(2), 'updated_at' => $now->copy()->subDays(2),
            ],
            [
                'user_id' => 2, 'meal_id' => 1, 'rate' => 4,
                'message' => 'Enak banget, tapi kemarin saya minta pedas, datangnya kurang pedas. Tapi overall rasanya tetap mantap.',
                'created_at' => $now->copy()->subDays(1), 'updated_at' => $now->copy()->subDays(1),
            ],
            [
                'user_id' => 1, 'meal_id' => 4, 'rate' => 5,
                'message' => 'Bumbu satenya medok dan dagingnya empuk. Lontongnya juga pas. Recommended!',
                'created_at' => $now->copy()->subHours(5), 'updated_at' => $now->copy()->subHours(5),
            ],
            [
                'user_id' => 3, 'meal_id' => 2, 'rate' => 3,
                'message' => 'Mie ayamnya biasa aja sih, kuahnya kurang gurih. Baksonya oke.',
                'created_at' => $now->copy()->subDays(3), 'updated_at' => $now->copy()->subDays(3),
            ],
            [
                'user_id' => 4, 'meal_id' => 7, 'rate' => 5,
                'message' => 'Es tehnya segar, manisnya pas, tidak berlebihan.',
                'created_at' => $now->copy()->subMinutes(30), 'updated_at' => $now->copy()->subMinutes(30),
            ],
            [
                'user_id' => 5, 'meal_id' => 3, 'rate' => 5,
                'message' => 'Ayam Bakarnya ajiib! Bumbunya meresap sampai ke tulang, sambelnya juga nendang.',
                'created_at' => $now->copy()->subDays(4), 'updated_at' => $now->copy()->subDays(4),
            ],
            [
                'user_id' => 6, 'meal_id' => 5, 'rate' => 4,
                'message' => 'Pisang gorengnya krispi di luar, lembut di dalam. Cocok buat teman ngopi sore.',
                'created_at' => $now->copy()->subHours(8), 'updated_at' => $now->copy()->subHours(8),
            ],
            [
                'user_id' => 7, 'meal_id' => 8, 'rate' => 5,
                'message' => 'Es Jeruk peras asli, bukan sirup. Seger banget diminum pas Surabaya lagi panas-panasnya.',
                'created_at' => $now->copy()->subDays(5), 'updated_at' => $now->copy()->subDays(5),
            ],
            [
                'user_id' => 2, 'meal_id' => 4, 'rate' => 4,
                'message' => 'Sate ayamnya enak, tapi porsinya agak kurang banyak buat saya. Mungkin lain kali pesan 2 porsi.',
                'created_at' => $now->copy()->subDays(6), 'updated_at' => $now->copy()->subDays(6),
            ],
            [
                'user_id' => 8, 'meal_id' => 9, 'rate' => 4,
                'message' => 'Kopinya mantap, pekat dan aromanya kuat. Harganya juga terjangkau.',
                'created_at' => $now->copy()->subHours(12), 'updated_at' => $now->copy()->subHours(12),
            ],
            [
                'user_id' => 9, 'meal_id' => 6, 'rate' => 3,
                'message' => 'Isian risolesnya enak, tapi kulitnya agak terlalu tebal. Overall lumayan.',
                'created_at' => $now->copy()->subDays(7), 'updated_at' => $now->copy()->subDays(7),
            ],
            [
                'user_id' => 10, 'meal_id' => 1, 'rate' => 5,
                'message' => 'Nasi goreng terenak yang pernah saya coba di aplikasi ini. Pengirimannya juga cepat.',
                'created_at' => $now->copy()->subDays(8), 'updated_at' => $now->copy()->subDays(8),
            ],
            [
                'user_id' => 3, 'meal_id' => 7, 'rate' => 4,
                'message' => 'Oke lah es tehnya, standar.',
                'created_at' => $now->copy()->subMinutes(90), 'updated_at' => $now->copy()->subMinutes(90),
            ],
            [
                'user_id' => 1, 'meal_id' => 2, 'rate' => 4,
                'message' => 'Mie gorengnya pas, tidak terlalu berminyak. Sayurannya juga fresh.',
                'created_at' => $now->copy()->subDays(10), 'updated_at' => $now->copy()->subDays(10),
            ],
            [
                'user_id' => 4, 'meal_id' => 3, 'rate' => 5,
                'message' => 'Daging ayamnya empuk, bumbunya berasa banget. Wajib coba!',
                'created_at' => $now->copy()->subWeeks(2), 'updated_at' => $now->copy()->subWeeks(2),
            ],
            [
                'user_id' => 5, 'meal_id' => 5, 'rate' => 5,
                'message' => 'Jadi langganan beli pisang goreng di sini. Selalu panas dan renyah.',
                'created_at' => $now->copy()->subDays(12), 'updated_at' => $now->copy()->subDays(12),
            ],
            [
                'user_id' => 6, 'meal_id' => 8, 'rate' => 4,
                'message' => 'Manis dan asamnya pas, es batunya juga banyak. Segar!',
                'created_at' => $now->copy()->subDays(14), 'updated_at' => $now->copy()->subDays(14),
            ],
            [
                'user_id' => 7, 'meal_id' => 9, 'rate' => 3,
                'message' => 'Kopinya terlalu pahit buat selera saya, mungkin harus tambah gula lagi.',
                'created_at' => $now->copy()->subHours(20), 'updated_at' => $now->copy()->subHours(20),
            ],
            [
                'user_id' => 8, 'meal_id' => 2, 'rate' => 5,
                'message' => 'Porsi mie gorengnya jumbo, bisa buat berdua. Rasanya juga otentik.',
                'created_at' => $now->copy()->subDays(15), 'updated_at' => $now->copy()->subDays(15),
            ],
            [
                'user_id' => 9, 'meal_id' => 1, 'rate' => 4,
                'message' => 'Request telur ceplok dan dikabulkan. Pelayanan mantap, rasa oke.',
                'created_at' => $now->copy()->subDays(16), 'updated_at' => $now->copy()->subDays(16),
            ],
            [
                'user_id' => 10, 'meal_id' => 4, 'rate' => 5,
                'message' => 'Ini baru sate! Dagingnya full, bukan lemak doang. Saus kacangnya top.',
                'created_at' => $now->copy()->subDays(18), 'updated_at' => $now->copy()->subDays(18),
            ],
            [
                'user_id' => 1, 'meal_id' => 6, 'rate' => 4,
                'message' => 'Risolesnya enak buat ganjel perut. Isinya padat.',
                'created_at' => $now->copy()->subWeeks(3), 'updated_at' => $now->copy()->subWeeks(3),
            ],
            [
                'user_id' => 2, 'meal_id' => 3, 'rate' => 4,
                'message' => 'Sambalnya juara, tapi ayamnya sedikit kecil ukurannya.',
                'created_at' => $now->copy()->subDays(20), 'updated_at' => $now->copy()->subDays(20),
            ],
            [
                'user_id' => 3, 'meal_id' => 5, 'rate' => 5,
                'message' => 'Manisnya pas, ga bikin eneg. Order 3 porsi langsung habis.',
                'created_at' => $now->copy()->subDays(22), 'updated_at' => $now->copy()->subDays(22),
            ],
            [
                'user_id' => 4, 'meal_id' => 8, 'rate' => 5,
                'message' => 'Selalu pesan es jeruk kalau makan di sini. Gak pernah ngecewain.',
                'created_at' => $now->copy()->subDays(25), 'updated_at' => $now->copy()->subDays(25),
            ],
            [
                'user_id' => 5, 'meal_id' => 7, 'rate' => 4,
                'message' => 'Tehnya wangi, bukan teh biasa.',
                'created_at' => $now->copy()->subHours(48), 'updated_at' => $now->copy()->subHours(48),
            ],
            [
                'user_id' => 6, 'meal_id' => 9, 'rate' => 5,
                'message' => 'Bagi pecinta kopi hitam, wajib coba kopi di sini. Nendang banget!',
                'created_at' => $now->copy()->subDays(28), 'updated_at' => $now->copy()->subDays(28),
            ],
            [
                'user_id' => 7, 'meal_id' => 1, 'rate' => 5,
                'message' => 'Sudah 5x pesan dan rasanya konsisten enak. The best Nasi Goreng!',
                'created_at' => $now->copy()->subWeeks(4), 'updated_at' => $now->copy()->subWeeks(4),
            ],
            [
                'user_id' => 8, 'meal_id' => 4, 'rate' => 4,
                'message' => 'Bumbu kacangnya legit. Next time mau coba sate kambingnya kalau ada.',
                'created_at' => $now->copy()->subDays(30), 'updated_at' => $now->copy()->subDays(30),
            ],
        ]);
    }
}