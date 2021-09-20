<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\UserCompanyRequest;
use Eshop\Controllers\Controller;
use Eshop\Models\Invoice\Company;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UserCompanyController extends Controller
{
    public function index(): Renderable
    {
        $companies = auth()->user()->companies()->with('address.country')->get();

        return view('account.company.index', [
            'companies' => $companies
        ]);
    }

    public function create(): Renderable
    {
        return view('account.company.create', [
            'countries' => Country::visible()->get()
        ]);
    }

    public function store(UserCompanyRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            auth()->user()->companies()->save($company = new Company($request->validated()));
            $company->address()->save(new Address($request->validated()));
        });

        return redirect()
            ->route('account.companies.index', app()->getLocale())
            ->with('success', __("The new company was saved!"));
    }

    public function edit(string $lang, Company $company): Renderable
    {
        return view('account.company.edit', [
            'company'   => $company,
            'countries' => Country::visible()->get()
        ]);
    }

    public function update(UserCompanyRequest $request, string $lang, Company $company): RedirectResponse
    {
        $company->update($request->validated());
        $company->address->update($request->validated());

        return back()->with('success', __("The company was saved"));
    }

    public function destroy(string $lang, Company $company): RedirectResponse
    {
        $company->delete();

        return back()->with('success', __("The company was deleted!"));
    }
}
