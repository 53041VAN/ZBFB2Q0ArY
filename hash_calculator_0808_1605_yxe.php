<?php
// 代码生成时间: 2025-08-08 16:05:33
// 使用Slim框架创建的哈希值计算工具
require 'vendor/autoload.php';

$app = new \Slim\Slim();

// 定义一个路由来处理哈希值计算请求
$app->post('/calculate-hash', function () use ($app) {

    // 获取请求体中的数据
    $request = $app->request();
    $data = $request->getBody();

    // 检查请求体是否为空
    if (empty($data)) {
        $app->response()->status(400);
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode(array(
            'error' => 'No data provided.'
        ));
        return;
    }

    // 计算哈希值，这里示例使用md5算法
    $hash = md5($data);

    // 响应哈希值计算结果
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode(array(
        'original_data' => $data,
        'hash' => $hash
    ));

});

// 运行Slim应用
$app->run();
