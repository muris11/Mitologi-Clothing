<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        TeamMember::truncate();

        $founder = TeamMember::create([
            'name' => 'Rizky Rafalda Oktaviandri',
            'position' => 'Founder Mitologi Clothing',
            'photo' => 'team/founder.jpg',
            'level' => 0,
            'sort_order' => 0,
        ]);

        $aisatuzzahro = TeamMember::create([
            'name' => 'Aisatuzzahro Fuadi',
            'position' => 'Sewing',
            'photo' => 'team/aisatuzzahro.jpg',
            'parent_id' => $founder->id,
            'level' => 1,
            'sort_order' => 0,
        ]);

        $irpan = TeamMember::create([
            'name' => 'Irpan Faddil',
            'position' => 'Freelance Designer Grafis',
            'photo' => 'team/irpan.jpg',
            'parent_id' => $founder->id,
            'level' => 1,
            'sort_order' => 1,
        ]);

        $sanu = TeamMember::create([
            'name' => 'Sanu Senja',
            'position' => 'Freelance Pointilist',
            'photo' => 'team/sanu.jpg',
            'parent_id' => $founder->id,
            'level' => 1,
            'sort_order' => 2,
        ]);

        $rizkyR = TeamMember::create([
            'name' => 'Rizky Rafalda',
            'position' => 'Produksi Sablon',
            'photo' => 'team/rizky-sablon.jpg',
            'parent_id' => $founder->id,
            'level' => 1,
            'sort_order' => 3,
        ]);

        TeamMember::create([
            'name' => 'Nino',
            'position' => 'Cutting Tshirt',
            'photo' => 'team/nino-cutting.jpg',
            'parent_id' => $aisatuzzahro->id,
            'level' => 2,
            'sort_order' => 0,
        ]);

        $honobuka = TeamMember::create([
            'name' => 'Honobuka Studio',
            'position' => 'Spot Color',
            'photo' => 'team/honobuka.jpg',
            'parent_id' => $irpan->id,
            'level' => 2,
            'sort_order' => 0,
        ]);

        TeamMember::create([
            'name' => 'Hadistorsi',
            'position' => 'Senior Data Analyst',
            'photo' => 'team/hadistorsi.jpg',
            'parent_id' => $sanu->id,
            'level' => 2,
            'sort_order' => 0,
        ]);

        TeamMember::create([
            'name' => 'Nino Sablon',
            'position' => 'Asistant Sablon',
            'photo' => 'team/nino-sablon.jpg',
            'parent_id' => $rizkyR->id,
            'level' => 2,
            'sort_order' => 0,
        ]);

        TeamMember::create([
            'name' => 'Ralema Studio',
            'position' => 'Setting Jersey',
            'photo' => 'team/ralema.jpg',
            'parent_id' => $honobuka->id,
            'level' => 3,
            'sort_order' => 0,
        ]);
    }
}
