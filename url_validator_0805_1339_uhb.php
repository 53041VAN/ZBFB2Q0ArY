<?php
// 代码生成时间: 2025-08-05 13:39:32
// 使用 Slim 框架创建一个简单的 URL 验证程序
require 'vendor/autoload.php';

// 初始化 Slim 应用
$app = new \Slim\Slim();

// 定义一个路由用于 URL 验证
$app->get('/validate-url', function () use ($app) {
    // 获取 URL 参数
    $url = $app->request()->params('url');

    // 检查 URL 是否提供
    if (!$url) {
        // 如果没有提供 URL，返回错误信息
        $app->response()->status(400);
        echo json_encode(['error' => 'URL parameter is missing']);
        return;
    }

    // 使用 filter_var 函数进行 URL 验证
    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        // 如果 URL 无效，返回错误信息
        $app->response()->status(400);
        echo json_encode(['error' => 'Invalid URL format']);
    } else {
        // 如果 URL 有效，返回成功信息
        echo json_encode(['message' => 'URL is valid']);
    }
});

// 运行应用
$app->run();