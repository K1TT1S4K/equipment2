<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    protected static ?string $password;

    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'Sakchan',
                'prefix_id' => 8,
                'firstname' => 'ศักดิ์ชาญ',
                'lastname' => 'เหลืองมณีโรจน์',
                'user_type' => 'ผู้ดูแลระบบ',
                'email' => 'sakchan@example.com',
                'email_verified_at' => now(),
                'password' => static::$password ??= Hash::make('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'username' => 'Suchetta',
                'prefix_id' => 1,
                'firstname' => 'สุเชษฐา',
                'lastname' => 'พรหมประเสริฐ',
                'user_type' => 'เจ้าหน้าที่สาขา',
                'email' => 'suchetta@example.com',
                'email_verified_at' => now(),
                'password' => static::$password ??= Hash::make('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'username' => 'Suhunsa',
                'prefix_id' => 2,
                'firstname' => 'สุหรรษา',
                'lastname' => 'ใจหนึ่ง',
                'user_type' => 'ผู้ปฏิบัติงานบริหาร',
                'email' => 'suhunsa@example.com',
                'email_verified_at' => now(),
                'password' => static::$password ??= Hash::make('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'username' => 'Boonlueo',
                'prefix_id' => 4,
                'firstname' => 'บุญเหลือ',
                'lastname' => 'นาบำรุง',
                'user_type' => 'อาจารย์',
                'email' => 'boonlueo@example.com',
                'email_verified_at' => now(),
                'password' => static::$password ??= Hash::make('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'username' => 'Narasuk',
                'prefix_id' => 7,
                'firstname' => 'นราศักดิ์',
                'lastname' => 'วงษ์วาสน์',
                'user_type' => 'อาจารย์',
                'email' => 'narasuk@example.com',
                'email_verified_at' => now(),
                'password' => static::$password ??= Hash::make('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'username' => 'user1',
                'prefix_id' => 1,
                'firstname' => 'กิตติศักดิ์',
                'lastname' => 'ผาทอง',
                'user_type' => 'ผู้ดูแลระบบ',
                'email' => 'test@example.com',
                'email_verified_at' => now(),
                'password' => static::$password ??= Hash::make('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'username' => 'user2',
                'prefix_id' => 1,
                'firstname' => 'ทดสอบ',
                'lastname' => 'สอง',
                'user_type' => 'อาจารย์',
                'email' => 'test2@example.com',
                'email_verified_at' => now(),
                'password' => static::$password ??= Hash::make('password'),
                'remember_token' => Str::random(10),
            ],
        ]);
    }
}
