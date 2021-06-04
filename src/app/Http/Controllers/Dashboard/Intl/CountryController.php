<?php

namespace App\Http\Controllers\Dashboard\Intl;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('dashboard.intl.countries');
    }
}
