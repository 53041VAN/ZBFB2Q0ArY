<?php
// 代码生成时间: 2025-08-07 17:00:18
// archive_extractor.php
// 使用 Slim 框架实现的文件压缩/解压工具

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个类来处理文件压缩和解压逻辑
class ArchiveExtractor {
    public function extract($archivePath, $destinationPath) {
        // 检查文件是否存在
        if (!file_exists($archivePath)) {
            throw new Exception('Archive file does not exist.');
        }

        // 检查目标目录是否存在
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // 使用 PharData扩展来解压文件
        $phar = new PharData($archivePath);
        // 解压到指定目录
        $phar->extractTo($destinationPath, null);
    }

    public function compress($sourcePath, $archivePath, $format = 'zip') {
        // 检查源目录是否存在
        if (!is_dir($sourcePath)) {
            throw new Exception('Source directory does not exist.');
        }

        // 压缩文件
        $archive = new PharData($archivePath);
        $archive->startBuffering();
        // 创建一个新的Phar文件
        $archive->buildFromDirectory($sourcePath, '/.*/');
        // 设置压缩格式
        $archive[0] = 'C';
        // 停止缓冲并压缩文件
        $archive->stopBuffering();
    }
}

// 创建 Slim 应用
$app = AppFactory::create();

// 解压缩文件的路由
$app->post('/extract', function (Request $request, Response $response, $args) {
    $body = $request->getParsedBody();
    $archivePath = $body['archivePath'] ?? null;
    $destinationPath = $body['destinationPath'] ?? null;

    if (!$archivePath || !$destinationPath) {
        return $response->withJson(['error' => 'Missing archivePath or destinationPath.'], 400);
    }

    try {
        $extractor = new ArchiveExtractor();
        $extractor->extract($archivePath, $destinationPath);
        return $response->withJson(['message' => 'Archive extracted successfully.'], 200);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

// 压缩文件的路由
$app->post('/compress', function (Request $request, Response $response, $args) {
    $body = $request->getParsedBody();
    $sourcePath = $body['sourcePath'] ?? null;
    $archivePath = $body['archivePath'] ?? null;

    if (!$sourcePath || !$archivePath) {
        return $response->withJson(['error' => 'Missing sourcePath or archivePath.'], 400);
    }

    try {
        $extractor = new ArchiveExtractor();
        $extractor->compress($sourcePath, $archivePath);
        return $response->withJson(['message' => 'Archive created successfully.'], 200);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

// 运行应用
$app->run();