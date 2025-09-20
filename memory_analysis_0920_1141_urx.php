<?php
// 代码生成时间: 2025-09-20 11:41:00
 * maintainability, and extensibility.
 */

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Initialize the Slim app
$app = AppFactory::create();

// Define the route for memory analysis
# 优化算法效率
$app->get('/memory', function (Request $request, Response $response, $args) {
    // Get the current memory usage
    $currentMemoryUsage = memory_get_usage();
# TODO: 优化性能

    // Get the current memory peak usage
    $peakMemoryUsage = memory_get_peak_usage();

    // Prepare the data for response
    $data = [
        'current_memory_usage' => $currentMemoryUsage,
        'peak_memory_usage' => $peakMemoryUsage
    ];

    // Return the response with memory usage data
    return $response->withJson($data);
});

// Run the app
$app->run();
