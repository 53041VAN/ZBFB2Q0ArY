<?php
// 代码生成时间: 2025-09-09 10:03:00
// 数据库迁移工具
// 使用SLIM框架实现一个简单的数据库迁移工具
// 遵循PHP最佳实践，确保代码的可维护性和可扩展性

require 'vendor/autoload.php';

// 设置数据库连接参数
define('DB_HOST', 'localhost');
define('DB_NAME', 'migration_tool');
define('DB_USER', 'root');
define('DB_PASS', '');

// 创建数据库连接
function getDbConnection() {
    try {
        $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die('数据库连接失败: ' . $e->getMessage());
    }
}

// 获取迁移文件路径
function getMigrationFilesPath() {
    return __DIR__ . '/migrations';
}

// 执行数据库迁移
function migrate($db) {
    $files = scandir(getMigrationFilesPath());
    $migrationFiles = array_filter($files, function($file) {
        return preg_match('/^\d+_.*\.sql$/', $file);
    });

    // 按文件名排序
    usort($migrationFiles, function($a, $b) {
        return strcasecmp($a, $b);
    });

    foreach ($migrationFiles as $file) {
        $sql = file_get_contents(getMigrationFilesPath() . '/' . $file);
        try {
            $db->exec($sql);
            echo 'Migration ' . $file . ' 执行成功' . PHP_EOL;
        } catch (PDOException $e) {
            echo 'Migration ' . $file . ' 执行失败: ' . $e->getMessage() . PHP_EOL;
        }
    }
}

// 初始化SLIM框架
$app = new \Slim\App();

// 定义迁移路由
$app->get('/migrate', function ($request, $response, $args) {
    $db = getDbConnection();
    try {
        migrate($db);
        $response->getBody()->write('Database migration successful');
    } catch (Exception $e) {
        $response->getBody()->write('Database migration failed: ' . $e->getMessage());
    }
    return $response;
});

// 运行SLIM应用
$app->run();