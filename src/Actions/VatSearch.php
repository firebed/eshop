<?php

namespace Eshop\Actions;


use Error;
use SoapClient;
use SoapFault;
use SoapHeader;
use stdClass;

class VatSearch
{
    private const WSDL = 'https://www1.gsis.gr/wsaade/RgWsPublic2/RgWsPublic2?WSDL';
    private const WSS  = 'https://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';

    /**
     * @throws SoapFault
     */
    public function handle(string $vatToSearch, string $vatCalledBy = null): array
    {
        $response = $this->getResponse($vatToSearch, $vatCalledBy);

        $rec = $response->basic_rec;
        $firms = $response->firm_act_tab->item;
        $firms = is_array($firms) ? $firms : [$firms];

        return [
            'vat'           => trim($rec->afm),
            'tax_authority' => trim($rec->doy_descr),
            'name'          => preg_replace('!\s+!', ' ', trim($rec->onomasia)),
            'city'          => trim($rec->postal_area_description),
            'postcode'      => trim($rec->postal_zip_code),
            'job'           => trim($firms[0]->firm_act_descr ?? ''),
            'street'        => trim($rec->postal_address),
            'street_number' => trim($rec->postal_address_no),
        ];
    }

    /**
     * @throws SoapFault
     */
    private function getResponse(string $vatToSearch, string $vatCalledBy = null)
    {
        if (str_starts_with($vatToSearch, 'EL')) {
            $vatToSearch = mb_substr($vatToSearch, 2);
        }

        if (blank($vatToSearch)) {
            throw new Error("Ο Α.Φ.Μ για τον οποίο ζητούνται πληροφορίες είναι απενεργοποιημένος.");
        }

        $client = new SoapClient(self::WSDL, ['soap_version' => SOAP_1_2]);
        $client->__setSoapHeaders($this->prepareHeaders());

        $response = $client->rgWsPublic2AfmMethod([
            'INPUT_REC' => [
                'afm_called_by'  => $vatCalledBy,
                'afm_called_for' => $vatToSearch
            ]
        ]);

        $response = $response->result->rg_ws_public2_result_rtType;

        $error = $response->error_rec;
        if (filled($error->error_code)) {
            throw new Error(trim($error->error_descr));
        }

        return $response;
    }

    private function prepareHeaders(): SoapHeader
    {
        $username = api_key('GGPS_USERNAME');
        $password = api_key('GGPS_PASSWORD');

        if (blank($username) || blank($password)) {
            throw new Error("Ανάθεση κωδικών πρόσβασης Γ.Γ.Π.Σ");
        }

        $header = new stdClass();
        $header->UsernameToken = new stdClass();
        $header->UsernameToken->Username = $username;
        $header->UsernameToken->Password = $password;

        return new SoapHeader(self::WSS, 'Security', $header);
    }
}
