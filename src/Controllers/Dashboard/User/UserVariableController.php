<?php

namespace Eshop\Controllers\Dashboard\User;

use Eshop\Controllers\Dashboard\Controller;
use Eshop\Services\VarCache;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserVariableController extends Controller
{
    public function index(): Renderable
    {
        $cached = VarCache::all();

        if ($cached === null) {
            $variables = DB::table('env')->pluck('value', 'key');

            VarCache::remember($variables->all());
        }
        
        $variables = VarCache::fill(VarCache::all());
        return $this->view('user-variables.index', compact('variables'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'variables'   => ['nullable', 'array'],
            'variables.*' => ['nullable', 'string']
        ]);

        $variables = $request->collect('variables')->map(fn($v, $k) => ['key' => $k, 'value' => $v]);

        DB::table('env')->upsert($variables->values()->all(), 'key');

        VarCache::remember($variables->mapWithKeys(fn($a, $k) => [$k => $a['value']])->all());

        return back();
    }
}