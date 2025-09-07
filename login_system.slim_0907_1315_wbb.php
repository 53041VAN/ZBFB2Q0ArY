<?php
// 代码生成时间: 2025-09-07 13:15:31
// 使用 Composer 自动加载依赖
require 'vendor/autoload.php';

// 引入 Slim 框架的中间件
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;
use Slim\Exception\HttpInternalServerErrorException;

// 定义用户模型，用于存储用户信息
class User {
    public $username;
    public $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }
}

// 登录服务类，处理登录逻辑
class LoginService {
    private $users;

    public function __construct() {
        // 假设这里存储了一些用户数据
        $this->users = [
            new User('admin', 'admin123'),
            new User('user', 'password')
        ];
    }

    public function authenticate($username, $password) {
        foreach ($this->users as $user) {
            if ($user->username === $username && $user->password === $password) {
                return true;
            }
        }
        return false;
    }
}

// 创建 Slim 应用
$app = AppFactory::create();

// 登录路由
$app->post('/api/login', function (Request $request, Response $response, $args) {
    $body = $request->getParsedBody();
    $username = $body['username'] ?? null;
    $password = $body['password'] ?? null;

    if ($username === null || $password === null) {
        return $response
            ->withStatus(400)
            ->withJson(['error' => 'Missing username or password']);
    }

    $loginService = new LoginService();
    $isAuthenticated = $loginService->authenticate($username, $password);

    if (!$isAuthenticated) {
        return $response
            ->withStatus(401)
            ->withJson(['error' => 'Authentication failed']);
    }

    return $response
        ->withStatus(200)
        ->withJson(['message' => 'Login successful']);
});

// 运行应用
$app->run();
