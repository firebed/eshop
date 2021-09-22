<?php

namespace Eshop\Commands;

use Firebed\Sitemap\Pings\Google;
use Eshop\Services\SitemapGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

class SitemapCommand extends Command
{
    protected $signature = 'sitemap:generate {--ping} {--force}';

    protected $description = 'Generates a sitemap';

    public function handle(): void
    {
        $generator = new SitemapGenerator();

        if (!$this->option('force') && !$generator->shouldUpdate()) {
            $this->info("Sitemap is up to date.");
            return;
        }

        $this->info("Generating sitemap...");
        $start = microtime(true);

        $generator->generate();

        $elapsed = format_number(microtime(true) - $start, 2);
        $this->report($elapsed, $generator->total_sitemaps, $generator->total_urls);

        if ($this->option('ping')) {
            $response = Google::pingSitemap(urlencode(URL::asset("sitemap.xml")));
            if ($response->ok()) {
                $this->info("Ping to Google: Success!");
            } else {
                $this->warn("Ping to Google: Error! " . $response->status());
            }
        }
    }

    private function report(string $seconds, int $totalSitemaps, int $totalUrls): void
    {
        $this->info("Sitemap generated successfully:");
        $this->info("- Duration: $seconds seconds");
        $this->info("- Sitemaps: $totalSitemaps");
        $this->info("- Urls: $totalUrls");
    }
}