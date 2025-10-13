<?php

namespace App\Console\Commands;

use App\Models\SitemapData;
use DateTime;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;

class MakeSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sitemap for the application.';

    /**
     * The collection of sitemap data.
     */
    protected $sitemapData;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->sitemapData = collect();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Gathering sitemap data...');

        $this->sitemapData = SitemapData::where('status', 'active')
            ->orderBy('priority', 'desc')
            ->get(['url', 'last_modified', 'change_frequency', 'priority'])
            ->map(function ($item) {
                return Url::create($item->url)
                    ->setLastModificationDate(new DateTime($item->last_modified))
                    ->setChangeFrequency($item->change_frequency)
                    ->setPriority($item->priority);
            });

        $this->info('Data successfully gathered.');

        $this->info('Generating sitemap...');

        try {
            $sitemap = Sitemap::create();

            $this->sitemapData->each(function ($url) use ($sitemap) {
                $sitemap->add($url);
            });

            $sitemap->writeToFile(public_path('sitemap.xml'));

            $this->info('Sitemap generated successfully.');
        } catch (\Throwable $e) {
            $this->error('Failed to generate sitemap: ' . $e->getMessage());
            return 1;
        }
    }
}
