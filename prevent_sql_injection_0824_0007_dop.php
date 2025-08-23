<?php
// 代码生成时间: 2025-08-24 00:07:50
// 防止SQL注入 - 使用SLIM框架
require 'vendor/autoload.php';

// 创建Slim实例
$app = new \Slim\Slim();

// 数据库配置
$dbHost     = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName     = 'test';

// 创建PDO连接
try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// 定义防止SQL注入的路由
$app->get('/prevent-sql-injection/:id', function ($id) use ($app, $pdo) {

    // 错误处理
    try {
        // 预处理SQL语句，防止SQL注入
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        // 获取查询结果
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 检查是否找到用户
        if ($user) {
            $app->response->status(200);
            $app->response()->header("Content-Type", "application/json");
            echo json_encode($user);
        } else {
            $app->response->status(404);
            $app->response()->header("Content-Type", "application/json");
            echo json_encode(array("error" => "User not found"));
        }
    } catch (PDOException $e) {
        // 错误处理
        $app->response->status(500);
        $app->response()->header("Content-Type", "application/json");
        echo json_encode(array("error" => "Error occurred: " . $e->getMessage()));
    }

});

// 运行Slim应用
$app->run();
