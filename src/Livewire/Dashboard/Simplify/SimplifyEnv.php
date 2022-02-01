<?php

namespace Eshop\Livewire\Dashboard\Simplify;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SimplifyEnv extends Component
{
    public string $environment = "";

    public string $sandboxPublicKey  = "";
    public string $sandboxPrivateKey = "";

    public string $livePublicKey  = "";
    public string $livePrivateKey = "";

    public function mount(): void
    {
        $this->environment = Cache::rememberForever('SIMPLIFY_ENVIRONMENT',
            static fn() => DB::table('env')->where('key', 'SIMPLIFY_ENVIRONMENT')->pluck('value')->first()
        );

        $this->sandboxPublicKey = Cache::rememberForever('SIMPLIFY_SANDBOX_PUBLIC_KEY',
            static fn() => DB::table('env')->where('key', 'SIMPLIFY_SANDBOX_PUBLIC_KEY')->pluck('value')->first()
        );

        $this->sandboxPrivateKey = Cache::rememberForever('SIMPLIFY_SANDBOX_PRIVATE_KEY',
            static fn() => DB::table('env')->where('key', 'SIMPLIFY_SANDBOX_PRIVATE_KEY')->pluck('value')->first()
        );

        $this->livePublicKey = Cache::rememberForever('SIMPLIFY_LIVE_PUBLIC_KEY',
            static fn() => DB::table('env')->where('key', 'SIMPLIFY_LIVE_PUBLIC_KEY')->pluck('value')->first()
        );

        $this->livePrivateKey = Cache::rememberForever('SIMPLIFY_LIVE_PRIVATE_KEY',
            static fn() => DB::table('env')->where('key', 'SIMPLIFY_LIVE_PRIVATE_KEY')->pluck('value')->first()
        );
    }

    public function save(): void
    {
        DB::table('env')->upsert([
            ['key' => 'SIMPLIFY_ENVIRONMENT', 'value' => $this->environment],

            ['key' => 'SIMPLIFY_SANDBOX_PUBLIC_KEY', 'value' => $this->sandboxPublicKey],
            ['key' => 'SIMPLIFY_SANDBOX_PRIVATE_KEY', 'value' => $this->sandboxPrivateKey],

            ['key' => 'SIMPLIFY_LIVE_PUBLIC_KEY', 'value' => $this->livePublicKey],
            ['key' => 'SIMPLIFY_LIVE_PRIVATE_KEY', 'value' => $this->livePrivateKey],
        ], 'key');

        Cache::forget('SIMPLIFY_ENVIRONMENT');

        Cache::forget('SIMPLIFY_SANDBOX_PUBLIC_KEY');
        Cache::forget('SIMPLIFY_SANDBOX_PRIVATE_KEY');

        Cache::forget('SIMPLIFY_LIVE_PUBLIC_KEY');
        Cache::forget('SIMPLIFY_LIVE_PRIVATE_KEY');
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.simplify.wire.simplify-env');
    }
}