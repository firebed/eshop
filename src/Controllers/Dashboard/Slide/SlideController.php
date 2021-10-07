<?php

namespace Eshop\Controllers\Dashboard\Slide;

use Eshop\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class SlideController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Manage slides');
    }
    
    public function index(): Renderable
    {
        return view('eshop::dashboard.slide.index');
    }
}