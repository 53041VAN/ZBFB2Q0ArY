<?php
// 代码生成时间: 2025-10-11 03:26:22
// folder_structure_organizer.php
// 使用Slim框架实现的文件夹结构整理器

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义App
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// 定义路由
$app->get('/organize/{path:.+}', function (Request $request, Response $response, $args) {
    $path = $args['path'];

    try {
        // 检查路径是否存在
        if (!file_exists($path)) {
            $response->getBody()->write('Error: Path does not exist.');
            return $response->withStatus(404);
        }

        // 检查路径是否为目录
        if (!is_dir($path)) {
            $response->getBody()->write('Error: Path is not a directory.');
            return $response->withStatus(400);
        }

        // 调用整理文件夹结构的函数
        $organized = organizeFolderStructure($path);
        $response->getBody()->write('Folder structure organized successfully: ' . json_encode($organized));
    } catch (Exception $e) {
        $response->getBody()->write('Error: ' . $e->getMessage());
        return $response->withStatus(500);
    }

    return $response;
});

// 运行应用
$app->run();

/**
 * 整理文件夹结构的函数
 *
 * @param string $path 需要整理的文件夹路径
 * @return array 返回整理后的文件夹结构数组
 */
function organizeFolderStructure($path) {
    $organized = [];
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach ($files as $name => $object) {
        if ($object->isDir()) {
            continue; // 跳过目录
        }
        $relativePath = preg_replace('/^' . preg_quote(realpath($path), '/') . '/', '', $object->getPathname());
        $organized[] = $relativePath;
    }
    return $organized;
}
