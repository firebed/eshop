<?php

namespace Eshop\Livewire\Dashboard\User;

use Eshop\Exports\UsersExport;
use Eshop\Models\User;
use Firebed\Components\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\Datatable\WithExports;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\Datatable\WithSorting;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShowUsers extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use SendsNotifications;
    use WithSelections;
    use WithCRUD;
    use DeletesRows;
    use WithExports;
    use WithCRUD;
    use WithSorting {
        queryString as sortingQueryString;
    }

    public string $search = "";

    protected function rules(): array
    {
        return [
            'model.first_name' => ['required', 'string'],
            'model.last_name'  => ['required', 'string'],
            'model.email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($this->model)],
            'model.password'   => ['required', 'string']
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function queryString(): array
    {
        return array_merge([
            'search' => ['except' => ''],
        ], $this->sortingQueryString());
    }

    protected function deleteRows(): ?int
    {
        return User::query()->whereKey($this->selected())->delete();
    }

    protected function makeEmptyModel(): User
    {
        return new User();
    }

    protected function findModel($id): Collection
    {
        return User::find($id);
    }

    protected function getModels(): Collection
    {
        return $this->users->getCollection();
    }

    public function export(): null|BinaryFileResponse
    {
        $export = new UsersExport($this->selected());
        return Excel::download($export, 'Users-' . now()->timestamp . '.xlsx');
    }

    public function getUsersProperty(): LengthAwarePaginator
    {
        return User::query()
            ->withCount(['carts' => fn($q) => $q->submitted()])
            ->withSum(['carts' => fn($q) => $q->submitted()], 'total')
            ->when($this->search, fn($q, $v) => $q->matchAgainst($v))
            ->when($this->sortField, function ($q, $v) {
                switch ($v) {
                    case 'name':
                        $q->orderBy('first_name', $this->sortDirection)
                            ->orderBy('last_name', $this->sortDirection);
                        break;
                    case 'email':
                    case 'created_at':
                    case 'last_login_at':
                    case 'carts_count':
                    case 'carts_sum_total':
                        $q->orderBy($v, $this->sortDirection);
                        break;
                }
            })
            ->paginate();
    }

    public function render(): View
    {
        return view('eshop::dashboard.user.wire.show-users', [
            'users' => $this->users
        ]);
    }
}
