<?php
// 代码生成时间: 2025-08-14 22:07:51
use Psr\Container\ContainerInterface as Container;
use Slim\Factory\AppFactory;
use PDO;

require __DIR__ . '/../vendor/autoload.php';

// 创建 Slim 应用
AppFactory::setContainer($container = new class($c = []) extends \Slim\Container {
    public function get($id) {
        return $this->$id ?: ($this->$id = $c[$id] ?? null);
    }
});
$app = AppFactory::create();

// 数据库配置
$container['settings']['db'] = function (Container $c) {
    return new PDO(
        'mysql:host=localhost;dbname=my_database',
        'username',
        'password',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
};

// 路由定义
$app->get('/users', function (Request $request, Response $response, Argument $args) {
    $db = $this->get('settings')['db'];
    $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$request->getQueryParams()['id'] ?? null]);
    $user = $stmt->fetch();
    if (!$user) {
        return $response->withJson(['error' => 'User not found'], 404);
    }
    return $response->withJson($user);
});

// 错误处理中间件
$app->addErrorMiddleware(true, true, true);

// 运行应用
$app->run();

/*
 * 防止SQL注入示例
 * 使用PDO预处理语句和参数绑定来防止SQL注入。
 * 通过这种方式，即使输入参数被恶意修改，也不会对数据库安全构成威胁。
 */
