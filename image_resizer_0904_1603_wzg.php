<?php
// 代码生成时间: 2025-09-04 16:03:45
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Intervention\Image\ImageManager;
use Intervention\Image\Exception\NotReadableException;

// Define the root directory of the application
define('ROOT_DIR', __DIR__);

// Create Slim app
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// Define routes
$app->post('/images/resize', function (Request $request, Response $response, array $args) {
    // Get the uploaded files
    $files = $request->getUploadedFiles();
    $resizeWidth = $request->getParsedBody()['width'] ?? 100;
    $resizeHeight = $request->getParsedBody()['height'] ?? 100;

    if (empty($files['image'])) {
        return $response->withJson(['error' => 'No image file provided.'], 400);
    }

    try {
        // Check if the uploaded file is valid and readable
        if (!$files['image']->getError() === UPLOAD_ERR_OK || !$files['image']->isReadable()) {
            throw new Exception('Invalid or unreadable image file.');
        }

        // Resize the image
        $imageManager = new ImageManager();
        $image = $imageManager->make($files['image']->file->getPathname());
        $resizedImage = $image->resize($resizeWidth, $resizeHeight, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Save the resized image to a temporary file
        $tmpFilePath = sys_get_temp_dir() . '/' . uniqid('resized_') . '.jpg';
        $resizedImage->save($tmpFilePath);

        // Return the resized image as a response
        $response->getBody()->write(file_get_contents($tmpFilePath));
        @unlink($tmpFilePath); // Remove temporary file

        return $response
            ->withHeader('Content-Type', 'image/jpeg')
            ->withStatus(200);

    } catch (NotReadableException $e) {
        return $response->withJson(['error' => 'Not readable image file.'], 400);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
});

$app->run();