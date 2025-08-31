<?php
// 代码生成时间: 2025-08-31 11:45:52
// 使用Slim框架实现的压缩文件解压工具
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Nyholm\Psr7\Response as Psr7Response;

// 初始化Slim应用
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// 定义根路由，用于处理文件上传和解压请求
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('Welcome to the Unzip Tool!');
    return $response;
});

// 上传并解压文件的路由
$app->post('/upload-and-unzip', function (Request $request, Response $response, array $args) {
    // 获取上传的文件
    $file = $request->getUploadedFiles()['file'];
    if (!$file) {
        return $response->withStatus(400)->withBody(Psr7Response::create(400, 'No file uploaded'));
    }

    // 检查文件是否有效
    if (!$file->getError() === UPLOAD_ERR_OK) {
        return $response->withStatus(400)->withBody(Psr7Response::create(400, 'Invalid file uploaded'));
    }

    // 获取文件名和临时路径
    $fileName = $file->getClientFilename();
    $tempPath = $file->getTempName();

    // 尝试解压缩文件
    try {
        // 解压缩到指定目录
        $extractPath = 'extracted/';
        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0777, true);
        }
        $zip = new ZipArchive();
        if ($zip->open($tempPath) === true) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            throw new Exception('Failed to open zip file');
        }

        // 返回成功消息
        return $response->withStatus(200)->withBody(Psr7Response::create(200, "File {$fileName} extracted successfully"));
    } catch (Exception $e) {
        // 返回错误消息
        return $response->withStatus(500)->withBody(Psr7Response::create(500, $e->getMessage()));
    }
});

// 运行应用
$app->run();

// 压缩文件解压工具的主要功能类
class UnzipTool {
    // 解压缩文件到指定目录
    public static function extractZipFile($filePath, $extractPath) {
        try {
            $zip = new ZipArchive();
            if ($zip->open($filePath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                throw new Exception('Failed to open zip file');
            }
        } catch (Exception $e) {
            throw new Exception("Error extracting zip file: {$e->getMessage()}");
        }
    }
}
