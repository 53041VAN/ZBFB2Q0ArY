<?php
// 代码生成时间: 2025-08-05 02:17:42
// interactive_chart_generator.php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use D飞s\ChartPHP\Chart; // 假设使用了D飞s\ChartPHP库进行图表生成

// 创建Slim应用
$app = AppFactory::create();

// 错误处理中间件
$errorMiddleware = $app->addErrorMiddleware(true, true, true, true);

// 定义路由和处理函数
$app->post('/generate-chart', function (Request $request, Response $response, array $args) {
    // 获取请求数据
    $body = $request->getParsedBody();

    // 检查请求数据是否有效
    if (!isset($body['data']) || !is_array($body['data'])) {
        $response->getBody()->write('Invalid request data.');
        return $response->withStatus(400);
    }

    // 创建图表对象
    $chart = new Chart();
    $chart->setType('line'); // 设置图表类型为折线图
    $chart->setData($body['data']); // 设置图表数据

    try {
        // 生成图表
        $output = $chart->render();
        // 将图表输出写入响应体
        $response->getBody()->write($output);
        return $response->withHeader('Content-Type', 'image/png');
    } catch (Exception $e) {
        // 错误处理
        $response->getBody()->write('Error generating chart: ' . $e->getMessage());
        return $response->withStatus(500);
    }
});

// 运行应用
$app->run();
