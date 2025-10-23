<?php
// 代码生成时间: 2025-10-23 19:40:18
// 使用Slim框架创建内容管理系统
require 'vendor/autoload.php';

// 创建Slim应用
$app = new \Slim\App();

// 定义数据库连接
$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'dbname' => 'content_management_system'
];

// 定义数据库类
class Database {
    protected \$connection;

    public function __construct(\$host, \$user, \$pass, \$dbname) {
        try {
            // 创建MySQL连接
            $this->connection = new PDO('mysql:host=' . \$host . ';dbname=' . \$dbname, \$user, \$pass);
            // 设置错误模式
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException \$e) {
            // 错误处理
            die('Database connection failed: ' . \$e->getMessage());
        }
    }

    // 获取数据库连接
    public function getConnection() {
        return \$this->connection;
    }
}

// 依赖注入
\$container = \$app->getContainer();
\$container['db'] = function (\$c) use (\$dbConfig) {
    return new Database(\$dbConfig['host'], \$dbConfig['user'], \$dbConfig['pass'], \$dbConfig['dbname']);
};

// 定义路由
// 获取所有内容
\$app->get('/api/content', function (\$request, \$response, \$args) {
    \$db = \$this->get('db')->getConnection();
    \$stmt = \$db->query('SELECT * FROM content');
    \$contents = \$stmt->fetchAll(PDO::FETCH_ASSOC);
    \$response->getBody()->write(json_encode(\$contents));
    return \$response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// 创建内容
\$app->post('/api/content', function (\$request, \$response, \$args) {
    \$contentData = \$request->getParsedBody();
    \$db = \$this->get('db')->getConnection();
    \$stmt = \$db->prepare('INSERT INTO content (title, body) VALUES (:title, :body)');
    \$stmt->bindParam(':title', \$contentData['title']);
    \$stmt->bindParam(':body', \$contentData['body']);
    \$stmt->execute();
    \$response->getBody()->write(json_encode(['message' => 'Content created successfully']));
    return \$response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
});

// 更新内容
\$app->put('/api/content/{id}', function (\$request, \$response, \$args) {
    \$contentData = \$request->getParsedBody();
    \$db = \$this->get('db')->getConnection();
    \$stmt = \$db->prepare('UPDATE content SET title=:title, body=:body WHERE id=:id');
    \$stmt->bindParam(':title', \$contentData['title']);
    \$stmt->bindParam(':body', \$contentData['body']);
    \$stmt->bindParam(':id', \$args['id']);
    \$stmt->execute();
    \$response->getBody()->write(json_encode(['message' => 'Content updated successfully']));
    return \$response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// 删除内容
\$app->delete('/api/content/{id}', function (\$request, \$response, \$args) {
    \$db = \$this->get('db')->getConnection();
    \$stmt = \$db->prepare('DELETE FROM content WHERE id=:id');
    \$stmt->bindParam(':id', \$args['id']);
    \$stmt->execute();
    \$response->getBody()->write(json_encode(['message' => 'Content deleted successfully']));
    return \$response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// 运行Slim应用
\$app->run();