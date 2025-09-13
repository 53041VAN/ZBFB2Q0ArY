<?php
// 代码生成时间: 2025-09-14 03:26:29
// 使用Slim框架创建一个排序算法的RESTful API服务
require 'vendor/autoload.php';

$app = new \Slim\App();

// 定义路由处理排序请求
$app->get('/sort/{algorithm}/{size}', function ($request, $response, $args) {
    $algorithm = $args['algorithm'];
    $size = intval($args['size']);
    
    // 检查请求参数是否合法
    if (!in_array($algorithm, ['bubble', 'quick', 'merge', 'insertion'])) {
        return $response->withJson(['error' => 'Invalid algorithm provided'], 400);
    }
    
    if ($size < 1 || $size > 10000) {
        return $response->withJson(['error' => 'Size must be between 1 and 10000'], 400);
    }
    
    // 生成一个随机数组
    $array = $this->generateRandomArray($size);
    
    // 根据算法对数组进行排序
    switch ($algorithm) {
        case 'bubble':
            $sortedArray = $this->bubbleSort($array);
            break;
        case 'quick':
            $sortedArray = $this->quickSort($array);
            break;
        case 'merge':
            $sortedArray = $this->mergeSort($array);
            break;
        case 'insertion':
            $sortedArray = $this->insertionSort($array);
            break;
        default:
            return $response->withJson(['error' => 'Unknown sorting algorithm'], 500);
    }
    
    // 返回排序后的数组
    return $response->withJson(['sortedArray' => $sortedArray], 200);
});

// 生成随机数组
function generateRandomArray($size) {
    $array = [];
    for ($i = 0; $i < $size; $i++) {
        $array[] = rand(1, 10000);
    }
    return $array;
}

// 冒泡排序算法
function bubbleSort($array) {
    for ($i = 0; $i < count($array) - 1; $i++) {
        for ($j = 0; $j < count($array) - $i - 1; $j++) {
            if ($array[$j] > $array[$j + 1]) {
                $temp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $temp;
            }
        }
    }
    return $array;
}

// 快速排序算法
function quickSort($array) {
    if (count($array) < 2) {
        return $array;
    }
    $left = $right = [];
    reset($array);
    $pivot_key = key($array);
    $pivot = array_shift($array);
    foreach ($array as $key => $value) {
        if ($value < $pivot) {
            $left[$key] = $value;
        } elseif ($value > $pivot) {
            $right[$key] = $value;
        }
    }
    return array_merge($this->quickSort($left), [$pivot_key => $pivot], $this->quickSort($right));
}

// 归并排序算法
function mergeSort($array) {
    if (count($array) == 1) return $array;
    $mid = count($array) / 2;
    $left = array_slice($array, 0, $mid);
    $right = array_slice($array, $mid);
    $left = $this->mergeSort($left);
    $right = $this->mergeSort($right);
    return $this->merge($left, $right);
}

// 合并两个已排序的数组
function merge($left, $right) {
    $result = [];
    while (count($left) > 0 && count($right) > 0) {
        if ($left[0] < $right[0]) {
            array_push($result, array_shift($left));
        } else {
            array_push($result, array_shift($right));
        }
    }
    while (count($left) > 0) {
        array_push($result, array_shift($left));
    }
    while (count($right) > 0) {
        array_push($result, array_shift($right));
    }
    return $result;
}

// 插入排序算法
function insertionSort($array) {
    for ($i = 1; $i < count($array); $i++) {
        $key = $array[$i];
        $j = $i - 1;
        while ($j >= 0 && $array[$j] > $key) {
            $array[$j + 1] = $array[$j];
            $j = $j - 1;
        }
        $array[$j + 1] = $key;
    }
    return $array;
}

// 运行Slim应用
$app->run();