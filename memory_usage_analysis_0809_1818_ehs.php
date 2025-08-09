<?php
// 代码生成时间: 2025-08-09 18:18:05
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个函数来格式化内存使用量
function formatMemoryUsage($memoryUsage) {
    if ($memoryUsage < 1024) {
        return $memoryUsage . ' bytes';
    } elseif ($memoryUsage < 1048576) {
        return round($memoryUsage / 1024, 2) . ' KB';
    } else {
        return round($memoryUsage / 1048576, 2) . ' MB';
    }
}

// 创建一个Slim应用
AppFactory::setCodingStylePrettifyExceptions(true);
AppFactory::setEnv('production');
$app = AppFactory::create();

// 定义一个路由来显示内存使用情况
$app->get('/memory', function (Request $request, Response $response, $args) {
    try {
        // 获取当前内存使用量
        $currentMemoryUsage = memory_get_usage();
        // 格式化内存使用量
        $formattedMemoryUsage = formatMemoryUsage($currentMemoryUsage);
        // 设置响应体
        $response->getBody()->write("Current memory usage: " . $formattedMemoryUsage);
        return $response;
    } catch (Exception $e) {
        // 处理异常
        $response->getBody()->write("Error: " . $e->getMessage());
        return $response->withStatus(500);
    }
});

// 运行应用
$app->run();
