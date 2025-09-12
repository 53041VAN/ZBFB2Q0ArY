<?php
// 代码生成时间: 2025-09-13 04:20:11
// 使用Slim框架创建RESTful API服务
require 'vendor/autoload.php';

$app = new \Slim\App();

// 数据模型类
class UserModel {
    // 数据存储
    private static $dataStore = [];

    // 获取用户列表
    public static function getAllUsers() {
        return self::$dataStore;
    }

    // 添加用户
    public static function addUser($user) {
        self::$dataStore[] = $user;
    }

    // 获取单个用户
    public static function getUser($id) {
        foreach (self::$dataStore as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    // 更新用户信息
    public static function updateUser($id, $user) {
        foreach (self::$dataStore as &$storedUser) {
            if ($storedUser['id'] === $id) {
                $storedUser = $user;
                return;
            }
        }
    }

    // 删除用户
    public static function deleteUser($id) {
        foreach (self::$dataStore as &$user) {
            if ($user['id'] === $id) {
                unset($user);
                break;
            }
        }
        self::$dataStore = array_values(self::$dataStore);
    }
}

// 错误处理中间件
$app->add(function ($request, $response, $next) {
    try {
        $response = $next($request, $response);
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(500);
    }
    return $response;
});

// 用户路由
$app->get('/users', function ($request, $response, $args) {
    $response->getBody()->write(json_encode(UserModel::getAllUsers()));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/users', function ($request, $response, $args) {
    $user = $request->getParsedBody();
    UserModel::addUser($user);
    $response->getBody()->write(json_encode($user));
    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
});

$app->get('/users/{id}', function ($request, $response, $args) {
    $user = UserModel::getUser($args['id']);
    if ($user) {
        $response->getBody()->write(json_encode($user));
    } else {
        $response->getBody()->write(json_encode(['error' => 'User not found']));
        $response = $response->withStatus(404);
    }
    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/users/{id}', function ($request, $response, $args) {
    $user = $request->getParsedBody();
    UserModel::updateUser($args['id'], $user);
    $response->getBody()->write(json_encode($user));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->delete('/users/{id}', function ($request, $response, $args) {
    UserModel::deleteUser($args['id']);
    $response->getBody()->write(json_encode(['message' => 'User deleted']));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();