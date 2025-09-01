<?php
// 代码生成时间: 2025-09-01 10:33:25
// user_authentication.php
// 使用Slim框架实现的用户登录验证系统

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\{App, Flash};
use Slim\Http\StatusCode;

require __DIR__ . '/../vendor/autoload.php';

$app = new App(["settings" => ["displayErrorDetails" => true]]);

// 数据库配置（示例）
$app->getContainer()['db'] = function ($c) {
    return new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
};

// 用户登录路由
$app->post('/login', function (Request $request, Response $response, $args) {
    // 获取请求体中的数据
    $body = $request->getParsedBody();
    $username = $body['username'] ?? '';
    $password = $body['password'] ?? '';

    // 数据验证
    if (empty($username) || empty($password)) {
        Flash::addMessage($request, 'error', 'Username and password are required!');
        return $response->withStatus(StatusCode::HTTP_BAD_REQUEST)->withHeader('Location', '/login');
    }

    // 从数据库验证用户
    $db = $this->getContainer()->db;
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    // 检查用户是否存在以及密码是否匹配
    if ($user && password_verify($password, $user['password_hash'])) {
        // 登录成功，设置会话
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        Flash::addMessage($request, 'success', 'Logged in successfully!');
        return $response->withHeader('Location', '/');
    } else {
        // 登录失败，返回错误信息
        Flash::addMessage($request, 'error', 'Invalid username or password!');
        return $response->withStatus(StatusCode::HTTP_UNAUTHORIZED)->withHeader('Location', '/login');
    }
});

// 前端登录页面路由（示例）
$app->get('/login', function (Request $request, Response $response, $args) {
    // 渲染登录页面
    return $response->getBody()->write("<form method='post' action='/login'>Username: <input name='username'><br>Password: <input type='password' name='password'><br><button type='submit'>Login</button></form>");
});

// 运行应用
$app->run();
