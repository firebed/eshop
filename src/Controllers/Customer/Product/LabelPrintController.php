<?php

namespace Eshop\Controllers\Customer\Product;

use Dompdf\Dompdf;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Product\Product;
use Eshop\Services\LabelPrinterService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Picqer\Barcode\BarcodeGeneratorSVG;

class LabelPrintController extends Controller
{
    public function index(): Renderable
    {
        return $this->view('label.index');
    }

    public function export(Request $request, LabelPrinterService $service): Response|Application|ResponseFactory
    {
        $options = $request->validate([
            'labels'              => ['required', 'array'],
            'labels.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'labels.*.quantity'   => ['sometimes', 'integer'],
            'width'               => ['required', 'integer'],
            'height'              => ['required', 'integer'],
            'margin'              => ['required', 'integer'],
            'fontSize'            => ['required', 'integer'],
            'copies'              => ['required', 'integer'],
        ]);

        $service->update(...Arr::only($options, ['width', 'height', 'margin', 'fontSize']));

        $labels = collect($request->input('labels.*'))->pluck('quantity', 'product_id');
        $products = Product::with('translation', 'parent.translation', 'options')->findMany($labels->keys());

        $generator = new BarcodeGeneratorSVG();

        foreach ($products as $product) {
            $product->labels_count = $labels[$product->id];
            if (filled($product->barcode)) {
                $product->barcode_img = base64_encode($generator->getBarcode($product->barcode, $generator::TYPE_CODE_128, 1, 32));
            }
        }

        $pdf = new Dompdf();
        $pdf->loadHtml($this->view('label.export', compact('products', 'options')));

        $pdf->render();
        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=labels.pdf");
    }
}