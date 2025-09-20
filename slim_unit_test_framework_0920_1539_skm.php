<?php
// 代码生成时间: 2025-09-20 15:39:35
// 引入Slim框架
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPUnit\Framework\TestCase;

// 定义一个简单的服务作为测试对象
class UserService {
    public function getUser($id) {
        return ['id' => $id, 'name' => 'John Doe'];
    }
}

// 创建一个单元测试类
class UserServiceTest extends TestCase {
    private $userService;

    protected function setUp(): void {
        $this->userService = new UserService();
    }

    public function testGetUser() {
        $user = $this->userService->getUser(1);
        $this->assertEquals(1, $user['id']);
        $this->assertEquals('John Doe', $user['name']);
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 定义一个路由调用UserService的getUser方法
$app->get('/user/{id}', function (Request $request, Response $response, $args) {
    try {
        $userService = new UserService();
        $user = $userService->getUser($args['id']);
        return $response->withJson($user);
    } catch (Exception $e) {
        return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
    }
});

// 运行Slim应用
$app->run();