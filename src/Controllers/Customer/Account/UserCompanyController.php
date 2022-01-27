<?php

namespace Eshop\Controllers\Customer\Account;

use Eshop\Controllers\Customer\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Location\Address;
use Eshop\Models\Location\Country;
use Eshop\Models\User\Company;
use Eshop\Requests\Customer\UserCompanyRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class UserCompanyController extends Controller
{
    use WithNotifications;

    public function index(): Renderable
    {
        $companies = auth()->user()?->companies()->with('address.country')->get();

        return $this->view('account.company.index', [
            'companies' => $companies
        ]);
    }

    public function create(): Renderable
    {
        return $this->view('account.company.create', [
            'countries' => Country::visible()->get()
        ]);
    }

    public function store(UserCompanyRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            auth()->user()?->companies()->save($company = new Company($request->validated()));
            $company->address()->save(new Address($request->validated()));
        });

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return redirect()
            ->route('account.companies.index', app()->getLocale());
    }

    public function edit(string $lang, Company $company): Renderable
    {
        return $this->view('account.company.edit', [
            'company'   => $company,
            'countries' => Country::visible()->get()
        ]);
    }

    public function update(UserCompanyRequest $request, string $lang, Company $company): RedirectResponse
    {
        $company->update($request->validated());
        $company->address->update($request->validated());

        $this->showSuccessNotification(__("eshop::notifications.saved"));

        return back();
    }

    public function destroy(string $lang, Company $company): RedirectResponse
    {
        $company->delete();

        $this->showSuccessNotification(__("eshop::notifications.deleted"));

        return back();
    }
}
