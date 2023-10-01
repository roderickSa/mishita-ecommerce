<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            "San Luis", "Miraflores", "Ate", "Comas", "La molina", "San Isidro"
        ];

        for ($i = 0; $i < count($districts); $i++) {
            District::create([
                "name" => $districts[$i],
                "status" => 1
            ]);
        }
    }
}
