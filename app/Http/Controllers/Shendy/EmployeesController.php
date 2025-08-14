<?php

namespace App\Http\Controllers\Shendy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index()
    {
        return view('employees.index');
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {
        return view('employees.show', compact('id'));
    }

    public function edit($id)
    {
        return view('employees.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }


}
