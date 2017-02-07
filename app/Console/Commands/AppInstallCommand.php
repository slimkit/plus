<?php

namespace Zhiyi\Plus\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class AppInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:install';

    protected $name = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installed the app component.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $component = $this->getNameInput();

        $installerClass = array_get(config('component'), $component);
        var_export($installerClass);
    }

    protected function getNameInput()
    {
        if (!$this->hasArgument('name')) {
            $this->error('name already exists!');
        }

        return $this->argument('name');
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of The component.'],
        ];
    }
}
