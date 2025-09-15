<?php

namespace Database\Seeders;

use Faker\Provider\Lorem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Equipment_transfer_doc_placeholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipment_transfer_doc_placeholder')->insert([
            [
                'government_agency' => 'text',
                'at' => 'text',
                'date' => date(now()),
                'title' => 'text',
                'dear_position' => 'text',
                'paragraph_before_table' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sapiente ullam quae consequatur, laboriosam, quas ducimus porro quod necessitatibus aut dolores aliquid doloribus consectetur voluptate voluptatem consequuntur ipsam quis accusamus itaque. Minima neque harum numquam libero, mollitia voluptas ratione dolorum tenetur deleniti similique perferendis repudiandae in et fuga provident laboriosam ex natus alias cumque ducimus sint omnis quibusdam. Ipsam accusamus atque ut sapiente nisi perspiciatis excepturi! Recusandae nisi ea autem beatae facere sapiente consectetur inventore, alias et sed quod corrupti, cumque eligendi quo aut commodi quibusdam. Eveniet suscipit ipsam, rerum, architecto animi neque, ratione aliquam quidem earum ea exercitationem optio necessitatibus.',
                'paragraph_after_table' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sapiente ullam quae consequatur, laboriosam, quas ducimus porro quod necessitatibus aut dolores aliquid doloribus consectetur voluptate voluptatem consequuntur ipsam quis accusamus itaque. Minima neque harum numquam libero, mollitia voluptas ratione dolorum tenetur deleniti similique perferendis repudiandae in et fuga provident laboriosam ex natus alias cumque ducimus sint omnis quibusdam. Ipsam accusamus atque ut sapiente nisi perspiciatis excepturi! Recusandae nisi ea autem beatae facere sapiente consectetur inventore, alias et sed quod corrupti, cumque eligendi quo aut commodi quibusdam. Eveniet suscipit ipsam, rerum, architecto animi neque, ratione aliquam quidem earum ea exercitationem optio necessitatibus.',
                'sender_name' => 'text',
                'sender_position' => 'text',
                'receiver_name' => 'text',
                'receiver_position' => 'text',
                'first_witness_name' => 'text',
                'first_witness_position' => 'text',
                'second_witness_name' => 'text',
                'second_witness_position' => 'text',
                'third_witness_name' => 'text',
                'third_witness_position' => 'text',
                'approver_name' => 'text',
                'approver_position' => 'text',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
