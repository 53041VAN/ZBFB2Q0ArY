<?php
// 代码生成时间: 2025-08-27 05:24:36
// 使用Slim框架创建一个数学计算工具集服务
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App();

// 添加除法功能，需要检查除数不能为0
$app->get('/divide/{dividend}/{divisor}', function (Request $request, Response $response, $args) {
    $dividend = $args['dividend'];
    $divisor = $args['divisor'];

    if ($divisor == 0) {
        return $response->withJson(['error' => 'Divisor cannot be zero'], 400);
    }

    $result = $dividend / $divisor;

    return $response->withJson(['result' => $result], 200);
});

// 添加加法功能
$app->get('/add/{num1}/{num2}', function (Request $request, Response $response, $args) {
    $num1 = $args['num1'];
    $num2 = $args['num2'];

    $result = $num1 + $num2;

    return $response->withJson(['result' => $result], 200);
});

// 添加减法功能
$app->get('/subtract/{num1}/{num2}', function (Request $request, Response $response, $args) {
    $num1 = $args['num1'];
    $num2 = $args['num2'];

    $result = $num1 - $num2;

    return $response->withJson(['result' => $result], 200);
});

// 添加乘法功能
$app->get('/multiply/{num1}/{num2}', function (Request $request, Response $response, $args) {
    $num1 = $args['num1'];
    $num2 = $args['num2'];

    $result = $num1 * $num2;

    return $response->withJson(['result' => $result], 200);
});

// 运行Slim应用
$app->run();
