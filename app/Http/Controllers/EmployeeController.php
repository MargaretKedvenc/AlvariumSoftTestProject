<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::query();
        if ($departmentId = $request->get('departmentId')) {
            $employees->where('department_id', $departmentId);
        }

        $employees = $employees->paginate($request->get('perPage', 10));

        return View::make('employee', [
            'employees' => $employees,
        ]);
    }

}
