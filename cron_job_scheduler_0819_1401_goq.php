<?php
// 代码生成时间: 2025-08-19 14:01:03
// cron_job_scheduler.php
// 使用Slim框架实现的定时任务调度器

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

// 定义定时任务类
class CronJob {
    protected $task;

    public function __construct($task) {
        $this->task = $task;
    }

    // 执行定时任务
    public function execute() {
        try {
            // 运行定时任务
            $process = new Process([$this->task]);
            $process->setTimeout(3600); // 设置超时时间为1小时
            $process->run();

            // 检查进程是否成功执行
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            return $process->getOutput();
        } catch (ProcessFailedException $e) {
            // 处理进程失败异常
            return "There was an error: {$e->getMessage()}";
        }
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 添加路由以触发定时任务
$app->get('/execute-task', function (Request $request, Response $response, $args) {
    $task = 'php /path/to/your/cron_task.php'; // 指定要执行的定时任务脚本路径
    $cronJob = new CronJob($task);
    $output = $cronJob->execute();

    // 返回执行结果
    return $response->getBody()->write($output);
});

// 运行应用
$app->run();
