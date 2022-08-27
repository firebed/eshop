<?php

namespace Eshop\Commands;

use Eshop\Actions\ReportError;
use Eshop\Services\Skroutz\Actions\CreateSkroutzXML;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SkroutzFeedCommand extends Command
{
    protected $signature = 'skroutz:generate';

    protected $description = 'Create xml feed for Skroutz';

    public function handle(ReportError $report, CreateSkroutzXML $skroutz): void
    {        
        try {
            $xml = $skroutz->handle();
            Storage::disk('public')->put('feeds/skroutz.xml', $xml->asXML());
            $this->info('Skroutz feed created successfully.');
            $this->info("Path: " . Storage::disk('public')->path('feeds/skroutz.xml'));
        } catch (Throwable $t) {
            $this->error($t->getMessage());
            $report->handle($t->getMessage(), $t->getTraceAsString());
        }
    }
}