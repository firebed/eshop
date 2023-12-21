<?php

namespace Eshop\Controllers\Dashboard\Invoice\Traits;

use Error;
use Eshop\Models\Invoice\Invoice;
use Eshop\Models\Invoice\InvoiceRow;
use Eshop\Models\Invoice\InvoiceType;
use Eshop\Models\Invoice\PaymentMethod;
use Exception;
use Firebed\AadeMyData\Enums\IncomeClassificationCategory;
use Firebed\AadeMyData\Enums\IncomeClassificationType;
use Firebed\AadeMyData\Enums\InvoiceType as MyDataInvoiceTypes;
use Firebed\AadeMyData\Enums\PaymentMethod as MyDataPaymentMethod;
use Firebed\AadeMyData\Enums\VatCategory;
use Firebed\AadeMyData\Enums\VatExemption;
use Firebed\AadeMyData\Models\Address;
use Firebed\AadeMyData\Models\Counterpart;
use Firebed\AadeMyData\Models\IncomeClassification;
use Firebed\AadeMyData\Models\Invoice as MyDataInvoice;
use Firebed\AadeMyData\Models\InvoiceDetails;
use Firebed\AadeMyData\Models\InvoiceHeader;
use Firebed\AadeMyData\Models\InvoiceSummary;
use Firebed\AadeMyData\Models\Issuer;
use Firebed\AadeMyData\Models\PaymentMethodDetail;

trait TransformsInvoice
{
    /**
     * @throws Exception
     */
    protected function transform(Invoice $invoice): MyDataInvoice
    {
        $type = new MyDataInvoice();
        $type->setIssuer($this->getIssuer());

        // Counterpart is not necessary for retail invoices
        if ($invoice->type !== InvoiceType::PSL) { // Πιστωτικό στοιχείο λιανικής
            $type->setCounterpart($this->getCounterpart($invoice));
        }

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

        $cType = $this->isGreekVat($invoice)
            ? ($invoice->type !== InvoiceType::PSL ? IncomeClassificationType::E3_561_001 : IncomeClassificationType::E3_561_003)
            : IncomeClassificationType::E3_561_005;

        $cCategory = $invoice->type === InvoiceType::TPY
            ? IncomeClassificationCategory::CATEGORY_1_3
            : IncomeClassificationCategory::CATEGORY_1_1;

        $lineNumber = 1;
        foreach ($vats as $vat => $totals) {
            $type->addInvoiceDetails(
                $this->getInvoiceRow(
                    $lineNumber++,
                    $this->parseVatType($vat),
                    $totals['total_net_value'],
                    $totals['total_vat_amount'],
                    $cType->value,
                    $cCategory->value,
                    $vat === 0 && !$this->isGreekVat($invoice) ? VatExemption::TYPE_14->value : null
                )
            );
        }

        $type->setInvoiceSummary($this->getInvoiceSummary($invoice));

        return $type;
    }

    private function parseVatType(string $vat): string
    {
        return match (round($vat, 2)) {
            0.24    => VatCategory::VAT_1->value,
            0.13    => VatCategory::VAT_2->value,
            0.06    => VatCategory::VAT_3->value,
            0.17    => VatCategory::VAT_4->value,
            0.09    => VatCategory::VAT_5->value,
            0.04    => VatCategory::VAT_6->value,
            0.00    => VatCategory::VAT_7->value,
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

            $address = new Address();
            $address->setStreet($client->street);
            $address->setNumber($client->street_number ?: 0);
            $address->setPostalCode($client->postcode);
            $address->setCity($client->city);
            $counterpart->setAddress($address);
        }

        return $counterpart;
    }

    /**
     * @throws Exception
     */
    private function getInvoiceHeader(Invoice $invoice): InvoiceHeader
    {
        $header = new InvoiceHeader();
        $header->setSeries($invoice->row ?: 0);
        $header->setAa($invoice->number);
        $header->setIssueDate($invoice->published_at->format('Y-m-d'));
        $header->setInvoiceType($this->getInvoiceType($invoice)->value);

        $header->setVatPaymentSuspension(false);
        $header->setCurrency('EUR');
        return $header;
    }

