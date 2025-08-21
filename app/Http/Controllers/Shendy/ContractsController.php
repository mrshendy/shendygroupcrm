<?php

namespace App\Http\Controllers\Shendy;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractsController extends Controller
{
      function __construct(){
        $this->middleware('permission:contract-list|client-create|client-edit|client-delete', ['only' => ['index','store']]);
        $this->middleware('permission:contract-create', ['only' => ['create','store']]);
        $this->middleware('permission:contract-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:contract-delete', ['only' => ['destroy']]);
    }
    public function index()
    {

        return view('contracts.index');

    }

    public function create()
    {
        return view('contracts.create');
    }

    public function store(Request $request)
    {

    }
    public function show(Contract $contract)
    {
        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        return view('contracts.edit', compact('contract'));
    }

    public function update(Request $request, Contract $contract)
    {

    }
    public function destroy(Contract $contract)
    {

    }
}
