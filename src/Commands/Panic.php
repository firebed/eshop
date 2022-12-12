<?php

namespace Eshop\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class Panic extends Command
{
    protected $signature = 'panic {enable=true}';

    public function handle(): void
    {
        $enabled = filter_var($this->argument('enable'), FILTER_VALIDATE_BOOLEAN);
    
        Cache::put('panic', $enabled);
    }
}