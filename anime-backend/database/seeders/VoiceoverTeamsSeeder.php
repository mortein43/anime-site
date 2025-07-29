<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use AnimeSite\Models\VoiceoverTeam;

class VoiceoverTeamsSeeder extends Seeder
{
    public function run()
    {
        VoiceoverTeam::factory(20)->create();
    }
}
