<?php
// 代码生成时间: 2025-08-05 18:40:53
// 引入Slim框架
require 'vendor/autoload.php';

// 创建Slim App实例
$app = new \Slim\App();

// 定义中间件来处理XSS攻击防护
$app->add(function ($request, $handler) {
    // 获取请求体的内容
    $body = $request->getParsedBody();
    // 过滤输入内容以防止XSS攻击
    $filteredInput = array_map(function ($value) {
        // 使用htmlspecialchars函数转义HTML特殊字符
        return is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
    }, $body);
    // 将过滤后的内容设置回请求体
    $request = $request->withParsedBody($filteredInput);
    // 继续处理请求
    return $handler->handle($request);
});

// 定义一个路由来测试XSS防护
$app->get('/xss', function ($request, $response, $args) {
    // 获取过滤后的输入
    $input = $request->getParsedBody();
    // 将输入显示在响应中
    return $response->getBody()->write('<pre>' . htmlspecialchars(print_r($input, true)) . '</pre>');
});

// 运行应用
$app->run();
