<?php

namespace App\Console\Commands;

use App\Services\AiBlogDraftGeneratorService;
use Illuminate\Console\Command;

class GenerateAiBlogDraftsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blogs:generate-ai-drafts {--limit= : Number of topics to process in this run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch multi-source topics and generate AI-powered blog drafts for admin review';

    public function __construct(private readonly AiBlogDraftGeneratorService $generatorService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $defaultLimit = (int) (config('blog.default_limit') ?? config('blog_automation.default_limit', 5));
        $optionLimit = $this->option('limit');
        $limit = is_numeric($optionLimit) ? max(1, (int) $optionLimit) : max(1, $defaultLimit);

        $this->info("Starting AI draft generation (limit: {$limit})...");

        $result = $this->generatorService->run($limit);

        $this->line('Topics fetched: ' . $result['fetched']);
        $this->line('Drafts created: ' . $result['created']);
        $this->line('Duplicates skipped: ' . $result['duplicates']);
        $this->line('Failures skipped: ' . $result['failed']);

        $this->info('AI draft generation completed.');

        return self::SUCCESS;
    }
}
