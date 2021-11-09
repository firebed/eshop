<?php

namespace Eshop\Listeners;

use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Facades\DB;

class CreateLoginRecord
{
    public function handle(): void
    {
        DB::table('logins')->insert([
            'user_id'    => auth()->id(),
            'device'     => $this->getDevice(),
            'ip'         => request()?->ip(),
            'created_at' => now()
        ]);
    }

    private function getDevice(): string|null
    {
        if (Browser::isMobile()) {
            return 'mobile';
        }

        if (Browser::isTablet()) {
            return 'table';
        }

        if (Browser::isDesktop()) {
            return 'desktop';
        }
        
        return null;
    }
}
