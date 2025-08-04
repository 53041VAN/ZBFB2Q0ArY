<?php
// 代码生成时间: 2025-08-05 07:51:08
 * This program provides a simple math calculator with the following operations:
 * - Addition
 * - Subtraction
 * - Multiplication
 * - Division
 */

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Create Slim App
$app = AppFactory::create();

// Define routes
$app->get('/math/{operation}/{num1}/{num2}', function ($request, $response, $args) {
    $operation = $args['operation'];
    $num1 = (float)$args['num1'];
    $num2 = (float)$args['num2'];

    try {
        $result = calculate($operation, $num1, $num2);
        return $response->withJson(['result' => $result]);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 400);
    }
});

// Calculate math operations
function calculate(string $operation, float $num1, float $num2): float {
    // Check for division by zero
    if ($operation === 'division' && $num2 === 0) {
        throw new Exception('Division by zero is not allowed.');
    }

    switch ($operation) {
        case 'addition':
            return $num1 + $num2;
        case 'subtraction':
            return $num1 - $num2;
        case 'multiplication':
            return $num1 * $num2;
        case 'division':
            return $num1 / $num2;
        default:
            throw new Exception('Invalid operation.');
    }
}

// Run the application
$app->run();
