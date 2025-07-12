<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'original_name' => $this->faker->word . '.pdf',
            'stored_name' => $this->faker->uuid . '.pdf',
            'document_type' => $this->faker->randomElement([
                'ยื่นแทงจำหน่ายครุภัณฑ์',
                'ยื่นขออนุมัติจัดซื้อจัดจ้าง',
                'ยื่นขออนุมัติเบิกจ่าย'
            ]),
            'date' => now()->format('Y-m-d'),
            'created_at' => now(),
            'created_by' => User::inRandomOrder()->first()?->id,
            'updated_at' => now(),
            'updated_by' => User::inRandomOrder()->first()?->id,
            'deleted_at' => null,
            'deleted_by' => null,
        ];
    }

    public function deleted()
    {
        return $this->state(function (array $attributes) {
            return [
                'deleted_at' => Carbon::now(),
                'deleted_by' => User::inRandomOrder()->first()?->id,
            ];
        });
    }
}
