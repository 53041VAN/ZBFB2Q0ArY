<?php
// 代码生成时间: 2025-09-07 23:28:58
// 数据分析器应用
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义数据分析器类
class DataAnalyzer {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    // 计算平均值
    public function calculateAverage() {
        if (empty($this->data)) {
            throw new Exception('数据不能为空');
        }

        $sum = array_sum($this->data);
        $count = count($this->data);
        return $sum / $count;
    }

    // 计算中位数
    public function calculateMedian() {
        if (empty($this->data)) {
            throw new Exception('数据不能为空');
        }

        sort($this->data);
        $middleIndex = floor(count($this->data) / 2);
        return (count($this->data) % 2) ? $this->data[$middleIndex] :
                ($this->data[$middleIndex - 1] + $this->data[$middleIndex]) / 2;
    }

    // 计算方差
    public function calculateVariance() {
        if (empty($this->data)) {
            throw new Exception('数据不能为空');
        }

        $average = $this->calculateAverage();
        $variance = 0;
        foreach ($this->data as $value) {
            $variance += pow($value - $average, 2);
        }
        return $variance / count($this->data);
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 定义根路径路由，返回数据分析器的平均值、中位数和方差
$app->get('/', function (Request $request, Response $response, array $args) {
    try {
        $data = [1, 2, 3, 4, 5]; // 示例数据，实际应用中可从请求参数或数据库获取
        $analyzer = new DataAnalyzer($data);
        $average = $analyzer->calculateAverage();
        $median = $analyzer->calculateMedian();
        $variance = $analyzer->calculateVariance();

        return $response->withJson([
            'average' => $average,
            'median' => $median,
            'variance' => $variance
        ]);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

// 运行Slim应用
$app->run();
