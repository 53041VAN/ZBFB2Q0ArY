<?php
// 代码生成时间: 2025-08-03 17:16:33
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// FolderStructureOrganizer class
class FolderStructureOrganizer {
    private $rootPath;

    public function __construct($rootPath) {
        $this->rootPath = $rootPath;
    }

    // Method to scan directories and organize files
    public function organizeFiles() {
        if (!is_dir($this->rootPath)) {
            throw new \Exception('The specified path is not a directory.');
        }

        $files = scandir($this->rootPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $this->processFile($this->rootPath . DIRECTORY_SEPARATOR . $file);
            }
        }
    }

    // Method to process each file and determine if it should be moved or deleted
    private function processFile($filePath) {
        if (is_dir($filePath)) {
            // Recursively call organizeFiles for subdirectories
            $this->organizeFiles($filePath . DIRECTORY_SEPARATOR);
        } else {
            // Implement file processing logic here
            // For example, move to a specific directory based on file type
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            $destination = $this->rootPath . DIRECTORY_SEPARATOR . $ext;
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            rename($filePath, $destination . DIRECTORY_SEPARATOR . basename($filePath));
        }
    }
}

// Create Slim app
$app = AppFactory::create();

// Define routes
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Welcome to the Folder Structure Organizer!');
    return $response;
});

$app->post('/organize', function (Request $request, Response $response, $args) {
    try {
        $body = $request->getParsedBody();
        $rootPath = $body['rootPath'] ?? null;

        if (!$rootPath) {
            throw new \Exception('Root path parameter is missing.');
        }

        $organizer = new FolderStructureOrganizer($rootPath);
        $organizer->organizeFiles();

        $response->getBody()->write('Files have been organized successfully.');
    } catch (Exception $e) {
        $response->getBody()->write('Error: ' . $e->getMessage());
    }
    return $response;
});

// Run the app
$app->run();