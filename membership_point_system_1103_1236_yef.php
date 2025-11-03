<?php
// 代码生成时间: 2025-11-03 12:36:32
// MembershipPointSystem.php
// 在Slim框架中创建会员积分系统的基本结构

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Response;
use Slim\Container;

require 'vendor/autoload.php';

$app = new \Slim\App(["settings" => [
    'displayErrorDetails' => true, // 在开发环境开启错误详情
    'db' => [
        'host' => 'localhost',
        'dbname' => 'membership',
        'user' => 'root',
        'pass' => 'password',
        'charset' => 'utf8mb4'
    ]
]
);

// 依赖注入
$container = $app->getContainer();
$container['db'] = function($c) {
    $settings = $c['settings']['db'];
    $pdo = new PDO("mysql:host={$settings['host']};dbname={$settings['dbname']};charset={$settings['charset']}",
        $settings['user'], $settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// 会员积分控制器
$app->group('/points', function () use ($app) {
    // 获取会员积分
    $app->get('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        try {
            $db = $this->get('db');
            $stmt = $db->prepare('SELECT points FROM members WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $memberPoints = $stmt->fetch();
            if (!$memberPoints) {
                return $response->withJson(['error' => 'Member not found.'], 404);
            }
            return $response->withJson(['points' => $memberPoints['points']]);
        } catch (Exception $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
    });

    // 添加会员积分
    $app->post('/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $body = $request->getParsedBody();
        $pointsToAdd = $body['points'] ?? 0;
        try {
            $db = $this->get('db');
            $stmt = $db->prepare('SELECT points FROM members WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $memberPoints = $stmt->fetch();
            if (!$memberPoints) {
                return $response->withJson(['error' => 'Member not found.'], 404);
            }
            $newPoints = $memberPoints['points'] + $pointsToAdd;
            $db->beginTransaction();
            $stmt = $db->prepare('UPDATE members SET points = :points WHERE id = :id');
            $stmt->execute(['points' => $newPoints, 'id' => $id]);
            $db->commit();
            return $response->withJson(['points' => $newPoints], 201);
        } catch (Exception $e) {
            $db->rollBack();
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
    });
});

$app->run();