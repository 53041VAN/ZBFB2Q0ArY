<?php
// 代码生成时间: 2025-08-07 03:47:41
use Slim\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

// 表单数据验证器类
class FormValidator {
    // 验证规则
    private $rules = [];

    // 构造函数
    public function __construct($rules) {
        $this->rules = $rules;
    }

    // 验证表单数据
    public function validate(ServerRequestInterface $request) {
        $requestBody = $request->getParsedBody();
        $errors = [];

        foreach ($this->rules as $field => $rule) {
            if (!isset($requestBody[$field])) {
                $errors[$field] = 'Field is required.';
            } else {
                $value = $requestBody[$field];
                foreach ($rule as $key => $value) {
                    switch ($key) {
                        case 'max':
                            if (strlen($value) > $value) {
                                $errors[$field] = "Field must be less than or equal to {$value} characters.";
                                break 2;
                            }
                        case 'min':
                            if (strlen($value) < $value) {
                                $errors[$field] = "Field must be at least {$value} characters.";
                                break 2;
                            }
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $errors[$field] = 'Invalid email format.';
                                break 2;
                            }
                    }
                }
            }
        }

        return $errors;
    }
}

// 应用启动函数
$app = \$c = new \Slim\App();

// 路由定义
$app->post('/validate', function (ServerRequestInterface \$request, Response \$response, \$args) {
    // 定义验证规则
    \$rules = [
        'name' => ['required', 'max' => 50],
        'email' => ['required', 'email'],
        'age' => ['min' => 18]
    ];

    // 创建验证器实例
    \$validator = new FormValidator(\$rules);

    // 执行验证
    \$errors = \$validator->validate(\$request);

    if (empty(\$errors)) {
        \$response->getBody()->write('Validation successful.');
    } else {
        \$response->getBody()->write(json_encode(\$errors));
        \$response->withStatus(400);
    }

    return \$response;
});

// 运行应用
\$app->run();