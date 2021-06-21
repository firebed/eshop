<form wire:submit.prevent="save">
    <x-bs::card>
        <x-bs::card.body>
            <div class="d-flex justify-content-end mb-4">
                <x-bs::button.primary type="submit" wire:loading.attr="disabled">{{ __("Save") }}</x-bs::button.primary>
            </div>

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @foreach($this->roles as $role)
                    <div class="col d-flex flex-column" wire:key="role-{{ $role->id }}">
                        <div class="fw-bold border-bottom pb-2 mb-2">{{ __($role->name) }}</div>

                        <div x-ref="permissions" class="d-grid gap-1">
                            @foreach($role->permissions as $permission)
                                <x-bs::input.checkbox
                                        wire:key="permission-{{ $permission->id}}"
                                        wire:model.defer="selected_permissions"
                                        value="{{ $permission->id }}"
                                        id="permission-{{ $permission->id }}">
                                    {{ __($permission->name) }}
                                </x-bs::input.checkbox>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </x-bs::card.body>
    </x-bs::card>
</form>