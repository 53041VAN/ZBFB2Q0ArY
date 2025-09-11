<?php
// 代码生成时间: 2025-09-12 00:13:50
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// ProcessManager类，用于处理进程管理相关功能
class ProcessManager {
    public function listProcesses(Request $request, Response $response, array $args) {
        // 获取所有正在运行的进程
        $processes = `ps aux`;

        // 将进程信息按照一定的格式输出
        $response->getBody()->write("<pre>" . htmlspecialchars($processes) . "</pre>");
        return $response;
    }

    public function killProcess(Request $request, Response $response, array $args) {
        $pid = $request->getParsedBody()['pid'] ?? null;
        if (!$pid) {
            return $response->withStatus(400, 'Missing PID in request body');
        }

        // 尝试终止进程
        if (exec("kill -9 $pid") === false) {
            return $response->withStatus(500, 'Failed to kill process');
        }

        return $response->withStatus(200, 'Process killed successfully');
    }
}

// 设置Slim应用程序
$app = AppFactory::create();

// 添加路由以列出所有进程
$app->get('/processes', ProcessManager::class . ':listProcesses');

// 添加路由以终止一个进程
$app->post('/kill-process', ProcessManager::class . ':killProcess');

// 运行应用程序
$app->run();
