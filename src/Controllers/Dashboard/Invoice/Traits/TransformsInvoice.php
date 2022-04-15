<?php

namespace Eshop\Controllers\Dashboard\Invoice\Traits;

use Error;
use Eshop\Models\Invoice\Invoice;
use Eshop\Models\Invoice\InvoiceRow;
use Eshop\Models\Invoice\InvoiceType;
use Eshop\Models\Invoice\PaymentMethod;
use Exception;
use Firebed\AadeMyData\Models\AddressType;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Models\Enums\IncomeClassificationCode;
use Firebed\AadeMyData\Models\Enums\InvoiceType as MyDataInvoiceTypes;
use Firebed\AadeMyData\Models\Enums\PaymentMethods as MyDataPaymentMethod;
use Firebed\AadeMyData\Models\Enums\VatExemption;
use Firebed\AadeMyData\Models\Enums\VatType;
use Firebed\AadeMyData\Models\IncomeClassificationType;
use Firebed\AadeMyData\Models\InvoiceHeaderType;
use Firebed\AadeMyData\Models\InvoiceRowType;
use Firebed\AadeMyData\Models\InvoiceSummaryType;
use Firebed\AadeMyData\Models\InvoiceType as MyDataInvoice;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethodDetailType;

trait TransformsInvoice
{
    protected function transform(Invoice $invoice): MyDataInvoice
    {
        $type = new MyDataInvoice();
        $type->setIssuer($this->getIssuer());
        $type->setCounterpart($this->getCounterpart($invoice));
        $type->setInvoiceHeader($this->getInvoiceHeader($invoice));
        $type->addPaymentMethod($this->getPaymentMethod($invoice));
        
        $vats = $invoice->rows
            ->groupBy(fn(InvoiceRow $row) => (string)$row->vat_percent)
            ->map(function ($g, $vat) {
                $total_net_value = round($g->sum(fn($r) => $r['quantity'] * round($r['price'] * (1 - $r['discount']), 4)), 2);
                return [
                    'total_net_value'  => $total_net_value,
                    'total_vat_amount' => round($total_net_value * $vat, 2)
                ];
            });

        $lineNumber = 1;
        foreach ($vats as $vat => $totals) {
            $type->addInvoiceRow(
                $this->getInvoiceRow($lineNumber++,
                    $this->parseVatType($vat),
                    $totals['total_net_value'],
                    $totals['total_vat_amount'],
                    $this->isGreekVat($invoice) ? IncomeClassificationCode::E3_561_001 : IncomeClassificationCode::E3_561_005,
                    $invoice->type === InvoiceType::TPY ? IncomeClassificationCategory::CATEGORY_1_3 : IncomeClassificationCategory::CATEGORY_1_1,
                    $vat === 0 && !$this->isGreekVat($invoice) ? VatExemption::TYPE_4 : null
                )
            );
        }

        $type->setInvoiceSummary($this->getInvoiceSummary($invoice));

        return $type;
    }

    private function parseVatType(string $vat): string
    {
        return match (round($vat, 2)) {
            0.24 => VatType::VAT_1,
            0.13 => VatType::VAT_2,
            0.06 => VatType::VAT_3,
            0.17 => VatType::VAT_4,
            0.09 => VatType::VAT_5,
            0.04 => VatType::VAT_6,
            0.00 => VatType::VAT_7,
            default => throw new Error("Μη αποδεκτή τιμή Φ.Π.Α $vat")
        };
    }

    private function getIssuer(): Issuer
    {
        $issuer = new Issuer();
        $issuer->setVatNumber(api_key('MYDATA_ISSUER_VAT', ''));
        $issuer->setCountry(api_key('MYDATA_ISSUER_COUNTRY', 'GR'));
        $issuer->setBranch(api_key('MYDATA_ISSUER_BRANCH', 0));
        return $issuer;
    }

    private function getCounterpart(Invoice $invoice): Counterpart
    {
        $client = $invoice->client;

        $counterpart = new Counterpart();
        $counterpart->setVatNumber($client->vat_number);
        $counterpart->setCountry($client->country);
        $counterpart->setBranch(0);

        if (!$this->isGreekVat($invoice)) {
            $counterpart->setName($client->name);

            $address = new AddressType();
            $address->setStreet($client->street);
            $address->setNumber($client->street_number ?: 0);
            $address->setPostalCode($client->postcode);
            $address->setCity($client->city);
            $counterpart->setAddress($address);
        }

        return $counterpart;
    }

