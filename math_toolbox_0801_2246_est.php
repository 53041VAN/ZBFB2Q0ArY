<?php
// 代码生成时间: 2025-08-01 22:46:32
// MathToolbox.php

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// Define namespace for MathToolbox
namespace MathToolbox;

// MathToolbox class that holds all math operations
class MathToolbox {
    public function add($a, $b) {
        return $a + $b;
    }

    public function subtract($a, $b) {
        return $a - $b;
    }

    public function multiply($a, $b) {
        return $a * $b;
    }

    public function divide($a, $b) {
        if ($b == 0) {
            throw new \Exception('Division by zero is not allowed.');
        }
        return $a / $b;
    }
}

// Setting up Slim application
$app = AppFactory::create();

// Route for adding two numbers
$app->get('/add/{a}/{b}', function (Request $request, Response $response, $args) {
    $toolbox = new MathToolbox();
    $sum = $toolbox->add($args['a'], $args['b']);
    $response->getBody()->write(json_encode(['result' => $sum]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Route for subtracting two numbers
$app->get('/subtract/{a}/{b}', function (Request $request, Response $response, $args) {
    $toolbox = new MathToolbox();
    $difference = $toolbox->subtract($args['a'], $args['b']);
    $response->getBody()->write(json_encode(['result' => $difference]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Route for multiplying two numbers
$app->get('/multiply/{a}/{b}', function (Request $request, Response $response, $args) {
    $toolbox = new MathToolbox();
    $product = $toolbox->multiply($args['a'], $args['b']);
    $response->getBody()->write(json_encode(['result' => $product]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Route for dividing two numbers
$app->get('/divide/{a}/{b}', function (Request $request, Response $response, $args) {
    $toolbox = new MathToolbox();
    try {
        $quotient = $toolbox->divide($args['a'], $args['b']);
        $response->getBody()->write(json_encode(['result' => $quotient]));
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(400);
    }
    return $response->withHeader('Content-Type', 'application/json');
});

// Run the Slim application
$app->run();
