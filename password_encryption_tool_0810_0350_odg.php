<?php
// 代码生成时间: 2025-08-10 03:50:55
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Slim\Psr7\ServerRequest;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/vendor/autoload.php';

// 密码加密解密类
class PasswordUtil {
    public static function encryptPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}

// 错误处理器
class ErrorHandler {
    public static function handle($message, $code = 400) {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response->withStatus($code);
    }
}

$app = AppFactory::create();

// 添加加密密码路由
$app->post('加密/{password:[\w\W]+}');
$app->map(['POST', '加密'], '/encrypt/{password}', function (Request $request, Response $response, $args) {
    try {
        $password = $args['password'];
        $encryptedPassword = PasswordUtil::encryptPassword($password);
        $response->getBody()->write(json_encode(['message' => 'Encrypted password', 'password' => $encryptedPassword]));
        return $response->withStatus(200);
    } catch (Exception $e) {
        return ErrorHandler::handle('Error encrypting password', 500);
    }
});

// 添加解密密码路由
$app->post('解密/{hash:[\w\W]+}');
$app->map(['POST', '解密'], '/verify/{hash}', function (Request $request, Response $response, $args) {
    try {
        $hash = $args['hash'];
        $password = $request->getParsedBody()['password'] ?? '';
        $isValid = PasswordUtil::verifyPassword($password, $hash);
        $response->getBody()->write(json_encode(['message' => 'Verification result', 'isValid' => $isValid]));
        return $response->withStatus(200);
    } catch (Exception $e) {
        return ErrorHandler::handle('Error verifying password', 500);
    }
});

// 运行应用
$app->run();