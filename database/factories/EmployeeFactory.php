<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "full_name" => $this->faker->name,
            "description" => $this->faker->text,
            "department_id" => Company::factory(),
            "position_id" => Branch::factory()
        ];
    }
}
