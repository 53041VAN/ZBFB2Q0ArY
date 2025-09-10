<?php
// 代码生成时间: 2025-09-10 15:26:27
// WebContentScraper.php
// 使用Slim框架实现的网页内容抓取工具

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 创建Slim应用
$app = AppFactory::create();

// 定义路由和处理函数，用于抓取网页内容
$app->get('/scrape/{url}', function (Request $request, Response $response, $args) {
    $url = $args['url'];
    
    // 错误处理，确保URL是有效的
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $response->getBody()->write('Invalid URL provided.');
        return $response->withStatus(400);
    }
    
    try {
        // 使用cURL抓取网页内容
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        $content = curl_exec($ch);
        
        // 检查cURL请求是否成功
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        
        // 关闭cURL资源
        curl_close($ch);
    } catch (Exception $e) {
        // 错误处理，如果请求失败返回错误信息
        $response->getBody()->write('Error fetching content: ' . $e->getMessage());
        return $response->withStatus(500);
    }
    
    // 设置响应内容类型和返回网页内容
    $response->getBody()->write($content);
    return $response->withHeader('Content-Type', 'text/html; charset=utf-8');
});

// 运行应用
$app->run();
