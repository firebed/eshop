<?php

namespace Eshop\Controllers\Customer\Feed;

use Eshop\Actions\Feed\CreateSkroutzXML;
use Eshop\Actions\ReportError;
use Illuminate\Http\Response;
use Throwable;

class SkroutzFeedController
{
    public function __invoke(CreateSkroutzXML $skroutz, ReportError $report): Response
    {
        try {
            $xml = $skroutz->handle();
            return response($xml->asXML(), 200, ['Context-Type' => 'application/xml']);
        } catch (Throwable $t) {
            $report->handle($t->getMessage(), $t->getTraceAsString());
        }

        return response('', 200, ['Context-Type' => 'application/xml']);
    }
}
