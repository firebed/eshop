<?php

namespace Eshop\Controllers\Customer;

use Illuminate\Contracts\Support\Renderable;

class HomepageController extends Controller
{
    public function __invoke(): Renderable
    {
        return $this->view('homepage.index');
    }
}
