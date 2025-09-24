<?php
// 代码生成时间: 2025-09-24 11:59:27
// 使用Composer的autoloader
require '/path/to/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// 主题切换程序启动
$app = AppFactory::create();

// 获取主题设置
$app->get('/theme/{theme}', function (Request $request, Response $response, $args) {
    $theme = $args['theme'];
    
    // 检查主题是否有效
    $validThemes = ['light', 'dark', 'colorful'];
    if (!in_array($theme, $validThemes)) {
        $response->getBody()->write('Invalid theme');
        return $response->withStatus(400);
    }

    // 设置主题cookie
    $settings = $request->getCookieParams();
    $settings['theme'] = $theme;
    $response = $response->withCookie('theme', $theme);
    
    // 返回新的主题设置
    $response->getBody()->write('Theme set to ' . $theme);
    return $response->withStatus(200);
});

// 运行应用
$app->run();