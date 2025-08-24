<?php

namespace App\Http\Controllers\Shendy;

use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientsController extends Controller
{
    function __construct(){
        $this->middleware('permission:client-list|client-create|client-edit|client-delete', ['only' => ['index','store']]);
        $this->middleware('permission:client-create', ['only' => ['create','store']]);
        $this->middleware('permission:client-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:client-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        // resources/views/clients/index.blade.php يحتوي على @livewire('clients.index')
        return view('clients.index');
    }

    public function create()
    {
        // resources/views/clients/create.blade.php يحتوي على @livewire('clients.create')
        return view('clients.create');
    }

    public function show(Client $client)
    {
        // resources/views/clients/show.blade.php يحتوي على <livewire:clients.show :client="$client" />
        return view('clients.show', compact('client'));
    }

    public function edit($id)
    {
        return view('clients.edit', ['clientId' => $id]);
    }

   public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }
}
