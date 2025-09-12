<?php
// 代码生成时间: 2025-09-13 00:11:43
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 创建 Slim 应用
AppFactory::setCodingStandard(__DIR__ . '/vendor/slim/psr7/src');
$app = AppFactory::create();

// 中间件，用于解析 JSON 请求体
$app->add(function ($request, $handler) {
    $body = $request->getBody();
    if ($body->isWritable()) {
        $body->rewind();
        $content = $body->getContents();
        if (!empty($content)) {
            $request = $request->withParsedBody(json_decode($content, true));
        }
    }
    return $handler->handle($request);
});

// 路由：接收 JSON 数据并转换为 XML
$app->post('/json-to-xml', function (Request $request, Response $response, $args) {
    // 获取 JSON 数据
    $data = $request->getParsedBody();

    // 错误处理：检查 JSON 数据是否有效
    if (!is_array($data)) {
        return $response->withJson(['error' => 'Invalid JSON data'], 400);
    }

    // 转换 JSON 为 XML
    $xml = new SimpleXMLElement('<root/>');
    array_walk_recursive($data, function ($value, $key) use ($xml) {
        $xmlKey = (is_numeric($key)) ? 'item' : $key;
        $xml->addChild($xmlKey, htmlspecialchars($value));
    });

    // 设置响应头和内容类型
    $response->getBody()->write($xml->asXML());
    return $response->withHeader('Content-Type', 'application/xml');
});

// 运行应用
$app->run();