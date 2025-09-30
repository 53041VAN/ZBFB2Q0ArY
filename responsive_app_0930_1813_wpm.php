<?php
// 代码生成时间: 2025-09-30 18:13:51
// 引入Slim框架
require 'vendor/autoload.php';

// 创建Slim应用
$app = new \Slim\App();

// 定义GET路由，用于展示响应式布局页面
$app->get('/', function (\$request, \$response, \$args) {
    \$response->getBody()->write("<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Responsive Layout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        @media (max-width: 600px) {
            .container {
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Responsive Layout Example</h1>
        <p>This layout adjusts based on the screen size.</p>
    </div>
</body>
</html>");
    return \$response;
});

// 定义一个错误处理器
$app->addErrorMiddleware(true, true, true);

// 运行应用
$app->run();
