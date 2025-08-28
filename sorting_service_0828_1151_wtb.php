<?php
// 代码生成时间: 2025-08-28 11:51:02
// SortingService 类负责对数字数组进行排序
class SortingService {

    // 对数组进行冒泡排序
    public function bubbleSort(array $arr): array {
        $n = count($arr);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($arr[$j] > $arr[$j + 1]) {
                    // 交换元素
                    $temp = $arr[$j];
                    $arr[$j] = $arr[$j + 1];
                    $arr[$j + 1] = $temp;
                }
            }
        }
        return $arr;
    }

    // 对数组进行选择排序
    public function selectionSort(array $arr): array {
        $n = count($arr);
        for ($i = 0; $i < $n - 1; $i++) {
            // 找到最小元素的索引
            $minIdx = $i;
            for ($j = $i + 1; $j < $n; $j++) {
                if ($arr[$j] < $arr[$minIdx]) {
                    $minIdx = $j;
                }
            }
            // 交换当前索引和最小元素索引的值
            $temp = $arr[$i];
            $arr[$i] = $arr[$minIdx];
            $arr[$minIdx] = $temp;
        }
        return $arr;
    }

    // 对数组进行插入排序
    public function insertionSort(array $arr): array {
        for ($i = 1; $i < count($arr); $i++) {
            $key = $arr[$i];
            $j = $i - 1;
            
            // 将选中的元素插入到已排序序列中的正确位置
            while ($j >= 0 && $arr[$j] > $key) {
                $arr[$j + 1] = $arr[$j];
                $j = $j - 1;
            }
            $arr[$j + 1] = $key;
        }
        return $arr;
    }

    // 检查输入数组是否为有效的数字数组
    private function isValidArray(array $arr): bool {
        foreach ($arr as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }
        return true;
    }

}

// Slim app setup
$app = new \Slim\App();

// 路由定义 - 冒泡排序
$app->get('/bubble-sort/{arr}', function ($request, $response, $args) {
    $arr = explode(',', $args['arr']);
    $sortingService = new SortingService();
    if ($sortingService->isValidArray($arr)) {
        $response = $sortingService->bubbleSort($arr);
    } else {
        $response = 'Invalid array: Array should contain only numbers.';
    }
    return $response;
});

// 路由定义 - 选择排序
$app->get('/selection-sort/{arr}', function ($request, $response, $args) {
    $arr = explode(',', $args['arr']);
    $sortingService = new SortingService();
    if ($sortingService->isValidArray($arr)) {
        $response = $sortingService->selectionSort($arr);
    } else {
        $response = 'Invalid array: Array should contain only numbers.';
    }
    return $response;
});

// 路由定义 - 插入排序
$app->get('/insertion-sort/{arr}', function ($request, $response, $args) {
    $arr = explode(',', $args['arr']);
    $sortingService = new SortingService();
    if ($sortingService->isValidArray($arr)) {
        $response = $sortingService->insertionSort($arr);
    } else {
        $response = 'Invalid array: Array should contain only numbers.';
    }
    return $response;
});

// 运行Slim应用
$app->run();