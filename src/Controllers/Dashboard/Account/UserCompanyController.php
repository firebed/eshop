<?php

namespace Eshop\Controllers\Dashboard\Account;

use Eshop\Controllers\Controller;
use Eshop\Models\Invoice\Company;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Requests\Customer\UserCompanyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserCompanyController extends Controller
{
    public function index(): View
    {
        $companies = auth()->user()->companies()->with('address.country')->get();

        return view('eshop::customer.account.company.index', [
            'companies' => $companies
        ]);
    }

    public function create(): View
    {
        return view('eshop::customer.account.company.create', [
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

    public function edit(string $lang, Company $company): View
    {
        return view('eshop::customer.account.company.edit', [
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
