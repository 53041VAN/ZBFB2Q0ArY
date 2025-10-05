<?php
// 代码生成时间: 2025-10-06 01:48:26
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Define the route for reading a binary file
$app->get('/read/{file}', function (Request $request, Response $response, $args) {
    $filePath = $args['file'];
    
    try {
        // Check if the file exists
        if (!file_exists($filePath)) {
            $response->getBody()->write('File not found.');
            return $response->withStatus(404);
        }

        // Read the file content in binary mode
        $content = file_get_contents($filePath, false, null, 0, 0x7FFFFFFF);

        // Set the response content and headers
        $response->getBody()->write($content);
        $response = $response->withHeader('Content-Type', 'application/octet-stream');
        $response = $response->withHeader('Content-Disposition', 'attachment; filename="' . basename($filePath) . '"');

        return $response;
    } catch (Exception $e) {
        $response->getBody()->write('Error reading file: ' . $e->getMessage());
        return $response->withStatus(500);
    }
});

// Define the route for writing a binary file
$app->post('/write/{file}', function (Request $request, Response $response, $args) {
    $filePath = $args['file'];
    $body = $request->getBody();
    
    try {
        // Get the file content from the request body
        $content = $body->getContents();

        // Write the content to the file in binary mode
        if (file_put_contents($filePath, $content, FILE_BINARY) === false) {
            throw new Exception('Error writing to file.');
        }

        $response->getBody()->write('File written successfully.');
        return $response;
    } catch (Exception $e) {
        $response->getBody()->write('Error writing file: ' . $e->getMessage());
        return $response->withStatus(500);
    }
});

// Run the application
$app->run();