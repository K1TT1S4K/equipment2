<?php

namespace Database\Seeders;

use App\Models\Title;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function Livewire\Volt\title;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    //  protected static ?string $password;

    
     
    // public function run(): void
    // {
    //     // User::factory(10)->create();

    //     User::factory()->create([
    //         'username' => "fakeing111",
    //         'prefix_id' => null,
    //         'firstname' => 'nrk',
    //         'lastname' => 'ng',
    //         'user_type' => 'admin',
    //         'email' => 'test@example.com',
    //         'email_verified_at' => now(),
    //         'password' => static::$password ??= Hash::make('password'),
    //         'remember_token' => Str::random(10),
    //     ]);
    // }


    public function run(): void
    {
        $this->call(
            [
                // PrefixSeeder::class,
                // UserSeeder::class,
                // TitleSeeder::class,
                // DocumentSeeder::class

                LocationSeeder::class,
                TitleSeeder::class,
                PrefixSeeder::class,
                Disposal_doc_placeholderSeeder::class,
                Equipment_transfer_doc_placeholderSeeder::class,
                Equipment_unitSeeder::class,
                DocumentSeeder::class,
                Equipment_typeSeeder::class,
                UserSeeder::class,
                EquipmentSeeder::class,
                Equipment_documentSeeder::class
            ]
        );
    }
}
