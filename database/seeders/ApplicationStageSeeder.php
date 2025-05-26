<?php

namespace Database\Seeders;

use App\Models\ApplicationStage;
use Illuminate\Database\Seeder;

class ApplicationStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            [
                'name' => 'Stage 1: Eligibility',
                'description' => 'Check if you are eligible for the Global Talent Visa',
                'order' => 1,
            ],
            [
                'name' => 'Stage 2: Endorsement',
                'description' => 'Apply for endorsement from Tech Nation',
                'order' => 2,
            ],
            [
                'name' => 'Stage 3: Visa Application',
                'description' => 'Apply for the Global Talent Visa',
                'order' => 3,
            ],
            [
                'name' => 'Stage 4: After Decision',
                'description' => 'What to do after you receive a decision',
                'order' => 4,
            ],
        ];

        foreach ($stages as $stage) {
            ApplicationStage::create($stage);
        }
    }
} 