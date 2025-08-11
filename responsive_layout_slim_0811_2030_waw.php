<?php
// 代码生成时间: 2025-08-11 20:30:57
// 引入Slim框架
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// 创建Slim应用
AppFactory::setCodingStylePsr12();
AppFactory::define(['settings' => ['displayErrorDetails' => true]]);
$app = AppFactory::create();

// 定义GET请求处理
$app->get('/', function ($request, $response, $args) {
    // 返回响应式布局的HTML页面
    return $response->getBody()->write($this->view->render($response, 'index.html', []));
});

// 运行应用
$app->run();

// 定义视图渲染函数
class View {
    private $container;
    public function __construct($container) {
        $this->container = $container;
    }

    public function render($response, $template, $data = []) {
        // 检查模板文件是否存在
        if (!file_exists($template)) {
            throw new \Exception('Template not found');
        }
        // 渲染模板并返回内容
        extract($data);
        ob_start();
        require $template;
        return ob_get_clean();
    }
}

// 错误处理中间件
$app->addErrorMiddleware(true, true, true);

// 定义模板目录路径
define('TEMPLATES_PATH', __DIR__ . '/templates/');

// 注册视图服务
$container = $app->getContainer();
$container['view'] = function ($container) {
    return new View($container);
};

// 定义模板文件
/* templates/index.html */
"<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Responsive Layout</title>
    <style>
        /* 响应式布局样式 */
        .container {
            width: 80%;
            margin: auto;
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class=\"container\">
        <h1>Welcome to the Responsive Layout</h1>
        <p>This is a responsive layout designed using PHP and Slim Framework.</p>
    </div>
</body>
</html>"
}