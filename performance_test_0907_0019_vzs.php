<?php
// 代码生成时间: 2025-09-07 00:19:39
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 创建一个简单的性能测试类
class PerformanceTestController {
    public function runTest(Request $request, Response $response, $args) {
        // 获取请求参数
        $duration = $request->getQueryParam('duration', 10); // 默认持续时间为10秒
        $duration = (int) $duration;

        $response->getBody()->write("Starting performance test for {$duration} seconds...\
");

        // 记录开始时间
        $startTime = microtime(true);

        // 模拟长时间运行的任务
        while ((microtime(true) - $startTime) < $duration) {
            // 这里可以放置性能测试的代码，例如数据库操作、文件读写等
            // 为了演示，我们只是简单的等待一小段时间
            usleep(100000); // 等待100毫秒
        }

        $response->getBody()->write("Performance test completed.\
");

        // 返回响应
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'text/plain');
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 注册路由
$app->get('/test', 'PerformanceTestController:runTest');

// 运行应用
$app->run();