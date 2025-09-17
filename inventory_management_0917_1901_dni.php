<?php
// 代码生成时间: 2025-09-17 19:01:12
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// InventoryItem class represents an item in the inventory
class InventoryItem {
    private $id;
    private $name;
    private $quantity;

    public function __construct($id, $name, $quantity) {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}

// InventoryService class handles business logic for inventory management
class InventoryService {
    private $items;

    public function __construct() {
        $this->items = [];
    }

    public function addItem(InventoryItem $item) {
        $this->items[$item->getId()] = $item;
    }

    public function getItem($id) {
        if (isset($this->items[$id])) {
            return $this->items[$id];
        } else {
            throw new Exception('Item not found');
        }
    }

    public function updateItemQuantity($id, $quantity) {
        if (isset($this->items[$id])) {
            $this->items[$id]->setQuantity($quantity);
        } else {
            throw new Exception('Item not found');
        }
    }
}

// Error handling middleware
$errorMiddleware = function ($request, $handler) {
    return function (Request $request, array $options) use ($request, $handler) {
        try {
            $response = $handler($request, $options);
        } catch (Exception $e) {
            $response = $handler->withJson(['error' => $e->getMessage()], 400);
        }
        return $response;
    };
};

$app = AppFactory::create();

// Add error handling middleware
$app->add($errorMiddleware);

// Route to add an item to inventory
$app->post('/add-item', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $item = new InventoryItem($data['id'], $data['name'], $data['quantity']);
    $inventoryService = new InventoryService();
    $inventoryService->addItem($item);
    return $response->withJson(['message' => 'Item added successfully'], 201);
});

// Route to get an item from inventory
$app->get('/item/{id}', function (Request $request, Response $response, $args) {
    $inventoryService = new InventoryService();
    try {
        $item = $inventoryService->getItem($args['id']);
        return $response->withJson(['id' => $item->getId(), 'name' => $item->getName(), 'quantity' => $item->getQuantity()]);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 404);
    }
});

// Route to update an item's quantity in inventory
$app->put('/item/{id}', function (Request $request, Response $response, $args) {
    $inventoryService = new InventoryService();
    try {
        $data = $request->getParsedBody();
        $inventoryService->updateItemQuantity($args['id'], $data['quantity']);
        return $response->withJson(['message' => 'Item quantity updated successfully']);
    } catch (Exception $e) {
        return $response->withJson(['error' => $e->getMessage()], 404);
    }
});

$app->run();
