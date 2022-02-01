<form autocomplete="off" wire:submit.prevent="save" class="d-grid gap-4">
    <div class="row align-items-baseline">
        <label for="simplify-environment" class="col">Environment</label>
        <div class="col-9">
            <select wire:model.defer="environment" id="simplify-environment" class="form-select form-select-sm w-auto font-monospace">
                <option value="sandbox">Sandbox</option>
                <option value="live">Live</option>
            </select>
        </div>
    </div>

    <div class="vstack gap-1">
        <div class="fw-500 mb-1">Sandbox</div>
        <div class="row align-items-baseline">
            <label for="simplify-sandbox-public-key" class="col">Public key</label>
            <div class="col-9">
                <input wire:model.defer="sandboxPublicKey" class="form-control form-control-sm font-monospace" type="text" id="simplify-sandbox-public-key">
            </div>
        </div>

        <div class="row align-items-baseline">
            <label for="simplify-sandbox-private-key" class="col">Private key</label>
            <div class="col-9">
                <input wire:model.defer="sandboxPrivateKey" class="form-control form-control-sm" type="password" id="simplify-sandbox-private-key" autocomplete="new-password">
            </div>
        </div>
    </div>

    <div class="vstack gap-1">
        <div class="fw-500 mb-1">Live</div>
        <div class="row align-items-baseline">
            <label for="simplify-live-public-key" class="col">Public key</label>
            <div class="col-9">
                <input wire:model.defer="livePublicKey" class="form-control form-control-sm font-monospace" type="text" id="simplify-live-public-key">
            </div>
        </div>

        <div class="row align-items-baseline">
            <label for="simplify-live-private-key" class="col">Private key</label>
            <div class="col-9">
                <input wire:model.defer="livePrivateKey" class="form-control form-control-sm" type="password" id="simplify-live-private-key" autocomplete="new-password">
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-alt">{{ __("Save") }}</button>
    </div>
</form>