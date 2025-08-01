<?php
// 代码生成时间: 2025-08-01 09:34:03
// 使用Slim框架的文本文件内容分析器
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义文字分析器类
class TextFileAnalyzer {
    public function analyzeTextContent(string $filePath): array {
        try {
            if (!file_exists($filePath)) {
                throw new Exception("File does not exist.");
            }

            $content = file_get_contents($filePath);
            $analysisResult = [
                'totalCharacters' => strlen($content),
                'totalWords' => str_word_count($content),
                'totalLines' => substr_count($content, "\
") + 1,
                'totalSentences' => substr_count($content, '.') + substr_count($content, '!') + substr_count($content, '?'),
            ];
            return $analysisResult;
        } catch (Exception $e) {
            // 错误处理
            return ['error' => $e->getMessage()];
        }
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 设置路由和处理函数
$app->get('/analyze/{filePath}', function (Request $request, Response $response, $args) {
    $filePath = $args['filePath'];
    $analyzer = new TextFileAnalyzer();
    $result = $analyzer->analyzeTextContent($filePath);

    if (isset($result['error'])) {
        return $response->withJson($result, 400);
    } else {
        return $response->withJson($result, 200);
    }
});

// 运行应用
$app->run();
