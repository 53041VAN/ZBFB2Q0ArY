<?php
// 代码生成时间: 2025-10-03 22:49:48
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个DataCleaningTool类，用于处理数据清洗和预处理
class DataCleaningTool {
    // 清理字符串，去除前后空格和特殊字符
    public function cleanString($string) {
        return trim($string, " 	\
\r\0\x0B");
    }

    // 转换字符串为小写
    public function toLowerCase($string) {
        return strtolower($string);
    }

    // 去除字符串中的HTML标签
    public function removeHtmlTags($string) {
        return strip_tags($string);
    }

    // 去除字符串中的空白字符
    public function removeWhitespace($string) {
        return preg_replace('/\s+/', '', $string);
    }
}

require __DIR__ . '/../vendor/autoload.php';

// 创建Slim应用
$app = AppFactory::create();

// 定义一个路由，用于处理GET请求
$app->get('/clean', function (Request $request, Response $response, $args) {
    $dataCleaningTool = new DataCleaningTool();
    $inputData = $request->getQueryParams()['data'] ?? '';

    // 检查输入数据
    if (empty($inputData)) {
        return $response->withJson(
            ['error' => 'No input data provided.'],
            Response::HTTP_BAD_REQUEST
        );
    }

    // 清洗和预处理数据
    $cleanedData = $dataCleaningTool->cleanString($inputData);
    $cleanedData = $dataCleaningTool->toLowerCase($cleanedData);
    $cleanedData = $dataCleaningTool->removeHtmlTags($cleanedData);
    $cleanedData = $dataCleaningTool->removeWhitespace($cleanedData);

    // 返回清洗后的数据
    return $response->withJson(['cleanedData' => $cleanedData]);
});

// 运行应用
$app->run();
