<?php
// 代码生成时间: 2025-09-22 03:04:42
// RandomNumberGenerator class
class RandomNumberGenerator {
    // Generate a random number between two integers
    public function generateRandomNumber($min, $max) {
        if (!is_int($min) || !is_int($max)) {
            // Throw an InvalidArgumentException if inputs are not integers
            throw new InvalidArgumentException('Both min and max must be integers.');
        }

        if ($min > $max) {
            // Throw an InvalidArgumentException if min is greater than max
            throw new InvalidArgumentException('Min cannot be greater than max.');
        }

        // Generate and return a random number between min and max
        return random_int($min, $max);
    }
}

// Slim setup
$app = new \Slim\App();

// Define a route to generate a random number
$app->get('/generate-random-number', function ($request, $response, $args) {
    $min = $request->getQueryParams()['min'] ?? 1;
    $max = $request->getQueryParams()['max'] ?? 100;

    try {
        $randomNumberGenerator = new RandomNumberGenerator();
        $randomNumber = $randomNumberGenerator->generateRandomNumber($min, $max);

        $response->getBody()->write(json_encode(['randomNumber' => $randomNumber]));
    } catch (InvalidArgumentException $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// Run the Slim application
$app->run();
