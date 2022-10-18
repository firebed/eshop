<?php

namespace Eshop\Services\Acs\Http;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class AcsCreateVoucher extends AcsRequest
{
    const CHARGE_SENDER   = 2;
    const CHARGE_RECEIVER = 4;

    protected string $action = 'ACS_Create_Voucher';

    public function handle(string  $billingCode, Carbon $pickupDate, string $sender, string $recipientName, string $recipientAddress,
                           string  $recipientAddressNumber, string $recipientPostcode, string $recipientRegion,
                           ?string $recipientPhone, ?string $recipientCellPhone, ?string $recipientFloor,
                           string  $recipientCountry, ?string $acsStationDestination, int $acsStationBranchDestination,
                           int     $chargeType, int $itemQuantity, float $weight, ?float $codAmount = null,
                           ?array  $deliveryProducts = null,
                           ?string $deliveryNotes = null, ?string $recipientEmail = null,
                           ?string $referenceKey1 = null, ?string $referenceKey2 = null,
    )
    {
        $deliveryProducts = Arr::wrap($deliveryProducts);
        $deliveryProducts = array_map('trim', $deliveryProducts);

        $params = [
            "Billing_Code"                   => $billingCode,
            "Pickup_Date"                    => $pickupDate->format('Y-m-d'),
            "Sender"                         => $sender,
            "Recipient_Name"                 => $recipientName,
            "Recipient_Address"              => $recipientAddress,
            "Recipient_Address_Number"       => $recipientAddressNumber,
            "Recipient_Zipcode"              => $recipientPostcode,
            "Recipient_Region"               => $recipientRegion,
            "Recipient_Phone"                => $recipientPhone,
            "Recipient_Cell_Phone"           => $recipientCellPhone,
            "Recipient_Floor"                => $recipientFloor,
            "Recipient_Company_Name"         => null,
            "Recipient_Country"              => $recipientCountry,
            "Acs_Station_Destination"        => $acsStationDestination,
            "Acs_Station_Branch_Destination" => $acsStationBranchDestination,
            "Charge_Type"                    => $chargeType,
            "Cost_Center_Code"               => null,
            "Item_Quantity"                  => $itemQuantity,
            "Weight"                         => $weight,
            "Dimension_X_In_Cm"              => null,
            "Dimension_Y_in_Cm"              => null,
            "Dimension_Z_in_Cm"              => null,
            "Cod_Ammount"                    => $codAmount,
            "Cod_Payment_Way"                => is_null($codAmount) ? null : 0,
            "Acs_Delivery_Products"          => empty($deliveryProducts) ? null : implode(",", $deliveryProducts),
            "Insurance_Ammount"              => null,
            "Delivery_Notes"                 => $deliveryNotes,
            "Appointment_Until_Time"         => null,
            "Recipient_Email"                => $recipientEmail,
            "Reference_Key1"                 => $referenceKey1,
            "Reference_Key2"                 => $referenceKey2,
            "With_Return_Voucher"            => null,
            "Content_Type_ID"                => null,
            "Language"                       => null
        ];

        [$value] = $this->request($params);

        return $value[0];
    }
}