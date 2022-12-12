<?php

namespace Eshop\Models\Location;

use Carbon\Carbon;
use Eshop\Models\Cart\Cart;
use Eshop\Services\Acs\Http\AcsAddressValidation;
use Eshop\Services\Acs\Http\AcsCreateVoucher;
use Eshop\Services\Acs\Http\AcsDeleteVoucher;
use Eshop\Services\Acs\Http\AcsFindAreaByZipcode;
use Eshop\Services\Acs\Http\AcsIssuePickupList;
use Eshop\Services\Acs\Http\AcsPrintVoucher;
use Eshop\Services\Acs\Http\AcsTrackingDetails;
use Eshop\Services\CourierCenter\Exceptions\CourierCenterException;
use Eshop\Services\CourierCenter\Http\CourierCenterCancelVoucher;
use Eshop\Services\CourierCenter\Http\CourierCenterCreateVoucher;
use Eshop\Services\CourierCenter\Http\CourierCenterGetStations;
use Eshop\Services\CourierCenter\Http\CourierCenterPickupList;
use Eshop\Services\CourierCenter\Http\CourierCenterPrintVoucher;
use Eshop\Services\CourierCenter\Http\CourierCenterTracking;
use Eshop\Services\SpeedEx\Exceptions\SpeedExException;
use Eshop\Services\SpeedEx\Http\SpeedExCancelVoucher;
use Eshop\Services\SpeedEx\Http\SpeedExCreatePickup;
use Eshop\Services\SpeedEx\Http\SpeedExCreateVoucher;
use Eshop\Services\SpeedEx\Http\SpeedExGetBranches;
use Eshop\Services\SpeedEx\Http\SpeedExGetTraceByVoucher;
use Eshop\Services\SpeedEx\Http\SpeedExGetVoucherPdf;
use Illuminate\Support\Collection;

trait HasCourierService
{
    public function validateAddress(?string $street, ?string $street_no, ?string $region, string $postcode): Collection
    {
        return match ($this->name) {
            'ACS Courier' => (new AcsAddressValidation())->handle($street, $street_no, $region, $postcode),
            default       => null
        };
    }

    public function stations(string $postcode): Collection
    {
        return match ($this->name) {
            'ACS Courier'    => (new AcsFindAreaByZipcode())->handle($postcode),
            'SpeedEx'        => (new SpeedExGetBranches())->handle($postcode),
            //'GenikiTaxydromiki' => (new GenikiTaxydromiki()),
            'Courier Center' => (new CourierCenterGetStations())->handle(),
            default          => null
        };
    }

    /**
     * @throws SpeedExException
     * @throws CourierCenterException
     */
    public function trace(string $voucher): Collection
    {
        return match ($this->name) {
            'ACS Courier'    => (new AcsTrackingDetails())->handle($voucher),
            'SpeedEx'        => (new SpeedExGetTraceByVoucher())->handle($voucher),
            //'GenikiTaxydromiki' => new GenikiTaxydromiki(),
            'Courier Center' => (new CourierCenterTracking())->handle($voucher),
            default          => null
        };
    }

    /**
     * @throws SpeedExException
     * @throws CourierCenterException
     */
    public function createVoucher(Collection|Cart $carts)
    {
        return match ($this->name) {
            'ACS Courier'    => (new AcsCreateVoucher())->handle($voucher),
            'SpeedEx'        => (new SpeedExCreateVoucher())->handle($carts),
            //'GenikiTaxydromiki' => new GenikiTaxydromiki(),
            'Courier Center' => (new CourierCenterCreateVoucher())->handle($carts),
            default          => null
        };
    }

    public function printVoucher(string $voucher)
    {
        return match ($this->name) {
            'ACS Courier'    => (new AcsPrintVoucher())->handle($voucher),
            'SpeedEx'        => (new SpeedExGetVoucherPdf())->handle($voucher),
            //'GenikiTaxydromiki' => new GenikiTaxydromiki(),
            'Courier Center' => (new CourierCenterPrintVoucher())->handle($voucher),
            default          => null
        };
    }

    /**
     * @throws CourierCenterException
     */
    public function cancelVoucher(string $voucher)
    {
        return match ($this->name) {
            'ACS Courier'    => (new AcsDeleteVoucher())->handle($voucher),
            'SpeedEx'        => (new SpeedExCancelVoucher())->handle($voucher),
            //'GenikiTaxydromiki' => new GenikiTaxydromiki(),
            'Courier Center' => (new CourierCenterCancelVoucher())->handle($voucher),
            default          => null
        };
    }

    /**
     * @throws CourierCenterException
     */
    public function createPickupList(Carbon $date)
    {
        return match ($this->name) {
            'ACS Courier'    => (new AcsIssuePickupList())->handle(),
            //'SpeedEx'        => (new SpeedExCreatePickup())->handle($date),
            //'GenikiTaxydromiki' => new GenikiTaxydromiki(),
            'Courier Center' => (new CourierCenterPickupList())->handle($date),
            default          => null
        };

    }
}