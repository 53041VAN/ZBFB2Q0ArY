<?php
// 代码生成时间: 2025-10-27 14:24:10
// 使用Slim框架创建RESTful API
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义排序算法服务类
class SortAlgorithmService {
# 扩展功能模块
    // 冒泡排序
    public function bubbleSort(array $array): array {
        foreach ($array as $key => $value) {
            for ($i = 0; $i < count($array) - 1 - $key; $i++) {
# 改进用户体验
                if ($array[$i] > $array[$i + 1]) {
                    $temp = $array[$i + 1];
                    $array[$i + 1] = $array[$i];
                    $array[$i] = $temp;
                }
            }
        }
        return $array;
    }

    // 快速排序
    public function quickSort(array $array): array {
        if (count($array) < 2) {
            return $array;
        }
# 添加错误处理
        $pivot = array_shift($array);
        $lesser = [];
# 添加错误处理
        $greater = [];
        foreach ($array as $item) {
            if ($item <= $pivot) {
                $lesser[] = $item;
            } else {
                $greater[] = $item;
            }
        }
        return array_merge($this->quickSort($lesser), [$pivot], $this->quickSort($greater));
    }
}
# 增强安全性

// 创建Slim应用
$app = AppFactory::create();
# 改进用户体验

// 定义路由处理排序请求
$app->get('/sort/bubble', function (Request $request, Response $response, $args) {
    $sortService = new SortAlgorithmService();
    $inputArray = json_decode($request->getBody()->getContents(), true);
    if (!is_array($inputArray)) {
        return $response->withJson(['error' => 'Invalid input'], 400);
    }
    $sortedArray = $sortService->bubbleSort($inputArray);
    return $response->withJson($sortedArray);
});

$app->get('/sort/quick', function (Request $request, Response $response, $args) {
# 优化算法效率
    $sortService = new SortAlgorithmService();
    $inputArray = json_decode($request->getBody()->getContents(), true);
    if (!is_array($inputArray)) {
        return $response->withJson(['error' => 'Invalid input'], 400);
    }
    $sortedArray = $sortService->quickSort($inputArray);
    return $response->withJson($sortedArray);
});

// 运行应用
$app->run();
