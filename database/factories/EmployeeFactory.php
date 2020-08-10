<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Department;
use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    $empType = $faker->randomElement(['full-time', 'hourly-pay']);

    return [
        'full_name' => $faker->name(),
        'birth_date' => $faker->dateTime(),
        'position' => $faker->jobTitle,
        'department_id' => function () {
            return Department::orderByRaw('rand()')->first()->id;
        },
        'employment_type' => $empType,
        'work_hours' => $empType === 'hourly-pay' ? $faker->numberBetween(0, 200) : null,
        'rate' => $empType === 'hourly-pay' ? $faker->randomFloat(2, 0.5, 50) : null,
        'month_payment' => $empType === 'full-time' ? $faker->randomFloat(2, 500, 2500) : null,
    ];
});
