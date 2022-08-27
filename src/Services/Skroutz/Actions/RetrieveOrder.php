<?php

namespace Eshop\Services\Skroutz\Actions;

use Eshop\Services\Skroutz\Exceptions\SkroutzException;

class RetrieveOrder extends SkroutzRequest
{
    /**
     * @throws SkroutzException
     */
    public function handle(string $skroutzOrderId)
    {
        $response = $this->get($skroutzOrderId);
        return $response->json('order');
    }
}