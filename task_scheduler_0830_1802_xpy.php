<?php
// 代码生成时间: 2025-08-30 18:02:57
// TaskScheduler.php
// 使用Slim框架实现的定时任务调度器
require 'vendor/autoload.php';

use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

// 定义定时任务调度器
class TaskScheduler 
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function scheduleTask($task, $interval)
    {
        try {
            // 检查任务是否可调度
            if (!is_callable($task)) {
                throw new InvalidArgumentException('Task is not callable');
            }

            // 设置任务执行间隔
            $interval = (int) $interval;
            if ($interval <= 0) {
                throw new InvalidArgumentException('Interval must be a positive integer');
            }

            // 添加到调度器
            $this->container['task_queue']->addTask($task, $interval);

            echo "Task scheduled successfully.\
";
        } catch (Exception $e) {
            // 错误处理
            echo "Error scheduling task: " . $e->getMessage() . "\
";
        }
    }
}

// 初始化Slim应用
$app = AppFactory::create();

// 设置容器
$container = $app->getContainer();
$container['task_queue'] = function ($c) {
    return new TaskQueue();
};
$container['task_scheduler'] = function ($c) {
    return new TaskScheduler($c);
};

// 注册调度任务路由
$app->get('/schedule-task', function ($request, $response, $args) {
    $task = $args['task'];
    $interval = $args['interval'];
    $scheduler = $this->getContainer()->get('task_scheduler');
    $scheduler->scheduleTask($task, $interval);
    return $response->withJson(['message' => 'Task scheduled successfully.']);
});

// 运行应用
$app->run();

// TaskQueue类用于管理任务队列
class TaskQueue 
{
    private $tasks = [];

    public function addTask(callable $task, $interval)
    {
        $this->tasks[] = ['task' => $task, 'interval' => $interval];
    }

    public function runTasks()
    {
        foreach ($this->tasks as $task) {
            // 执行任务
            call_user_func($task['task']);

            // 等待间隔时间
            sleep($task['interval']);
        }
    }
}

// 确保TaskQueue类在容器中被正确实例化和使用

?>