<?php

namespace App;

use Illuminate\Support\Facades\DB;
use SimpleXMLElement;

class ImportService
{
    public function importXml(SimpleXMLElement $xmlEmployees)
    {
        DB::beginTransaction();

        try {
            foreach ($xmlEmployees as $employee) {
                $department = Department::firstOrCreate(['name' => trim($employee->department)]);

                Employee::create([
                    'full_name' => trim($employee->fullName),
                    'birth_date' => trim($employee->birthDate),
                    'department_id' => $department->id,
                    'position' => trim($employee->position),
                    'employment_type' => trim($employee->employmentType),
                    'work_hours' => (float) $employee->workHours,
                    'rate' => (float) $employee->rate,
                    'month_payment' => (float) $employee->monthPayment,
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();
    }
}
