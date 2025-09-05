<?php
// 代码生成时间: 2025-09-05 17:36:21
// 使用Slim框架创建库存管理系统
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\ResponseBody;
use Slim\Psr7\Response;

// 定义库存类
class Inventory {
    private $items = [];

    public function __construct($items = []) {
        $this->items = $items;
    }

    public function add($item) {
        if (isset($this->items[$item['id']])) {
            throw new Exception('Item already exists in inventory');
        }
        $this->items[$item['id']] = $item;
        return $this->items[$item['id']];
    }

    public function remove($id) {
        if (!isset($this->items[$id])) {
            throw new Exception('Item not found in inventory');
        }
        unset($this->items[$id]);
    }

    public function update($id, $item) {
        if (!isset($this->items[$id])) {
            throw new Exception('Item not found in inventory');
        }
        $this->items[$id] = $item;
        return $this->items[$id];
    }

    public function getAll() {
        return $this->items;
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 添加库存项
$app->post('/inventory', function (Request $request, Response $response) {
    $inventory = new Inventory();
    $item = $request->getParsedBody();
    try {
        $addedItem = $inventory->add($item);
        return $response->withJson($addedItem, 201);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 400);
    }
});

// 删除库存项
$app->delete('/inventory/{id}', function (Request $request, Response $response, $args) {
    $inventory = new Inventory();
    try {
        $inventory->remove($args['id']);
        return $response->withJson(['message' => 'Item removed successfully'], 200);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 404);
    }
});

// 更新库存项
$app->put('/inventory/{id}', function (Request $request, Response $response, $args) {
    $inventory = new Inventory();
    $item = $request->getParsedBody();
    try {
        $updatedItem = $inventory->update($args['id'], $item);
        return $response->withJson($updatedItem, 200);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 404);
    }
});

// 获取所有库存项
$app->get('/inventory', function (Request $request, Response $response) {
    $inventory = new Inventory();
    $responseBody = new ResponseBody();
    $responseBody->write(json_encode($inventory->getAll()));
    return $response->withBody($responseBody);
});

// 运行应用
$app->run();
