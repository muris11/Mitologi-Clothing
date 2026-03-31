<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RecommendationService;

class TrainModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:train-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger Recommendation Model Training';

    /**
     * Execute the console command.
     */
    public function handle(RecommendationService $service)
    {
        $this->info('Starting model training...');
        
        try {
            $result = $service->train();
            
            if ($result) {
                $this->info('Model trained successfully.');
            } else {
                $this->error('Model training failed (Service returned false).');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
