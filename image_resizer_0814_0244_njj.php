<?php
// 代码生成时间: 2025-08-14 02:44:04
// Image Resizer using Slim Framework
// This script resizes images to specified dimensions

require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;

// Define constants for image paths
define('ORIGINAL_IMAGES_DIR', __DIR__ . '/original_images/');
define('RESIZED_IMAGES_DIR', __DIR__ . '/resized_images/');

// Create Slim App
$app = AppFactory::create();

// Middleware to handle error responses
$app->addErrorMiddleware(true, true, true, "group:errors");

// Define routes
$app->post('/resize', function (Request $request, Response $response, ContainerInterface $container) {
    // Get JSON data from request body
    $data = $request->getParsedBody();
    
    // Validate input data
    if (empty($data['originalImagePath']) || empty($data['newWidth']) || empty($data['newHeight'])) {
        return $response->withJson(['error' => 'Missing data'], 400);
    }
    
    // Extract image path and dimensions
    $originalImagePath = ORIGINAL_IMAGES_DIR . $data['originalImagePath'];
    $newWidth = $data['newWidth'];
    $newHeight = $data['newHeight'];
    
    // Check if original image exists
    if (!file_exists($originalImagePath)) {
        return $response->withJson(['error' => 'Original image not found'], 404);
    }
    
    // Create new image path
    $newImagePath = RESIZED_IMAGES_DIR . pathinfo($data['originalImagePath'], PATHINFO_FILENAME) . '_resized.' . pathinfo($data['originalImagePath'], PATHINFO_EXTENSION);
    
    // Get image extension
    $imageExtension = strtolower(pathinfo($data['originalImagePath'], PATHINFO_EXTENSION));
    
    // Load image resource
    $image = null;
    switch ($imageExtension) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($originalImagePath);
            break;
        case 'png':
            $image = imagecreatefrompng($originalImagePath);
            break;
        case 'gif':
            $image = imagecreatefromgif($originalImagePath);
            break;
        default:
            return $response->withJson(['error' => 'Unsupported image format'], 415);
    }
    
    // Create a new true color image
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Resize and copy the original image to the new image
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($image), imagesy($image));
    
    // Save the resized image
    switch ($imageExtension) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($newImage, $newImagePath);
            break;
        case 'png':
            imagepng($newImage, $newImagePath);
            break;
        case 'gif':
            imagegif($newImage, $newImagePath);
            break;
    }
    
    // Free up memory
    imagedestroy($image);
    imagedestroy($newImage);
    
    // Return success response with new image path
    return $response->withJson(['newImagePath' => $newImagePath], 201);
});

// Run the app
$app->run();
