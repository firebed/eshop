<?php

namespace Eshop\Controllers\Dashboard\Invoice;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Invoice\Client;
use Eshop\Requests\Dashboard\Invoice\ClientRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    public function index(): Renderable
    {
        $clients = Client::orderBy('name')->paginate();

        return $this->view('client.index', compact('clients'));
    }

    public function create(): Renderable
    {
        return $this->view('client.create');
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        Client::create($request->validated());
        
        if ($request->filled('redirect_to')) {
            return redirect()->to($request->input('redirect_to'));
        }
        
        return redirect()->route('clients.index');
    }
}