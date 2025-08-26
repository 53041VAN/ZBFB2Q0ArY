<?php
// 代码生成时间: 2025-08-26 16:12:07
// 使用 Slim 框架防止 SQL 注入的示例程序
require 'vendor/autoload.php';

// 创建 Slim 应用
$app = new \Slim\App();

// 数据库配置
$dbConfig = [
    'host' => 'localhost',
    'user' => 'your_username',
    'pass' => 'your_password',
    'dbname' => 'your_database'
];

// 使用 PDO 连接数据库
try {
    $pdo = new PDO(
        'mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'],
        $dbConfig['user'],
        $dbConfig['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (\PDOException $e) {
    // 错误处理
    die('Database connection failed: ' . $e->getMessage());
}

// 定义 SQL 查询参数的正则表达式
$paramPattern = '/^[a-zA-Z0-9_]+$/';

// SQL 查询函数，防止 SQL 注入
function queryWithParams($pdo, $query, $params) {
    if (!preg_match($paramPattern, $query)) {
        throw new Exception('Invalid query');
    }
    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $value) {
        if (!preg_match($paramPattern, $key)) {
            throw new Exception('Invalid parameter key');
        }
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

// 定义 GET 请求处理程序
$app->get('/users', function ($request, $response, $args) use ($pdo) {
    $userId = $request->getQueryParams()['id'] ?? '';
    // 过滤用户输入
    if (!preg_match($paramPattern, $userId)) {
        return $response->withJson(['error' => 'Invalid user ID'], 400);
    }
    // 使用预处理语句防止 SQL 注入
    $results = queryWithParams($pdo, 'SELECT * FROM users WHERE id = :id', [':id' => $userId]);
    // 返回结果
    return $response->withJson($results);
});

// 定义 POST 请求处理程序
$app->post('/users', function ($request, $response, $args) use ($pdo) {
    $data = $request->getParsedBody();
    // 验证数据
    $errors = [];
    if (!isset($data['name']) || !preg_match($paramPattern, $data['name'])) {
        $errors['name'] = 'Name is required and must be alphanumeric';
    }
    if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email is required and must be a valid email';
    }
    if (!empty($errors)) {
        return $response->withJson(['errors' => $errors], 400);
    }
    // 插入用户数据到数据库
    $stmt = $pdo->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');
    $stmt->execute([':name' => $data['name'], ':email' => $data['email']]);
    // 返回结果
    return $response->withJson(['message' => 'User created successfully'], 201);
});

// 运行应用
$app->run();