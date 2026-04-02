<?php

namespace App\Console\Commands;

use App\Models\HeroSlide;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanedHeroImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:orphaned-hero-images {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned hero slide images not linked to any database record';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $disk = Storage::disk('public');

        // Get all files in hero-slides directory
        $allFiles = $disk->files('hero-slides');

        // Get all image_url from database
        $dbFiles = HeroSlide::whereNotNull('image_url')
            ->pluck('image_url')
            ->toArray();

        $this->info('Scanning hero-slides directory...');
        $this->info('Total files in storage: '.count($allFiles));
        $this->info('Active files in database: '.count($dbFiles));

        $orphanedFiles = [];
        $deletedCount = 0;
        $failedCount = 0;

        foreach ($allFiles as $file) {
            if (! in_array($file, $dbFiles)) {
                $orphanedFiles[] = $file;

                if ($isDryRun) {
                    $this->warn('[DRY-RUN] Would delete: '.$file);
                } else {
                    if ($disk->exists($file)) {
                        $deleted = $disk->delete($file);

                        if ($deleted) {
                            $this->info('Deleted: '.$file);
                            $deletedCount++;

                            Log::info('Orphaned hero slide image deleted', [
                                'file' => $file,
                            ]);
                        } else {
                            $this->error('Failed to delete: '.$file);
                            $failedCount++;

                            Log::error('Failed to delete orphaned hero slide image', [
                                'file' => $file,
                            ]);
                        }
                    } else {
                        $this->warn('File not found: '.$file);
                    }
                }
            }
        }

        $this->newLine();

        if (empty($orphanedFiles)) {
            $this->info('No orphaned files found. All files are linked to database records.');
        } else {
            $this->info('Orphaned files found: '.count($orphanedFiles));

            if ($isDryRun) {
                $this->warn('Dry-run mode: No files were actually deleted.');
                $this->info('Run without --dry-run to delete these files.');
            } else {
                $this->info('Successfully deleted: '.$deletedCount.' files');
                if ($failedCount > 0) {
                    $this->error('Failed to delete: '.$failedCount.' files');
                }
            }
        }

        return 0;
    }
}