    /**
     * @throws Exception
     */
    private function getInvoiceType(Invoice $invoice): MyDataInvoiceTypes
    {
        return match ($invoice->type) {
            InvoiceType::TPA                  => $this->isGreekVat($invoice) ? MyDataInvoiceTypes::TYPE_1_1 : MyDataInvoiceTypes::TYPE_1_2,
            InvoiceType::TPY                  => $this->isGreekVat($invoice) ? MyDataInvoiceTypes::TYPE_2_1 : MyDataInvoiceTypes::TYPE_2_2,
            InvoiceType::PT                   => MyDataInvoiceTypes::TYPE_5_2,
            InvoiceType::PSL                  => MyDataInvoiceTypes::TYPE_11_4,
            InvoiceType::TPA_INTRA            => MyDataInvoiceTypes::TYPE_1_2,
            InvoiceType::PRO, InvoiceType::DA => throw new Error('Δεν μπορεί να σταλεί στο myDATA.'),
        };
    }

    private function getPaymentMethod(Invoice $invoice): PaymentMethodDetail
    {
        $paymentMethod = new PaymentMethodDetail();
        $paymentMethod->setType(
            match ($invoice->payment_method) {
                PaymentMethod::POD,
                PaymentMethod::Cash   => MyDataPaymentMethod::METHOD_3,

                PaymentMethod::PayPal,
                PaymentMethod::WireTransfer => MyDataPaymentMethod::METHOD_6,

                PaymentMethod::CreditCard => MyDataPaymentMethod::METHOD_7,

                PaymentMethod::Credit => MyDataPaymentMethod::METHOD_5,
                PaymentMethod::Check  => MyDataPaymentMethod::METHOD_4,
            }
        );
        $paymentMethod->setAmount($invoice->total);
        return $paymentMethod;
    }

    private function getInvoiceRow(int $lineNumber, string $vatType, $total_net_value, $total_vat_amount, string $classificationType, string $classificationCategory, ?string $vatException = null): InvoiceDetails
    {
        $invoiceRow = new InvoiceDetails();
        $invoiceRow->setLineNumber($lineNumber);
        $invoiceRow->setNetValue($total_net_value);
        $invoiceRow->setVatCategory($vatType);
        $invoiceRow->setVatAmount($total_vat_amount);

        if (filled($vatException)) {
            $invoiceRow->setVatExemptionCategory($vatException);
        }

        $icls = new IncomeClassification();
        $icls->setClassificationType($classificationType);
        $icls->setClassificationCategory($classificationCategory);
        $icls->setAmount($total_net_value);
        $invoiceRow->addIncomeClassification($icls);
        return $invoiceRow;
    }

    private function getInvoiceSummary(Invoice $invoice): InvoiceSummary
    {
        $invoiceSummary = new InvoiceSummary();
        $invoiceSummary->setTotalNetValue($invoice->total_net_value);
        $invoiceSummary->setTotalVatAmount($invoice->total_vat_amount);
        $invoiceSummary->setTotalWithheldAmount(0);
        $invoiceSummary->setTotalFeesAmount(0);
        $invoiceSummary->setTotalStampDutyAmount(0);
        $invoiceSummary->setTotalOtherTaxesAmount(0);
        $invoiceSummary->setTotalDeductionsAmount(0);
        $invoiceSummary->setTotalGrossValue($invoice->total);

        $icls = new IncomeClassification();
        $icls->setClassificationType($this->isGreekVat($invoice)
                ? ($invoice->type !== InvoiceType::PSL ? IncomeClassificationType::E3_561_001 : IncomeClassificationType::E3_561_003)
                : IncomeClassificationType::E3_561_005
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