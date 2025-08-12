<?php
// 代码生成时间: 2025-08-13 07:14:41
// 使用Slim框架创建数据分析器
require 'vendor/autoload.php';

use Slim\Factory\AppFactory';
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

// 定义数据分析器类
class DataAnalysisService {
    public function calculateMean($numbers) {
        if(empty($numbers)) {
            throw new InvalidArgumentException('No data provided');
        }
        $sum = array_sum($numbers);
        $count = count($numbers);
        return $sum / $count;
    }

    public function calculateMedian($numbers) {
        if(empty($numbers)) {
            throw new InvalidArgumentException('No data provided');
        }
        sort($numbers);
        $count = count($numbers);
        $middleIndex = floor(($count - 1) / 2);
        if($count % 2) {
            return $numbers[$middleIndex];
        } else {
            return ($numbers[$middleIndex] + $numbers[$middleIndex + 1]) / 2;
        }
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 注册数据分析器服务到容器
$container = $app->getContainer();
$container['dataAnalysisService'] = function($c) {
    return new DataAnalysisService();
};

// 定义路由处理数据
$app->post('/calculate', function (Request $request, Response $response, $args) {
    $body = $request->getParsedBody();
    $numbers = $body['numbers'] ?? [];

    $service = $this->get('dataAnalysisService');
    try {
        $mean = $service->calculateMean($numbers);
        $median = $service->calculateMedian($numbers);

        $response->getBody()->write(json_encode(['mean' => $mean, 'median' => $median]));
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        $response->withStatus(400);
    }

    return $response
        ->withHeader('Content-Type', 'application/json');
});

// 运行应用
$app->run();

// 注释说明：
// 1. 使用Slim框架和Psr容器
// 2. 定义数据分析器类，包含计算平均值和中位数的方法
// 3. 注册数据分析器服务到Slim容器
// 4. 定义POST路由，处理数据分析请求
// 5. 在请求处理中，调用数据分析器服务计算平均值和中位数
// 6. 返回JSON格式的响应，并包含错误处理