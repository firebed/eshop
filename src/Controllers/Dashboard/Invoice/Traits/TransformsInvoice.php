<?php

namespace Eshop\Controllers\Dashboard\Invoice\Traits;

use Eshop\Models\Invoice\Invoice;
use Eshop\Models\Invoice\InvoiceType;
use Eshop\Models\Invoice\PaymentMethod;
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
        $type->addInvoiceRow($this->getInvoiceRow($invoice));
        $type->setInvoiceSummary($this->getInvoiceSummary($invoice));

        return $type;
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

    private function getInvoiceType(Invoice $invoice): string
    {
        return match ($invoice->type) {
            InvoiceType::TPA => $this->isGreekVat($invoice) ? MyDataInvoiceTypes::TYPE_1_1 : MyDataInvoiceTypes::TYPE_1_2,
            InvoiceType::TPY => $this->isGreekVat($invoice) ? MyDataInvoiceTypes::TYPE_2_1 : MyDataInvoiceTypes::TYPE_2_2,
            InvoiceType::PT => MyDataInvoiceTypes::TYPE_5_2,
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
            PaymentMethod::Cash => MyDataPaymentMethod::METHOD_3,

            PaymentMethod::Credit => MyDataPaymentMethod::METHOD_5,
            PaymentMethod::Check => MyDataPaymentMethod::METHOD_4,
        });
        $paymentMethod->setAmount($invoice->total);
        return $paymentMethod;
    }

    private function getInvoiceRow(Invoice $invoice): InvoiceRowType
    {
        $invoiceRow = new InvoiceRowType();
        $invoiceRow->setLineNumber(1);
        $invoiceRow->setNetValue($invoice->total_net_value);
        $invoiceRow->setVatCategory($this->isGreekVat($invoice) ? VatType::VAT_1 : VatType::VAT_7);
        $invoiceRow->setVatAmount($invoice->total_vat_amount);

        if (!$this->isGreekVat($invoice)) {
            $invoiceRow->setVatExemptionCategory(VatExemption::TYPE_4);
        }

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