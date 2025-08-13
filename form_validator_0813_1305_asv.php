<?php
// 代码生成时间: 2025-08-13 13:05:07
// 使用Slim框架创建表单数据验证器
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Respect\Validation\Validator as v;

// 定义表单验证规则
$validationRules = [
    'username' => v::notEmpty()->alpha(' ')
        ->length(3, 20),
    'email' => v::email(),
    'password' => v::stringType()->length(8, 20),
    'age' => v::intType()->positive(),
];

// 创建Slim应用
$app = AppFactory::create();

// 表单处理中间件
$app->add(function (Request $request, Response $response, callable $next) use ($validationRules) {
    $body = $request->getParsedBody();
    $errors = [];
    
    // 遍历验证规则，验证表单数据
    foreach ($validationRules as $field => $rule) {
        if (empty($body[$field])) {
            $errors[$field] = 'Field is required.';
        } elseif (!$rule->validate($body[$field])) {
            $errors[$field] = 'Invalid input.';
        }
    }
    
    // 检查是否有错误
    if (!empty($errors)) {
        $response->getBody()->write(json_encode(['errors' => $errors]));
        return $response->withStatus(400);
    }
    
    return $next($request, $response);
});

// 表单数据处理路由
$app->post('/api/form', function (Request $request, Response $response) use ($validationRules) {
    $body = $request->getParsedBody();
    
    // 表单数据通过验证，进行处理
    // TODO: 实现具体的业务逻辑
    
    $response->getBody()->write(json_encode(['message' => 'Form submitted successfully.']));
    return $response->withStatus(200);
});

// 运行Slim应用
$app->run();