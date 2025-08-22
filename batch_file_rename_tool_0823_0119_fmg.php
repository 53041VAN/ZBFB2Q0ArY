<?php
// 代码生成时间: 2025-08-23 01:19:35
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

// 创建Slim应用
AppFactory::setInstance(AppFactory::create());

$app = AppFactory::getInstance();

// 路由：处理文件重命名请求
$app->post('/api/rename', function (Request $request, Response $response, array $args) {
    $body = $request->getParsedBody();
    $sourceDirectory = $body['sourceDirectory'] ?? null;
    $renameRules = $body['renameRules'] ?? [];
    
    // 检查必要参数
    if (!$sourceDirectory) {
        return $response->withStatus(400)
            ->withJson(['error' => 'Source directory is required']);
    }
    
    // 确保源目录存在
    if (!is_dir($sourceDirectory)) {
        return $response->withStatus(404)
            ->withJson(['error' => 'Source directory not found']);
    }
    
    // 重命名文件
    try {
        $renameResults = [];
        foreach ($renameRules as $rule) {
            $sourceFile = $sourceDirectory . '/' . $rule['oldName'];
            $targetFile = $sourceDirectory . '/' . $rule['newName'];
            
            if (file_exists($sourceFile)) {
                if (rename($sourceFile, $targetFile)) {
                    $renameResults[] = ['oldName' => $rule['oldName'], 'newName' => $rule['newName'], 'status' => 'success'];
                } else {
                    $renameResults[] = ['oldName' => $rule['oldName'], 'newName' => $rule['newName'], 'status' => 'failed'];
                }
            } else {
                $renameResults[] = ['oldName' => $rule['oldName'], 'newName' => $rule['newName'], 'status' => 'source file not found'];
            }
        }
    } catch (Exception $e) {
        return $response->withStatus(500)
            ->withJson(['error' => 'Error occurred: ' . $e->getMessage()]);
    }
    
    return $response->withJson($renameResults);
});

// 运行应用
$app->run();

// 批量文件重命名工具
//
// 该工具通过Slim框架接收文件重命名请求，并处理文件重命名操作。
// 请求体应包含源目录路径和重命名规则。
// 每个规则包含旧文件名和新文件名。
// 使用POST请求发送到/api/rename端点。
//
// 示例请求体：
// {
//     "sourceDirectory": "/path/to/source",
//     "renameRules": [
//         {
//             "oldName": "file1.txt",
//             "newName": "file1_renamed.txt"
//         },
//         {
//             "oldName": "file2.txt",
//             "newName": "file2_renamed.txt"
//         }
//     ]
// }
