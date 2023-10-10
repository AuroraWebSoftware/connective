<?php

namespace AuroraWebSoftware\Connective\Commands;

use Illuminate\Console\Command;

class ConnectiveCommand extends Command
{
    public $signature = 'connective';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
