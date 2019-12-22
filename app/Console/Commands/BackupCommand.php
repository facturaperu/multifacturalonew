<?php
namespace App\Console\Commands;

use App\Console\Commands\Services\BackupJobFactory;
use Exception;
use Spatie\Backup\Commands\BackupCommand as SpatieBackupCommand;
use Spatie\Backup\Events\BackupHasFailed;

class BackupCommand extends SpatieBackupCommand
{

    protected $signature = 'backup:tenants {--disable-notifications}';

    public function handle()
    {
        consoleOutput()->comment('Starting backup...');

        $disableNotifications = $this->option('disable-notifications');

        try {
            $backupJob = BackupJobFactory::createFromArray(config('backup'));

            if ($disableNotifications) {
                $backupJob->disableNotifications();
            }

            $backupJob->run();

            consoleOutput()->comment('Backup completed!');

        } catch (Exception $exception) {
            consoleOutput()->error("Backup failed because: {$exception->getMessage()}.");

            if (! $disableNotifications) {
                event(new BackupHasFailed($exception));
            }

            return 1;
        }
    }
}
