<?php
// 代码生成时间: 2025-08-08 07:12:07
// RandomNumberGenerator.php
// 使用Slim框架实现的随机数生成器

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;

// 创建Slim应用程序
AppFactory::create();

// 定义生成随机数的路由
\$app->get('/random', function (Request \$request, Response \$response, \$args) {
    // 获取请求参数中的最小值和最大值
    \$min = \$request->getQueryParam('min', 1);
    \$max = \$request->getQueryParam('max', 100);

    // 验证参数值
    if (!is_numeric(\$min) || !is_numeric(\$max)) {
        // 如果参数值不是数字，返回错误信息
        \$response->getBody()->write('Min and max values must be numeric.');
        return \$response->withStatus(400);
    }

    // 生成随机数
    \$randomNumber = rand(\$min, \$max);

    // 将随机数添加到响应体
    \$response->getBody()->write(json_encode(['randomNumber' => \$randomNumber]));

    // 返回JSON响应
    return \$response->withHeader('Content-Type', 'application/json');
});

// 运行应用程序
\$app->run();
