<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Community;
use App\Models\Program;
use App\Models\Project;
use App\Models\Kpi;

class ICDMSTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $dept = Department::firstOrCreate(
            ['code' => 'PFO'],
            [
                'name' => 'Programs & Field Operations',
                'description' => 'Core project execution department'
            ]
        );

        $community = Community::firstOrCreate(
            ['name' => 'Umueze Village', 'state' => 'Anambra State', 'lga' => 'Awka South'],
            [
                'latitude' => 6.2105,
                'longitude' => 7.0722,
                'estimated_population' => 12500
            ]
        );

        $program = Program::firstOrCreate(
            ['program_code' => 'PRG-TECH-2026'],
            [
                'title' => 'Digital Inclusion & Youth Empowerment',
                'description' => 'Targeted tech training and grant support for micro-entrepreneurs.',
                'budget' => 25000000.00,
                'start_date' => now()->subMonths(3),
                'status' => 'active'
            ]
        );

        $project = Project::firstOrCreate(
            ['project_code' => 'ICDMS-PRJ-001'],
            [
                'program_id' => $program->id,
                'community_id' => $community->id,
                'title' => 'Awka Tech Skills Acquisition Cohort A',
                'objectives' => 'Train 500 youth in web software development and web design.',
                'budget' => 5000000.00,
                'expenditure' => 1800000.00,
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(5),
                'status' => 'in_progress'
            ]
        );

        Kpi::firstOrCreate(
            ['project_id' => $project->id, 'title' => 'Youth Software Engineers Trained'],
            [
                'unit' => 'Students',
                'baseline' => 0,
                'target' => 500,
                'current' => 320,
                'frequency' => 'monthly'
            ]
        );
    }
}