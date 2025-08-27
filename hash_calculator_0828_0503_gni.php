<?php
// 代码生成时间: 2025-08-28 05:03:31
// hash_calculator.php
// 一个使用Slim框架的哈希值计算工具

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;

// 初始化Slim应用
AppFactory::setCodingStyle('psr12');
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// 路由：计算哈希值
$app->get('/calculate-hash', function ($request, $response, $args) {
    // 获取请求参数
    $data = $request->getParsedBody();
    if (!isset($data['content']) || !isset($data['algorithm'])) {
        return $response->withJson(
            ['error' => 'Missing required parameters: content and algorithm'],
            400
        );
    }

    $content = $data['content'];
    $algorithm = $data['algorithm'];

    // 验证算法是否支持
    if (!in_array($algorithm, hash_algos(), true)) {
        return $response->withJson(
            ['error' => 'Unsupported hashing algorithm'],
            400
        );
    }

    // 计算哈希值
    $hash = hash($algorithm, $content);

    // 返回结果
    return $response->withJson(['hash' => $hash]);
});

// 启动应用
$app->run();
