<?php

namespace App\Http\Controllers\Shendy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
      function __construct(){
        $this->middleware('permission:employee-list|employee-create|employee-edit|employee-delete', ['only' => ['index','store']]);
        $this->middleware('permission:employee-create', ['only' => ['create','store']]);
        $this->middleware('permission:employee-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:employee-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        return view('employees.index');
    }
  public function leaves()
{
    return view('employees.leaves.index');
}

public function salaries()
    {
        // Logic to check attendance for the employee with the given ID
        return view('employees.salaries.index');
    }

    public function shifts()
    {
        return view('shifts.manage');
    }
    public function create()
    {
        return view('employees.create');
    }
    public function createLeave()
    {
        return view('employees.leaves.create');
    }
    public function leaveBalances()
    {
        return view('leave-balances.manage');
    }
    public function store(Request $request)
    {

    }
    public function attendanceCheck()
    {
        // Logic to check attendance for the employee with the given ID
        return view('attendance.check');
    }
    public function attendanceManage()
    {
        // Logic to check attendance for the employee with the given ID
        return view('attendance.manage');
    }

    public function show($id)
    {
        return view('employees.show', compact('id'));
    }

    public function editattendance($id)
    {
        return view('attendance.edit', compact('id'));
    }
public function edit($id)
    {
        return view('employees.edit', compact('id'));
    }
    public function editSalary($id)
    {
        return view('employees.salaries.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {

    }
  
    public function destroy($id)
    {

    }


}
