<?php

namespace Eshop\Controllers\Dashboard\Product;

use Eshop\Controllers\Controller;
use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Models\Product\Product;
use Eshop\Requests\Dashboard\Product\MassVariantPropertyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class MassVariantPropertyController extends Controller
{
    use WithNotifications;

    public function __invoke(MassVariantPropertyRequest $request): RedirectResponse
    {
        $property = $request->input('property');
        $data = array_combine($request->input('ids'), $request->input('values'));
        $distinct = array_unique($data);

        try {
            DB::transaction(function () use ($property, $data, $distinct) {
                foreach ($distinct as $value) {
                    Product::whereKey(array_keys($data, $value))->update([
                        $property => $property === 'discount' ? $value / 100 : $value
                    ]);
                }
            });

            $count = count($data);
            $this->showSuccessNotification(trans_choice("eshop::variant.notifications.{$property}_updated", $count, ['number' => $count]));
        } catch (Throwable) {
            $this->showErrorNotification(trans('eshop::variant.notifications.error'));
        }

        $request->flashOnly('ids');
        return back();
    }
}
