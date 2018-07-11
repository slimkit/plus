<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Console\Commands;

use InvalidArgumentException;
use Illuminate\Console\Command;
use Zhiyi\Plus\Support\Configuration;

class InstallPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'install:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Operation and installation password.';

    /**
     * The Plus config repostyory.
     *
     * @var \Zhiyi\Plus\Support\Configuration
     */
    protected $repository;

    /**
     * Create the console command instance.
     *
     * @param \Zhiyi\Plus\Support\Configuration $repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Configuration $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * The console command handle.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function handle()
    {
        // Asking for a new password.
        $question = 'Please enter a new installation password';
        $questionDefaultValue = null;
        $value = $this->getOutput()->askHidden($question, $questionDefaultValue);

        // Ask for confirmation password and confirm whether it is consistent with inquiry password.
        $question = 'Please enter the confirmation password';
        $this->getOutput()->askHidden($question, function ($passwordConfirmation) use ($value) {
            if ($passwordConfirmation === $value) {
                return;
            }

            throw new InvalidArgumentException('Two passwords are not consistent.');
        });

        $this->repository->set('installer.password', md5($value));
        $this->info('The installation password is set successfully.');
    }
}
