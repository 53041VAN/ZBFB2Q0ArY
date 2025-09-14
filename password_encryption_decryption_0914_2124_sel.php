<?php
// 代码生成时间: 2025-09-14 21:24:30
// 使用Slim框架创建密码加密解密工具
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// 定义应用
$app = AppFactory::create();

// 密码加密中间件
$app->add(function ($request, $handler) {
    \$params = $request->getParsedBody();
    if (isset($params['password'])) {
        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
    }
    $request = $request->withParsedBody($params);
    return $handler->handle($request);
});

// 加密密码的路由
$app->post('/encrypt', function (Request \$request, Response \$response, \$args) {
    \$params = \$request->getParsedBody();
    \$encryptedPassword = isset(\$params['password']) ? \$params['password'] : null;
    if (!\$encryptedPassword) {
        \$response->getBody()->write('Password is required');
        return \$response->withStatus(400);
    }
    \$response->getBody()->write('Encrypted password: ' . \$encryptedPassword);
    return \$response->withStatus(200);
});

// 解密密码的路由
$app->post('/decrypt', function (Request \$request, Response \$response, \$args) {
    \$params = \$request->getParsedBody();
    \$encryptedPassword = isset(\$params['password']) ? \$params['password'] : null;
    if (!\$encryptedPassword) {
        \$response->getBody()->write('Password is required');
        return \$response->withStatus(400);
    }
    if (\$password = password_verify('password123', \$encryptedPassword)) {
        \$response->getBody()->write('Decrypted password: ' . \$password);
    } else {
        \$response->getBody()->write('Invalid password');
        return \$response->withStatus(401);
    }
    return \$response->withStatus(200);
});

// 运行应用
$app->run();
