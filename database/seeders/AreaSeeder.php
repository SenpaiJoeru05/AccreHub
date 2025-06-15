<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            
            ['name' => 'Area I', 'slug' => 'area-i', 'description' => 'Vision and Mission'],
            ['name' => 'Area II', 'slug' => 'area-ii', 'description' => 'Faculty'],
            ['name' => 'Area III', 'slug' => 'area-iii', 'description' => 'Curriculum'],
            ['name' => 'Area IV - Student', 'slug' => 'area-iv-student', 'description' => 'Student-related accreditation documents'],
            ['name' => 'Area V', 'slug' => 'area-v', 'description' => 'Community Extension'],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}