<?php

namespace App\Http\Controllers;

use App\Department;
use App\Employee;
use App\Http\Requests\ImportEmployee;
use App\ImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use SimpleXMLElement;

class EmployeeController extends Controller
{
    private $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

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

    public function importPage()
    {
        return View::make('import');
    }

    public function import(ImportEmployee $request)
    {
        $path = $request->file('file')->store('imports');
        $xmlEmployees = new SimpleXMLElement(Storage::get($path));

        try {
            $this->importService->importXml($xmlEmployees);

            return redirect()->back()->with(['success' => 'Data imported successfully']);
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['Not valid import data']);
        }
    }
}
