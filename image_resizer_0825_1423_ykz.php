<?php
// 代码生成时间: 2025-08-25 14:23:35
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Intervention\Image\ImageManager;

// Setup Slim Application
$app = AppFactory::create();

// Route for processing image resize requests
$app->post('/images/resize', function (Request $request, Response $response, array $args) {
    // Get parameters from request body
    $params = $request->getParsedBody();
    $targetWidth = $params['width'] ?? null;
    $targetHeight = $params['height'] ?? null;
    $imageFiles = $params['files'] ?? null;

    // Check if parameters are provided
    if (!$targetWidth || !$targetHeight || !$imageFiles) {
        return $response
            ->withStatus(400)
            ->withJson(['error' => 'Missing required parameters']);
    }

    // Initialize Image Manager
    $imageManager = new ImageManager();

    // Loop through each file and resize
    foreach ($imageFiles as $file) {
        if (!file_exists($file['path'])) {
            // Return error if file does not exist
            return $response
                ->withStatus(404)
                ->withJson(['error' => 'File not found']);
        }

        try {
            // Open an image file
            $image = $imageManager->make($file['path']);

            // Resize the image
            $image->resize($targetWidth, $targetHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Save the resized image
            $image->save($file['path']);
        } catch (Exception $e) {
            // Handle exceptions and return error message
            return $response
                ->withStatus(500)
                ->withJson(['error' => $e->getMessage()]);
        }
    }

    // Return a success message
    return $response
        ->withStatus(200)
        ->withJson(['message' => 'Images resized successfully']);
});

// Run the application
$app->run();