<?php
// 代码生成时间: 2025-09-23 04:12:48
// 引入Slim框架
require 'vendor/autoload.php';

// 创建Slim实例
$app = new \Slim\App();

// 定义GET请求处理函数
$app->get('/api/users', function ($request, $response, $args) {
    // 获取用户数据，这里假设为静态数据
    $users = [
        ['id' => 1, 'name' => 'John Doe'],
        ['id' => 2, 'name' => 'Jane Smith'],
    ];

    // 返回JSON响应
    return $response->withJson($users);
});

// 定义POST请求处理函数
$app->post('/api/users', function ($request, $response, $args) {
    // 解析请求体中的JSON数据
    $data = $request->getParsedBody();

    // 验证数据
    if (empty($data['name'])) {
        return $response->withStatus(400)
            ->withJson(['error' => 'Name is required']);
    }

    // 创建用户，这里假设直接添加到数组中
    $id = count($users) + 1;
    $users[] = ['id' => $id, 'name' => $data['name']];

    // 返回JSON响应
    return $response->withJson($users[$id - 1]);
});

// 运行Slim应用
$app->run();