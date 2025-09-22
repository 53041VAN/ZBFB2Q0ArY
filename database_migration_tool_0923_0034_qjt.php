<?php
// 代码生成时间: 2025-09-23 00:34:29
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Doctrine\DBAL\Tools\Cli\Shell;
use Doctrine\DBAL\Tools\Console\Command as DbalCommands;

// 定义常量
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('DB_NAME', 'your_database_name');

// 设置数据库连接信息
$config = [
    'driver' => 'pdo_mysql',
    'host' => DB_HOST,
    'user' => DB_USER,
    'password' => DB_PASS,
    'dbname' => DB_NAME
];

// 创建Slim应用
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// 错误处理中间件
$app->addErrorMiddleware(true, true, true, null, 
    function ($request, $response, $exception) {
        return $response->withJson(['error' => $exception->getMessage()], 500);
    }
);

// 获取数据库连接
$db = \Doctrine\DBAL\DriverManager::getConnection($config);

// 创建Shell并注册命令
$shell = new Shell();
$shell->addCommands([
    DbalCommands\Migration\GenerateCommand::class,
    DbalCommands\Migration\DiffCommand::class,
    DbalCommands\Migration\ExecuteCommand::class,
    DbalCommands\Migration\StatusCommand::class,
    DbalCommands\Migration\VersionCommand::class,
    DbalCommands\Migration\LatestCommand::class,
    DbalCommands\Migration\MigrateCommand::class,
    DbalCommands\Migration\RollupCommand::class,
    DbalCommands\Migration\SyncMetadataCommand::class
]);

// 定义路由
$app->get('/migrate', function (Request $request, Response $response, $args) use ($db, $shell) {
    try {
        // 执行数据库迁移
        $shell->runShell();
        return $response->withJson(['message' => 'Migration successful.'], 200);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

// 运行应用
$app->run();
