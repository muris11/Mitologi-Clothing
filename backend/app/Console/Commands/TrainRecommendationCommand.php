<?php

namespace App\Console\Commands;

use App\Services\RecommendationService;
use Illuminate\Console\Command;

class TrainRecommendationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Train the AI recommendation model using latest product and order data';

    /**
     * Execute the console command.
     */
    public function handle(RecommendationService $recommendationService)
    {
        $this->info('Starting AI model training...');

        if (!$recommendationService->healthCheck()) {
            $this->error('AI Service is not reachable. Please ensure the recommendation-service is running.');
            return Command::FAILURE;
        }

        if ($recommendationService->train()) {
            $this->info('AI model trained successfully!');
            return Command::SUCCESS;
        } else {
            $this->error('Failed to train AI model. Check logs for details.');
            return Command::FAILURE;
        }
    }
}
