<?php
// 代码生成时间: 2025-10-14 01:36:20
// 引入Slim框架
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个简单的原子交换协议实现
class AtomicExchange {
    private $value;

    // 构造函数，初始化值
    public function __construct($initialValue) {
        $this->value = $initialValue;
    }

    // 获取当前值
    public function getValue() {
        return $this->value;
    }

    // 原子交换方法
    public function exchange($newValue) {
        if (!is_int($newValue)) {
            throw new InvalidArgumentException('New value must be an integer');
        }

        $this->value = $newValue;
    }
}

// 创建一个Slim应用
$app = AppFactory::create();

// 创建原子交换对象
$atomicExchange = new AtomicExchange(0);

// 定义GET路由，用于获取当前值
$app->get('/value', function (Request $request, Response $response, $args) use ($atomicExchange) {
    $response->getBody()->write((string) $atomicExchange->getValue());
    return $response;
});

// 定义POST路由，用于交换值
$app->post('/exchange', function (Request $request, Response $response, $args) use ($atomicExchange) {
    try {
        $newVal = (int) $request->getParsedBody()['newVal'];
        $atomicExchange->exchange($newVal);
        $response->getBody()->write((string) $atomicExchange->getValue());
    } catch (InvalidArgumentException $e) {
        $response = $response->withStatus(400);
        $response->getBody()->write($e->getMessage());
    } catch (Exception $e) {
        $response = $response->withStatus(500);
        $response->getBody()->write('Internal Server Error');
    }
    return $response;
});

// 运行应用
$app->run();
