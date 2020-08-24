<?php

namespace Youke\BaseSettings\Console;

use Illuminate\Console\Command;
use Youke\BaseSettings\Console\Helpers\Publisher;

class PublishConfigCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'swagger:publish-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish config';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Publish config files');

        (new Publisher($this))->publishFile(
            realpath(__DIR__ . '/../../config/') . '/swagger.php',
            base_path('config'),
            'swagger.php'
        );
    }
}
