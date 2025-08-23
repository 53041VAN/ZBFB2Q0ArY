<?php
// 代码生成时间: 2025-08-23 17:00:27
// 使用Slim框架实现的URL链接有效性验证程序
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php'; // 加载依赖的自动加载文件

// 创建Slim应用
$app = AppFactory::create();

// 添加GET路由，用于验证URL链接
$app->get('/validate-url', function (Request $request, Response $response, array $args) {
    // 获取URL参数
    $url = $request->getQueryParam('url');

    if (empty($url)) {
        // 如果URL参数为空，则返回错误信息
        return $response
            ->withStatus(400)
            ->withJson(['error' => 'URL parameter is required.']);
    }

    try {
        // 验证URL格式
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception('Invalid URL format.');
        }

        // 尝试使用cURL获取URL内容，检查URL是否有效
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_status < 200 || $http_status >= 400) {
            throw new Exception('URL is not reachable.');
        }

        // 返回URL验证结果
        return $response
            ->withStatus(200)
            ->withJson(['message' => 'URL is valid and reachable.']);
    } catch (Exception $e) {
        // 返回错误信息
        return $response
            ->withStatus(400)
            ->withJson(['error' => $e->getMessage()]);
    }
});

// 运行Slim应用
$app->run();
