<?php

namespace Eshop\Services\SpeedEx\Http;

class SpeedExGetTraceByReference extends SpeedExRequest
{
    protected string $action = 'GetTraceByClientKey';

    public function handle(string $referenceKey1 = null, string $referenceKey2 = null, string $referenceKey3 = null)
    {
        return $this->request([
            'ClientKey1' => $referenceKey1,
            'ClientKey2' => $referenceKey2,
            'ClientKey3' => $referenceKey3
        ]);
    }
}