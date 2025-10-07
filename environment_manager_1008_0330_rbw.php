<?php
// 代码生成时间: 2025-10-08 03:30:20
// 使用Slim框架创建一个简单的API来管理环境变量
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 环境变量管理器类
class EnvironmentManager {
    private $envVariables;

    public function __construct() {
        $this->envVariables = $_ENV;
    }

    // 获取所有环境变量
    public function getAll() {
        return $this->envVariables;
    }

    // 设置环境变量
    public function set($key, $value) {
        if ($this->validateKey($key)) {
            $this->envVariables[$key] = $value;
            return true;
        }
        return false;
    }

    // 验证环境变量的键名是否有效
    private function validateKey($key) {
        return is_string($key) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $key);
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 获取所有环境变量
$app->get('/env', function (Request $request, Response $response, $args) {
    $envManager = new EnvironmentManager();
    $envData = $envManager->getAll();
    return $response->getBody()->write(json_encode($envData));
});

// 设置环境变量
$app->post('/env/{key}', function (Request $request, Response $response, $args) {
    $envManager = new EnvironmentManager();
    $key = $args['key'];
    $body = $request->getParsedBody();
    $value = $body['value'] ?? null;

    if ($envManager->set($key, $value)) {
        return $response->withJson(['success' => true, 'message' => 'Environment variable set successfully.']);
    } else {
        return $response->withStatus(400)
            ->withJson(['success' => false, 'message' => 'Invalid key or value.']);
    }
});

// 运行应用
$app->run();