<?php
// 代码生成时间: 2025-08-08 02:08:45
// url_validator_slim.php
// 使用Slim框架实现URL链接有效性验证

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 创建Slim应用
AppFactory::setCodingStylePsr4();
AppFactory::define(function (): void {
    $app = AppFactory::create();

    // 定义GET路由用于验证URL链接有效性
    $app->get('/validate-url', function (Request $request, Response $response, array $args): Response {
        $url = $request->getQueryParams()['url'] ?? '';

        // 检查URL是否提供
        if (empty($url)) {
            return $response->withJson(
                ['error' => 'URL parameter is required'],
                400
            );
        }

        // 验证URL格式
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $response->withJson(
                ['error' => 'Invalid URL format'],
                400
            );
        }

        // 尝试获取URL头信息以验证有效性
        $headers = @get_headers($url);
        if ($headers === false || strpos($headers[0], '200') === false) {
            return $response->withJson(
                ['error' => 'URL is not valid or not reachable'],
                400
            );
        }

        // 返回有效性验证结果
        return $response->withJson(
            ['message' => 'URL is valid'],
            200
        );
    });

    // 运行应用
    $app->run();
});