    private function getInvoiceHeader(Invoice $invoice): InvoiceHeaderType
    {
        $header = new InvoiceHeaderType();
        $header->setSeries($invoice->row ?: 0);
        $header->setAa($invoice->number);
        $header->setIssueDate($invoice->published_at->format('Y-m-d'));
        $header->setInvoiceType($this->getInvoiceType($invoice));

        $header->setVatPaymentSuspension(false);
        $header->setCurrency('EUR');
        return $header;
    }

    /**
     * @throws Exception
     */
    private function getInvoiceType(Invoice $invoice): string
    {
        return match ($invoice->type) {
            InvoiceType::TPA => $this->isGreekVat($invoice) ? MyDataInvoiceTypes::TYPE_1_1 : MyDataInvoiceTypes::TYPE_1_2,
            InvoiceType::TPY => $this->isGreekVat($invoice) ? MyDataInvoiceTypes::TYPE_2_1 : MyDataInvoiceTypes::TYPE_2_2,
            InvoiceType::PT  => MyDataInvoiceTypes::TYPE_5_2,
            InvoiceType::PRO => throw new Error('Το προτιμολόγιο δεν μπορεί να σταλεί στο myDATA.'),
        };
    }

    private function getPaymentMethod(Invoice $invoice): PaymentMethodDetailType
    {
        $paymentMethod = new PaymentMethodDetailType();
        $paymentMethod->setType(match ($invoice->payment_method) {
            PaymentMethod::PayPal,
            PaymentMethod::WireTransfer,
            PaymentMethod::CreditCard,
            PaymentMethod::POD,
            PaymentMethod::Cash   => MyDataPaymentMethod::METHOD_3,

            PaymentMethod::Credit => MyDataPaymentMethod::METHOD_5,
            PaymentMethod::Check  => MyDataPaymentMethod::METHOD_4,
        });
        $paymentMethod->setAmount($invoice->total);
        return $paymentMethod;
    }

    private function getInvoiceRow(int $lineNumber, string $vatType, $total_net_value, $total_vat_amount, string $classificationType, string $classificationCategory, ?string $vatException = null): InvoiceRowType
    {
        $invoiceRow = new InvoiceRowType();
        $invoiceRow->setLineNumber($lineNumber);
        $invoiceRow->setNetValue($total_net_value);
        $invoiceRow->setVatCategory($vatType);
        $invoiceRow->setVatAmount($total_vat_amount);

        if (filled($vatException)) {
            $invoiceRow->setVatExemptionCategory($vatException);
        }

        $icls = new IncomeClassificationType();
        $icls->setClassificationType($classificationType);
        $icls->setClassificationCategory($classificationCategory);
        $icls->setAmount($total_net_value);
        $invoiceRow->addIncomeClassification($icls);
        return $invoiceRow;
    }

    private function getInvoiceSummary(Invoice $invoice): InvoiceSummaryType
    {
        $invoiceSummary = new InvoiceSummaryType();
        $invoiceSummary->setTotalNetValue($invoice->total_net_value);
        $invoiceSummary->setTotalVatAmount($invoice->total_vat_amount);
        $invoiceSummary->setTotalWithheldAmount(0);
        $invoiceSummary->setTotalFeesAmount(0);
        $invoiceSummary->setTotalStampDutyAmount(0);
        $invoiceSummary->setTotalOtherTaxesAmount(0);
        $invoiceSummary->setTotalDeductionsAmount(0);
        $invoiceSummary->setTotalGrossValue($invoice->total);

        $icls = new IncomeClassificationType();
        $icls->setClassificationType($this->isGreekVat($invoice)
            ? IncomeClassificationCode::E3_561_001
            : IncomeClassificationCode::E3_561_005
        );

        $icls->setClassificationCategory($invoice->type === InvoiceType::TPY
            ? IncomeClassificationCategory::CATEGORY_1_3
            : IncomeClassificationCategory::CATEGORY_1_1
        );
        $icls->setAmount($invoice->total_net_value);
        $invoiceSummary->addIncomeClassification($icls);

        return $invoiceSummary;
    }

    private function isGreekVat(Invoice $invoice): bool
    {
        return $invoice->client->country === 'GR';
    }
}