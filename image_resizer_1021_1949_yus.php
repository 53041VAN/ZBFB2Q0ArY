<?php
// 代码生成时间: 2025-10-21 19:49:12
// Image Resizer using PHP and SLIM framework
// Version 1.0
// Author: [Your Name]

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Intervention\Image\ImageManager;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Stream;
use Slim\Psr7\Factory\StreamFactory;

require __DIR__ . '/vendor/autoload.php';

// Create Slim App
$app = AppFactory::create();

// Define the route for image resizing
$app->post('/images/resize', function (Request $request, Response $response) {
    $imageManager = new ImageManager();
    $streamFactory = new StreamFactory();
    $params = $request->getParsedBody();
    
    // Check if the required parameters are present
    if (!isset($params['source'], $params['destination'], $params['width'], $params['height'])) {
        throw new HttpNotFoundException($request, 'Missing required parameters');
    }
    
    // Get the source and destination paths
    $sourcePath = $params['source'] ?? null;
    $destinationPath = $params['destination'] ?? null;
    $width = (int) ($params['width'] ?? 0);
    $height = (int) ($params['height'] ?? 0);
    
    // Check if the source file exists
    if (!file_exists($sourcePath)) {
        throw new HttpNotFoundException($request, 'Source file not found');
    }
    
    try {
        // Resize the image
        $image = $imageManager->make($sourcePath);
        $image->resize($width, $height);
        
        // Save the resized image to the destination path
        $image->save($destinationPath);
    } catch (\Exception $e) {
        throw new HttpInternalServerErrorException($request, 'Error resizing image: ' . $e->getMessage());
    }
    
    // Return a success response
    return $response->withJson(['message' => 'Image resized successfully']);
});

// Run the Slim App
$app->run();
