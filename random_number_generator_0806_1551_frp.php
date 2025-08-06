<?php
// 代码生成时间: 2025-08-06 15:51:12
// 使用Slim框架创建的随机数生成器程序
require 'vendor/autoload.php';

/**
 * 随机数生成器服务
 */
class RandomNumberGeneratorService {
    /**
     * 生成随机数
     *
     * @param int $min 下限
     * @param int $max 上限
     * @return int
     * @throws Exception 抛出异常，如果参数不合法
     */
    public function generate(int $min, int $max): int {
        if ($min > $max) {
            throw new Exception('下限不能大于上限');
        }
        if ($min < 0) {
            throw new Exception('下限不能是负数');
        }
        return rand($min, $max);
    }
}

// 创建Slim应用
$app = new \Slim\Factory\AppFactory();
$container = $app->getContainer();
$container[RandomNumberGeneratorService::class] = function ($container) {
    return new RandomNumberGeneratorService();
};

$app->get('/random/{min:[0-9]+}/{max:[0-9]+}', function ($request, $response, $args) {
    $min = (int) $args['min'];
    $max = (int) $args['max'];
    $randomNumberService = $this->get(RandomNumberGeneratorService::class);
    try {
        $randomNumber = $randomNumberService->generate($min, $max);
        $response->getBody()->write(json_encode(['randomNumber' => $randomNumber]));
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        $response->withStatus(400);
    }
    return $response;
});

$app->run();