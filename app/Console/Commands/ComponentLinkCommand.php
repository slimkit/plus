<?php

namespace Zhiyi\Plus\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ComponentLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'component:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from "repositorie" to "vendor" (Composer component.)';

    /**
     * 执行操作.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle()
    {
        $this->call(
            'package:link',
            ['package' => $this->argument('package')]
        );

        $this->error('This command is about to be removed. Please use the "package:link" command.');
    }

    /**
     * get command arguments.
     *
     * @return array
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function getArguments()
    {
        return [
            ['package', InputArgument::REQUIRED, 'The package to symbolic link instead by dir.'],
        ];
    }
}
