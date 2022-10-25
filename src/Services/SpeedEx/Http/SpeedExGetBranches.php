<?php

namespace Eshop\Services\SpeedEx\Http;

use Illuminate\Support\Collection;

class SpeedExGetBranches extends SpeedExRequest
{
    const LANG_GREEK = 1;
    const LANG_ENG   = 2;

    protected string $action = 'GetBranches';

    public function handle(string $zipCode, int $language = 1): Collection
    {
        $response = $this->request([
            'language' => $language,
            'zipCode'  => $zipCode,
        ]);

        return collect($response->Branches->Branch ?? [])
            ->map(fn($station) => [
                'id'   => $station->BranchCode,
                'name' => $station->BranchName,
                'type' => null,
            ]);
    }
}