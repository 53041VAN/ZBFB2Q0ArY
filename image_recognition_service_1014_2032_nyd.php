<?php
// 代码生成时间: 2025-10-14 20:32:58
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;
use Slim\Exception\HttpNotFoundException;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Classification\SupportVectorMachine;
use Phpml\FeatureExtraction\RgbaToLabConverter;
use Phpml\Image.recognition\SVMImageRecognizer;
use Phpml\Image.recognition\KNNImageRecognizer;

// Define the image recognition service class
class ImageRecognitionService {

    private $recognizer;

    public function __construct($recognizer) {
        $this->recognizer = $recognizer;
    }

    // Method to recognize an image
    public function recognizeImage($imagePath) {
        try {
            $imageData = $this->recognizer->recognize($imagePath);
            return $imageData;
        } catch (Exception $e) {
            // Handle any exceptions during image recognition
            return ['error' => $e->getMessage()];
        }
    }
}

// Bootstrap Slim App
$app = AppFactory::create();

// Define routes
$app->get('/recognize', function (Request $request, Response $response, RouteCollectorProxy $router) {
    $imagePath = $request->getQueryParams()['imagePath'];
    if (empty($imagePath)) {
        return $response->withStatus(400)
            ->getBody()
            ->write('Missing image path parameter');
    }

    $recognizerType = $request->getQueryParams()['recognizerType'] ?? 'knn';
    $recognizer = $this->getRecognizer($recognizerType);
    $service = new ImageRecognitionService($recognizer);

    $result = $service->recognizeImage($imagePath);
    return $response->withJson($result);
});

// Factory method to get a recognizer based on the type
private function getRecognizer($type) {
    if ($type === 'knn') {
        // KNearestNeighbors recognizer
        $knn = new KNNearestNeighbors();
        return new KNNImageRecognizer($knn);
    } elseif ($type === 'svm') {
        // SupportVectorMachine recognizer
        $svm = new SupportVectorMachine();
        return new SVMImageRecognizer($svm);
    } else {
        throw new HttpNotFoundException($c, 'Unsupported recognizer type');
    }
}

// Run the app
$app->run();
