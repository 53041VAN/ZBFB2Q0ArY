<?php
// 代码生成时间: 2025-08-04 11:10:49
// 数据分析应用
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

// 创建应用
AppFactory::setCodingStylePreset(AppFactory::CODING_STYLE_PRESERVE);
$app = AppFactory::create();

// 中间件，用于错误处理
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    if ($response->getStatusCode() >= 400) {
        $response->getBody()->write('Error: ' . $response->getStatusCode());
    }
    return $response;
});

// 路由1：获取分析数据
$app->get('/data', function (Request $request, Response $response, array $args) {
    // 模拟获取数据
    $data = ['data1' => 10, 'data2' => 20, 'data3' => 30];
    
    // 计算总和
    $total = array_sum($data);
    
    // 计算平均值
    $average = $total / count($data);
    
    // 响应数据
    $response->getBody()->write(json_encode(['total' => $total, 'average' => $average]));
    return $response->withHeader('Content-Type', 'application/json');
});

// 路由2：获取分析数据详情
$app->get('/data/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    
    // 模拟数据
    $data = ['data1' => 10, 'data2' => 20, 'data3' => 30];
    
    // 错误处理
    if (!array_key_exists($id, $data)) {
        return $response->withStatus(404)->getBody()->write('Data not found');
    }
    
    // 响应数据
    $response->getBody()->write(json_encode(['id' => $id, 'value' => $data[$id]]));
    return $response->withHeader('Content-Type', 'application/json');
});

// 运行应用
$app->run();