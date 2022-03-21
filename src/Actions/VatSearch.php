<?php

namespace Eshop\Actions;

use Error;
use Illuminate\Support\Facades\Http;

class VatSearch
{
    public function handle(string $vatToSearch): array|null
    {
        $username = eshop('ggps_username');
        $password = eshop('ggps_password');

        if (blank($username) || blank($password) || blank($vatToSearch)) {
            return null;
        }

        $request = <<<EOT
<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope" xmlns:ns1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:ns2="http://rgwspublic2/RgWsPublic2Service" xmlns:ns3="http://rgwspublic2/RgWsPublic2">
   <env:Header>
      <ns1:Security>
         <ns1:UsernameToken>
            <ns1:Username>$username</ns1:Username>
            <ns1:Password>$password</ns1:Password>
         </ns1:UsernameToken>
      </ns1:Security>
   </env:Header>
   <env:Body>
      <ns2:rgWsPublic2AfmMethod>
         <ns2:INPUT_REC>
            <ns3:afm_called_by/>
            <ns3:afm_called_for>$vatToSearch</ns3:afm_called_for>
         </ns2:INPUT_REC>
      </ns2:rgWsPublic2AfmMethod>
   </env:Body>
</env:Envelope>
EOT;

        $response = Http::withBody($request, 'application/soap+xml')
            ->post('https://www1.gsis.gr/wsaade/RgWsPublic2/RgWsPublic2?WSDL');

        $xml = $response->body();
        $xml = str_ireplace(['env:', 'srvc:'], '', $xml);
        $xml = simplexml_load_string($xml);

        $result = $xml->Body->rgWsPublic2AfmMethodResponse->result->rg_ws_public2_result_rtType;
        $error = $result->error_rec;

        if (filled((string)$error->error_code)) {
            throw new Error((string)$error->error_descr);
        }

        $rec = $result->basic_rec;
        $jobs = $result->firm_act_tab;
        
        if ((string) $rec->deactivation_flag !== "1") {
            throw new Error("Ο Α.Φ.Μ για τον οποίο ζητούνται πληροφορίες είναι απενεργοποιημένος.");            
        }
        
        return [
            'vat'           => (string)$rec->afm,
            'tax_authority' => (string)$rec->doy_descr,
            'name'          => preg_replace('!\s+!', " ", (string)$rec->onomasia),
            'city'          => (string)$rec->postal_area_description,
            'postcode'      => (string)$rec->postal_zip_code,
            'job'           => (string)$jobs->item[0]->firm_act_descr,
            'street'        => (string)$rec->postal_address,
            'street_number' => (string)$rec->postal_address_no,
        ];
    }
}
