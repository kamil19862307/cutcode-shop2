<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'shop:install';

    protected $description = 'Instalation';

    public function handle(): int
    {
        $this->call('php artisan storage:link');
        $this->call('migrate');

        return self::SUCCESS;
    }
}
