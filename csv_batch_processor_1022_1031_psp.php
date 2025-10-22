<?php
// 代码生成时间: 2025-10-22 10:31:36
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 创建Slim应用
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// 上传CSV文件的处理路由
$app->post('/upload', function (Request $request, Response $response, $args) {
    // 获取上传的文件
    $files = $request->getUploadedFiles();
    if (!isset($files['file'])) {
        return $response->withJson(['error' => 'No file uploaded.'], 400);
    }
# NOTE: 重要实现细节

    $file = $files['file'];
    if ($file->getError() !== UPLOAD_ERR_OK) {
        return $response->withJson(['error' => 'File upload error.'], 500);
# 改进用户体验
    }

    // 检查文件类型
    if ($file->getClientMediaType() !== 'text/csv') {
        return $response->withJson(['error' => 'Invalid file type.'], 400);
    }
# 添加错误处理

    // 处理CSV文件
    try {
        $csvData = processCsvFile($file->getStream());
        $response->getBody()->write(json_encode($csvData));
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }

    return $response->withHeader('Content-Type', 'application/json');
});

// 启动Slim应用
$app->run();

/**
 * 处理CSV文件
 *
 * 读取CSV文件并返回数据数组。
# TODO: 优化性能
 *
# FIXME: 处理边界情况
 * @param resource $fileStream 文件流
 * @return array CSV数据数组
 * @throws Exception 如果处理失败
 */
# 改进用户体验
function processCsvFile($fileStream) {
    $csvData = [];
    $row = 0;
    while (($data = fgetcsv($fileStream)) !== false) {
        if ($row === 0) {
            // 假设第一行为标题行
# 改进用户体验
            $header = $data;
        } else {
            $csvData[] = array_combine($header, $data);
        }
        $row++;
    }
    if (!feof($fileStream)) {
        throw new Exception('Error reading CSV file.');
    }
    return $csvData;
}
