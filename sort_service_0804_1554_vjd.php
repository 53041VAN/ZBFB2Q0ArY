<?php
// 代码生成时间: 2025-08-04 15:54:21
require 'vendor/autoload.php';

use Slim\Factory\AppFactory';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SortService {
    // Bubble Sort implementation
    public function bubbleSort(&$array) {
        $n = count($array);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < n - i - 1; $j++) {
                if ($array[$j] > $array[$j + 1]) {
                    // Swap elements
                    $temp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $temp;
                }
            }
        }
    }

    // Quick Sort implementation
    public function quickSort(&$array, $low, $high) {
        if ($low < $high) {
            $pi = $this->partition($array, $low, $high);
            $this->quickSort($array, $low, $pi - 1);
            $this->quickSort($array, $pi + 1, $high);
        }
    }

    private function partition(&$array, $low, $high) {
        $pivot = $array[$high];
        $i = ($low - 1);
        for ($j = $low; $j < $high; $j++) {
            if ($array[$j] < $pivot) {
                $i++;
                $temp = $array[$i];
                $array[$i] = $array[$j];
                $array[$j] = $temp;
            }
        }
        $temp = $array[$i + 1];
        $array[$i + 1] = $array[$high];
        $array[$high] = $temp;
        return $i + 1;
    }
}

$app = AppFactory::create();

// Endpoint to sort array using bubble sort
$app->post('/bubble-sort', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $sortService = new SortService();
    if (isset($data['array']) && is_array($data['array'])) {
        $sortService->bubbleSort($data['array']);
        return $response->withJson(['sortedArray' => $data['array']], 200);
    } else {
        return $response->withJson(['error' => 'Invalid data. Please send an array.'], 400);
    }
});

// Endpoint to sort array using quick sort
$app->post('/quick-sort', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $sortService = new SortService();
    if (isset($data['array']) && is_array($data['array'])) {
        $sortService->quickSort($data['array'], 0, count($data['array']) - 1);
        return $response->withJson(['sortedArray' => $data['array']], 200);
    } else {
        return $response->withJson(['error' => 'Invalid data. Please send an array.'], 400);
    }
});

$app->run();