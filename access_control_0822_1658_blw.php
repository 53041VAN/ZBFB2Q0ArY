<?php
// 代码生成时间: 2025-08-22 16:58:05
require 'vendor/autoload.php';

use Slim\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use Slim\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// 定义中间件来处理访问权限
class AccessMiddleware {
    public function __invoke(Request $request, Response $response, callable $next) {
        // 这里添加访问控制逻辑，例如检查用户角色或Token
        // 假设我们通过HTTP头的Authorization字段来检查Token
        $token = $request->getHeaderLine('Authorization');
        if (empty($token) || !$this->validateToken($token)) {
            $response = $response->withStatus(403)->withBody($response->getBody()->write('Access Denied'));
            return $next($request, $response);
        }

        return $next($request, $response);
    }

    private function validateToken($token) {
        // 这里添加Token验证逻辑，返回布尔值
        // 例如，可以检查token是否有效，是否过期等
        return true; // 假设所有token都是有效的
    }
}

// 定义一个简单的路由来演示中间件的使用
$app = \Slim\Factory\AppFactory::create();

$app->add(new AccessMiddleware());

// 定义一个受保护的路由
$app->get('/protected-route', function (Request $request, Response $response, $args) {
    return $response->getBody()->write('This is a protected route');
});

// 定义一个不受保护的路由
$app->get('/public-route', function (Request $request, Response $response, $args) {
    return $response->getBody()->write('This is a public route');
});

$app->run();
