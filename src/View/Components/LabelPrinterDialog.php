<?php

namespace Eshop\View\Components;

use Eshop\Services\LabelPrinterService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

class LabelPrinterDialog extends Component
{
    public LabelPrinterService $label;

    public function __construct(LabelPrinterService $service)
    {
        $this->label = $service;
    }

    public function render(): Renderable
    {
        return view('eshop::components.label-printer-dialog');
    }
}