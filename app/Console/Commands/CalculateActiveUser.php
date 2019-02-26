<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CalculateActiveUser extends Command
{
    /**
     * 供我们调用命令
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:calculate-active-user';

    /**
     * 命令的描述
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成活跃用户';

    /**
     *
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 最终执行的方法
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(User $user)
    {
        //在命令行打印一行信息
        $this->info('开始计算');

        $user->calculateAndCacheActiveUsers();

        $this->info('成功生效!');
    }
}
