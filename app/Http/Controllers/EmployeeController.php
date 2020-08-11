<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        return View::make('employee', [
            'employees' => Employee::paginate($request->get('perPage', 10)),
        ]);
    }
}
