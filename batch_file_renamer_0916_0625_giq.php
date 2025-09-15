<?php
// 代码生成时间: 2025-09-16 06:25:54
// 使用Slim框架实现的批量文件重命名工具
require 'vendor/autoload.php';

$app = new \Slim\App();

// 路由：上传文件并重命名
$app->post('/files/rename', function ($request, $response, $args) {
    $payload = $request->getParsedBody();
    $sourceDirectory = $payload['sourceDirectory'] ?? '';
    $targetDirectory = $payload['targetDirectory'] ?? '';
    $renamePattern = $payload['renamePattern'] ?? '';

    // 错误处理
    if (empty($sourceDirectory) || empty($targetDirectory) || empty($renamePattern)) {
        return $response
            ->withStatus(400)
            ->withJson(['error' => 'Missing parameters']);
    }

    try {
        // 调用文件重命名函数
        $renamedFiles = renameFiles($sourceDirectory, $targetDirectory, $renamePattern);

        // 返回成功和重命名文件列表
        return $response
            ->withJson(['success' => 'Files renamed successfully', 'renamedFiles' => $renamedFiles]);
    } catch (Exception $e) {
        // 返回错误信息
        return $response
            ->withStatus(500)
            ->withJson(['error' => $e->getMessage()]);
    }
});

// 文件重命名函数
function renameFiles($sourceDirectory, $targetDirectory, $renamePattern) {
    // 获取源目录下的所有文件
    $files = glob($sourceDirectory . '/*');
    $renamedFiles = [];

    foreach ($files as $file) {
        if (is_file($file)) {
            // 构建新的文件名
            $newFileName = basename($file, pathinfo($file, PATHINFO_EXTENSION)) . '_' . $renamePattern . '.' . pathinfo($file, PATHINFO_EXTENSION);
            $newFilePath = $targetDirectory . '/' . $newFileName;

            // 重命名文件
            if (rename($file, $newFilePath)) {
                $renamedFiles[] = ['old' => $file, 'new' => $newFilePath];
            } else {
                throw new Exception("Failed to rename file: {$file} to {$newFilePath}");
            }
        }
    }

    return $renamedFiles;
}

// 运行应用
$app->run();
