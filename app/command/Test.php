<?php

declare(strict_types=1);

namespace app\command;

use think\console\Input;
use think\console\Output;

class Test extends BaseCommand
{
    protected function configure()
    {
        // 指令配置
        $this->setName('Test')
            ->setDescription('StartAdmin Test Command');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->console("这是普通输出");
        $this->warning("这是警告输出");
        $this->error("这是错误输出");
        $this->success("这是成功输出");
    }
}
