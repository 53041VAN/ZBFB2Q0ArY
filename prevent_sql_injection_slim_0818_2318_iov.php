<?php
// 代码生成时间: 2025-08-18 23:18:53
use Psr\Container\ContainerInterface as Container;
use Slim\Factory\AppFactory;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

// 配置数据库连接
$dbConfig = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'dbname' => 'your_database',
    'user' => 'your_username',
    'pass' => 'your_password',
    'charset' => 'utf8mb4',
];

require_once 'vendor/autoload.php';

// 创建Slim应用
$app = AppFactory::create();

// 设置依赖注入容器
$container = new Container();
$container->set(PDO::class, function () use ($dbConfig) {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $dbConfig['host'],
        $dbConfig['port'],
        $dbConfig['dbname'],
        $dbConfig['charset']
    );
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    return new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], $options);
});

$app->get('/[{name}]', function (Request $request, Response $response, $args) use ($container) {
    // 获取用户输入
    $name = $request->getAttribute('name');

    try {
        // 获取数据库连接
        $db = $container->get(PDO::class);

        // 准备SQL语句
        $stmt = $db->prepare('SELECT * FROM users WHERE name = :name');
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);

        // 执行查询
        $stmt->execute();

        // 获取结果
        $user = $stmt->fetch();

        if ($user) {
            $response->getBody()->write('User found: ' . $user['name']);
        } else {
            $response->getBody()->write('User not found.');
        }
    } catch (PDOException $e) {
        // 错误处理
        $response->getBody()->write('Error: ' . $e->getMessage());
    }

    return $response;
});

$app->run();