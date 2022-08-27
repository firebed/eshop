<?php

namespace Eshop\Services\Skroutz\Actions;

use Eshop\Services\Skroutz\Enums\SkroutzRejectOptions;
use Eshop\Services\Skroutz\Exceptions\SkroutzException;

class UploadInvoice extends SkroutzRequest
{
    private const ACTION = "invoices";

    /**
     * To upload the receipt/invoice of the order, you need to
     * set the invoice_file param with the contents of the document.
     *
     * Only one file is allowed per order. If the file is already uploaded,
     * it will be replaced by the new file in a subsequent submission.
     *
     * @param string $skroutzOrderId The order's id by Skroutz
     * @param string $invoice        Local path to invoice file. Allowed file types are: pdf/png/jpg.
     *                               The maximum file size is 7MB.
     *
     * @return bool True on success or false on failure
     *
     * @throws SkroutzException
     * @see SkroutzRejectOptions
     */
    public function handle(string $skroutzOrderId, string $invoice): bool
    {
        $response = $this->upload($skroutzOrderId, self::ACTION, [
            'invoice_file' => $invoice
        ]);

        return $response->json('status');
    }
}