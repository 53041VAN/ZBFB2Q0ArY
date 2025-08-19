<?php
// 代码生成时间: 2025-08-19 19:38:32
// 使用Composer自动加载Slim框架和其它依赖
require 'vendor/autoload.php';

use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use React\EventLoop\Factory;
use React\ChildProcess\Process;
use React\Promise\Deferred;

// 创建Slim应用
$app = AppFactory::create();

// 创建日志记录器
$container = $app->getContainer();
$container['logger'] = function(ContainerInterface $container): LoggerInterface {
    $logger = new Logger('slim-skeleton');
    $logger->pushHandler(new RotatingFileHandler(__DIR__ . '/logs/app.log', 0, Logger::DEBUG));
    $logger->pushHandler(new StreamHandler(fopen('php://stdout', 'w'), Logger::INFO));
    return $logger;
};

// 定时任务调度器
$loop = Factory::create();
$deferred = new Deferred();

// 定义任务执行函数
$taskFunction = function() use ($app, $loop) {
    // 在这里编写你需要定时执行的任务代码
    // 例如：发送邮件，备份数据库，清理缓存等
    $app->getContainer()['logger']->info('定时任务执行中...');

    // 模拟任务执行时间
    $loop->addTimer(2, function() use ($deferred) {
        $deferred->resolve();
    });
};

// 定时任务调度器
$app->get('/run-task', function ($request, $response, $args) use ($taskFunction, $loop, $deferred) {
    $taskFunction();
    $deferred->promise()->then(function() use ($response) {
        return $response->withJson(['status' => 'success', 'message' => '任务执行完成！']);
    }, function($error) use ($response) {
        return $response->withJson(['status' => 'error', 'message' => '任务执行失败！原因：' . $error->getMessage()]);
    });
    return $response;
});

// 添加错误处理中间件
$app->addErrorMiddleware(true, true, true);

// 运行应用
$app->run();
