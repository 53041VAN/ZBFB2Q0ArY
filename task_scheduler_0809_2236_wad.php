<?php
// 代码生成时间: 2025-08-09 22:36:30
// 使用Composer autoloader
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// 创建Slim应用
AppFactory::setCodingStylePsr12();
AppFactory::setContainerConfigurator(\$configurator = function (\Interop\Container\ContainerInterface $container) {
    // 注册日志服务
    $container[LoggerInterface::class] = function ($c) {
        $logger = new Logger('slim-skeleton');
        $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
        return $logger;
    };
});

$app = AppFactory::create();

// 定时任务调度器中间件
$app->add(function (\$request, \$response, \$next) {
    try {
        // 每天凌晨2点执行定时任务
        if (date('H') == 2 && date('i') == 0) {
            // 这里添加定时任务执行代码
            echo "定时任务执行中...\
";
            // 例如：清理日志文件，发送通知等
        }
    } catch (\Exception \$e) {
        // 错误处理
        \$this->logger->error(\$e->getMessage());
        return \$response->withStatus(500)->getBody()->write('Internal Server Error');
    }
    return \$next(\$request, \$response);
});

// 定义一个简单的路由作为示例
$app->get('/[{name}]', function (\$request, \$response, \$args) {
    return \$response->getBody()->write("Hello, " . \$args['name'] . "!");
});

// 运行应用
$app->run();
