<?php
// 代码生成时间: 2025-08-10 11:19:15
// 使用SLIM框架创建随机数生成器
// RandomNumberGenerator.php

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;

// 创建应用
$app = AppFactory::create();

// 定义路由生成随机数
$app->get('/generate-random', function ($request, $response, $args) {
    // 检查参数并设置默认值
    $min = $request->getQueryParam('min', 0);
    $max = $request->getQueryParam('max', 100);

    // 错误处理
    if ($min >= $max) {
        return $response->withJson(\[
            'success' => false,
            'message' => 'Minimum value should be less than maximum value.'
        ], 400);
    }

    // 生成随机数
    $randomNumber = rand($min, $max);

    // 返回随机数
    return $response->withJson(\[
        'success' => true,
        'random_number' => $randomNumber
    ], 200);
});

// 运行应用
$app->run();
