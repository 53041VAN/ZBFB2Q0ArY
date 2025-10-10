<?php
// 代码生成时间: 2025-10-10 16:20:49
// 使用Slim框架创建一个简单的REST API，用于批量文件重命名
use Slim\Factory\ServerRequestFactory;
use Slim\Psr7\Environment;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

require __DIR__ . '/../vendor/autoload.php';

$app = \Slim\Factory\AppFactory::create();

// POST /rename endpoint 用于接收批量重命名的请求
$app->post('/rename', function (ServerRequestInterface $request): ResponseInterface {
    $responseBody = [];
    $responseBody['status'] = 'error';
    $responseBody['message'] = 'Error occurred';
    
    // 尝试从请求中获取JSON数据
    try {
        $bodyContents = $request->getBody()->getContents();
        $data = json_decode($bodyContents, true);
        
        if (empty($data['files'])) {
            throw new \Exception('No files specified for renaming');
        }
        
        // 遍历文件数组进行重命名
        foreach ($data['files'] as $file) {
            if (!isset($file['oldName']) || !isset($file['newName'])) {
                throw new \Exception('Missing oldName or newName for a file');
            }
            
            $oldPath = $file['oldName'];
            $newPath = $file['newName'];
            
            // 检查旧文件是否存在
            if (!file_exists($oldPath)) {
                throw new \Exception("File {$oldPath} does not exist");
            }
            
            // 尝试重命名文件
            if (!rename($oldPath, $newPath)) {
                throw new \Exception("Failed to rename file {$oldPath} to {$newPath}");
            }
        }
        
        // 如果所有文件都重命名成功，更新响应状态
        $responseBody['status'] = 'success';
        $responseBody['message'] = 'Files renamed successfully';
    } catch (Exception $e) {
        $responseBody['message'] = $e->getMessage();
    }
    
    return Response::create(json_encode($responseBody), 200, ['Content-Type' => 'application/json']);
});

// 运行应用
$app->run();

// 以下注释和文档说明了如何使用此API
/**
 * @title Batch File Rename Tool
 * @version 1.0.0
 *
 * @description This is a simple REST API using Slim Framework for batch file renaming.
 *
 * @host localhost:8080
 * @BasePath /
 *
 * @securityDefinitions {}
 *
 * @security {}
 *
 * @tag.name Files
 * @tag.description Operations about files
 *
 * @path /rename
 * @post
 * @tag Files
 * @summary Rename files in batch
 * @description This can be used to rename multiple files in a single request.
 * @operationId renameFiles
 * @produces application/json
 * @consumes application/json
 * @security {}
 * @param {object} body
 * @param {array} body.files
 * @param {string} body.files[].oldName
 * @param {string} body.files[].newName
 * @success {object} 200
 * @failure {object} 400
 *
 * @example {"files":[{"oldName":"oldfile.txt","newName":"newfile.txt"}]}
 */