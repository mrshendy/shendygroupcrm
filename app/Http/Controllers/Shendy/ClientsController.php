<?php

namespace App\Http\Controllers\Shendy;

use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientsController extends Controller
{
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

    public function edit(Client $client)
    {
        // resources/views/clients/edit.blade.php يحتوي على <livewire:clients.edit :client="$client" />
        return view('clients.edit', compact('client'));
    }

    // Store/Update/Destroy مش لازمة هنا لو هتشتغل Livewire بالكامل
    public function store() {}
    public function update() {}
    public function destroy() {}
}
