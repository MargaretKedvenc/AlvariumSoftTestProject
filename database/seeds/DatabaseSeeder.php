<?php

use App\Department;
use App\Employee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        factory(Department::class, 5)->create();
        factory(Employee::class, 50)->create();
    }
}
