<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    protected $departments = ["IT", "Marketing", "Accounting"];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        foreach ($this->departments as $department) {
//            Company::factory()->has(Employee::factory()->count(5))->create([
//                "name" => $department
//            ]);
//        }
    }
}
