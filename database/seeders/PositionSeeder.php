<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    private $positions = ["Programmer", "Designer", "Manager"];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        foreach ($this->positions as $position) {
//            Branch::factory()->create([
//                "name" => $position
//            ]);
//        }
    }
}
