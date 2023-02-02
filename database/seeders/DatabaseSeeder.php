<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            'fullname' => 'Şifre Password',
            'password' => Hash::make('password')
        ]);

        DB::table('posts')->insert([
            'title' => 'Başlık',
            'description' => 'Bu bir açıklama yazısıdır.',
            'tag' => json_encode(['laserTag']),
            'illness' => 'hastalık ismidir',
            'subject' => 'konu başlığı',
            'filename' => '1670330960-vesikalik.jpg',
            'filepath' => 'uploads/1670330960-vesikalik.jpg',
            'mime_type' => 'image/png'
        ]);

        DB::table('contacts')->insert([
            'fullname' => 'Meeyzt',
            'phone_number' => '5433997765',
            'description' => 'Yardım etmek için iletişime geçin'
        ]);
    }
}
