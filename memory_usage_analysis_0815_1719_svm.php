<?php
// 代码生成时间: 2025-08-15 17:19:14
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;

// 一个简单的内存使用情况分析程序
// 通过Slim框架提供RESTful接口

// 创建应用
$app = AppFactory::create();

// 获取当前内存使用的路由
# 添加错误处理
$app->get('/memory', function ($request, $response, $args) {
    // 获取内存使用量
    $memoryUsage = memory_get_usage();
    
    // 返回内存使用量
    return $response->getBody()->write(json_encode(['memory_usage' => $memoryUsage]));
});

// 运行应用
$app->run();
