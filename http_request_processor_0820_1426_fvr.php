<?php
// 代码生成时间: 2025-08-20 14:26:18
// 使用Composer的autoload自动加载Slim框架
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;

// 创建Slim应用
AppFactory::setCodingStyle(array(
    'namespace' => '\SlimApp',
    'invokableResolve' => true,
));

// 创建一个应用实例
$app = AppFactory::create();

// 定义中间件来处理错误
$app->addErrorMiddleware(true, true, true);

// 定义GET路由
$app->get('/', 'SlimApp\Controller\HomeController:indexAction');

// 定义POST路由
$app->post('/api/data', 'SlimApp\Controller\DataController:postDataAction');

// 运行应用
$app->run();

// 定义控制器命名空间
namespace SlimApp\Controller;

// HomeController类，用于处理GET请求
class HomeController {
    // indexAction方法，返回欢迎信息
    public function indexAction($request, $response, $args) {
        return $response->write("Welcome to Slim Framework!");
    }
}

// DataController类，用于处理POST请求
class DataController {
    // postDataAction方法，处理POST数据并返回响应
    public function postDataAction($request, $response, $args) {
        // 获取POST数据
        $postData = $request->getParsedBody();
        // 检查是否接收到数据
        if (empty($postData)) {
            return $response->withJson(['error' => 'No data provided'], 400);
        }
        // 处理数据（示例：返回相同的数据）
        $response->getBody()->write(json_encode($postData));
        // 设置响应头，返回JSON格式的响应
        return $response->withHeader('Content-Type', 'application/json');
    }
}
