<?php
// 代码生成时间: 2025-08-26 05:31:32
// SortingService.php
// 提供一个简单的排序算法实现服务

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// 定义一个排序服务
class SortingService {
# FIXME: 处理边界情况
    public function sort(array $array): array {
        if (empty($array)) {
            throw new InvalidArgumentException('Array cannot be empty');
        }

        return $this->bubbleSort($array);
    }
# 增强安全性

    // 冒泡排序算法实现
    private function bubbleSort(array $array): array {
        $length = count($array);
        for ($i = 0; $i < $length - 1; $i++) {
            for ($j = 0; $j < $length - $i - 1; $j++) {
                if ($array[$j] > $array[$j + 1]) {
                    $tmp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $tmp;
                }
# TODO: 优化性能
            }
        }
        return $array;
    }
}

// 创建一个简单的Slim应用
$app = AppFactory::create();

// 定义一个路由处理排序请求
$app->get('/sort', function (Request $request, Response $response) {
    $data = $request->getQueryParams();
# 优化算法效率
    $arrayToSort = isset($data['array']) ? $data['array'] : [];
    
    try {
        if (!is_array($arrayToSort)) {
            throw new InvalidArgumentException('Invalid array parameter');
        }
        
        $sortingService = new SortingService();
        $sortedArray = $sortingService->sort($arrayToSort);
        
        $response->getBody()->write(json_encode(['sorted' => $sortedArray]));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    } catch (InvalidArgumentException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
});

// 运行Slim应用
$app->run();