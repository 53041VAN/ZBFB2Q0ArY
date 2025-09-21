<?php
// 代码生成时间: 2025-09-21 14:50:17
// 数据模型设计
// 使用Slim框架创建RESTful API
// 代码结构清晰，易于理解，包含适当的错误处理，遵循PHP最佳实践
# 改进用户体验

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 创建Slim应用
AppFactory::setCodingStandard(array('PSR-1', 'PSR-2'));
\$app = AppFactory::create();

// 数据模型
# NOTE: 重要实现细节
class UserModel {
    private \$db;
# 添加错误处理
    public function __construct(\$db) {
        \$this->db = \$db;
    }

    // 获取所有用户
    public function getAllUsers() {
        try {
            \$query = "SELECT * FROM users";
            \$statement = \$this->db->prepare(\$query);
            \$statement->execute();
            return \$statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException \$e) {
            http_response_code(500);
            return \$e->getMessage();
# NOTE: 重要实现细节
        }
    }
# TODO: 优化性能

    // 获取单个用户
    public function getUserById(\$id) {
        try {
            \$query = "SELECT * FROM users WHERE id = :id";
# 扩展功能模块
            \$statement = \$this->db->prepare(\$query);
            \$statement->bindParam(':id', \$id, PDO::PARAM_INT);
            \$statement->execute();
            return \$statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException \$e) {
            http_response_code(500);
            return \$e->getMessage();
        }
    }
# NOTE: 重要实现细节

    // 添加新用户
    public function addUser(\$name, \$email) {
        try {
            \$query = "INSERT INTO users (name, email) VALUES (:name, :email)";
# 优化算法效率
            \$statement = \$this->db->prepare(\$query);
            \$statement->bindParam(':name', \$name);
# FIXME: 处理边界情况
            \$statement->bindParam(':email', \$email);
            \$statement->execute();
            return \$this->db->lastInsertId();
        } catch (PDOException \$e) {
            http_response_code(500);
            return \$e->getMessage();
        }
    }

    // 更新用户信息
    public function updateUser(\$id, \$name, \$email) {
        try {
            \$query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
            \$statement = \$this->db->prepare(\$query);
# 扩展功能模块
            \$statement->bindParam(':id', \$id, PDO::PARAM_INT);
            \$statement->bindParam(':name', \$name);
            \$statement->bindParam(':email', \$email);
            \$statement->execute();
            return \$statement->rowCount();
        } catch (PDOException \$e) {
            http_response_code(500);
            return \$e->getMessage();
        }
    }

    // 删除用户
    public function deleteUser(\$id) {
        try {
            \$query = "DELETE FROM users WHERE id = :id";
            \$statement = \$this->db->prepare(\$query);
# NOTE: 重要实现细节
            \$statement->bindParam(':id', \$id, PDO::PARAM_INT);
            \$statement->execute();
# NOTE: 重要实现细节
            return \$statement->rowCount();
        } catch (PDOException \$e) {
            http_response_code(500);
            return \$e->getMessage();
        }
# 添加错误处理
    }
}

// 数据库连接
\$db = new PDO('mysql:host=localhost;dbname=your_db', 'your_username', 'your_password');
\$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 创建用户模型实例
\$userModel = new UserModel(\$db);

// GET /users
\$app->get('/users', function (Request \$request, Response \$response) use (\$userModel) {
    \$users = \$userModel->getAllUsers();
    return \$response->getBody()->write(json_encode(\$users));
# 改进用户体验
});

// GET /users/{id}
\$app->get('/users/{id}', function (Request \$request, Response \$response, \$args) use (\$userModel) {
# 改进用户体验
    \$userId = \$args['id'];
    \$user = \$userModel->getUserById(\$userId);
    if (!\$user) {
        \$response = \$response->withStatus(404);
        return \$response->getBody()->write(json_encode(array('error' => 'User not found')));
    }
    return \$response->getBody()->write(json_encode(\$user));
});
# FIXME: 处理边界情况

// POST /users
# 添加错误处理
\$app->post('/users', function (Request \$request, Response \$response) use (\$userModel) {
    \$data = \$request->getParsedBody();
    \$userId = \$userModel->addUser(\$data['name'], \$data['email']);
    if (!\$userId) {
        \$response = \$response->withStatus(400);
# 添加错误处理
        return \$response->getBody()->write(json_encode(array('error' => 'Failed to add user')));
    }
    return \$response->getBody()->write(json_encode(array('success' => 'User added', 'id' => \$userId)));
});

// PUT /users/{id}
\$app->put('/users/{id}', function (Request \$request, Response \$response, \$args) use (\$userModel) {
    \$userId = \$args['id'];
    \$data = \$request->getParsedBody();
    \$updateCount = \$userModel->updateUser(\$userId, \$data['name'], \$data['email']);
    if (!\$updateCount) {
        \$response = \$response->withStatus(400);
# 扩展功能模块
        return \$response->getBody()->write(json_encode(array('error' => 'Failed to update user')));
    }
    return \$response->getBody()->write(json_encode(array('success' => 'User updated')));
});
# FIXME: 处理边界情况

// DELETE /users/{id}
\$app->delete('/users/{id}', function (Request \$request, Response \$response, \$args) use (\$userModel) {
# 优化算法效率
    \$userId = \$args['id'];
    \$deleteCount = \$userModel->deleteUser(\$userId);
    if (!\$deleteCount) {
        \$response = \$response->withStatus(400);
        return \$response->getBody()->write(json_encode(array('error' => 'Failed to delete user')));
    }
    return \$response->getBody()->write(json_encode(array('success' => 'User deleted')));
});

// 运行应用
# TODO: 优化性能
\$app->run();