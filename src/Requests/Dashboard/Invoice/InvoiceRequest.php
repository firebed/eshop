<?php

namespace Eshop\Requests\Dashboard\Invoice;

use Eshop\Models\Invoice\Client;
use Eshop\Models\Invoice\InvoiceType;
use Eshop\Models\Invoice\PaymentMethod;
use Eshop\Requests\Traits\WithRequestNotifications;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class InvoiceRequest extends FormRequest
{
    use WithRequestNotifications {
        failedValidation as traitFailedValidation;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'                => ['required', new Enum(InvoiceType::class)],
            'client_id'           => ['required', 'integer', 'exists:clients,id'],
            'payment_method'      => ['required', new Enum(PaymentMethod::class)],
            'relative_document'   => ['nullable', 'string'],
            'transaction_purpose' => ['nullable', 'string'],
            'published_at'        => ['nullable', 'date'],
            'row'                 => ['nullable', 'string'],
            'number'              => ['nullable', 'integer'],
            'rows'                => ['required', 'array'],
            'rows.*.code'         => ['required', 'string'],
            'rows.*.description'  => ['required', 'string'],
            'rows.*.unit'         => ['required', 'string'],
            'rows.*.quantity'     => ['required', 'numeric'],
            'rows.*.price'        => ['required', 'numeric'],
            'rows.*.discount'     => ['required', 'numeric', 'min:0', 'max:1'],
            'rows.*.vat_percent'  => ['required', 'numeric', 'min:0', 'max:1'],
        ];
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'type'           => 'τύπος',
            'client_id'      => 'πελάτης',
            'payment_method' => 'μέγεθος πληρωμής',
            'row'            => 'σειρά',
            'number'         => 'αριθμός',
            'rows'           => 'γραμμές',
        ]);
    }

    protected function failedValidation(Validator $validator): void
    {
        $client = Client::find($this->client_id);
        $clientName = $client ? $client->name . " ($client->vat_number)" : '';

        session()->flash('client', $clientName);

        $this->traitFailedValidation($validator);
    }
}