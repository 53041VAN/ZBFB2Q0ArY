<?php
// 代码生成时间: 2025-08-28 22:18:27
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// Define constants for process states
define('PROCESS_RUNNING', 1);
define('PROCESS_STOPPED', 0);

// Define a class to manage processes
class ProcessManager {
    private $processes = [];

    /**
     * Start a new process
     *
     * @param string $name
     * @param callable $callback
     */
    public function startProcess($name, callable $callback) {
        if (isset($this->processes[$name])) {
            throw new Exception("Process already exists.");
        }

        $this->processes[$name] = [
            'status' => PROCESS_RUNNING,
            'callback' => $callback
        ];
    }

    /**
     * Stop an existing process
     *
     * @param string $name
     */
    public function stopProcess($name) {
        if (!isset($this->processes[$name])) {
            throw new Exception("Process does not exist.");
        }

        $this->processes[$name]['status'] = PROCESS_STOPPED;
    }

    /**
     * List all active processes
     *
     * @return array
     */
    public function listProcesses() {
        return array_filter($this->processes, function ($process) {
            return $process['status'] === PROCESS_RUNNING;
        });
    }
}

// Create a new Slim application
$app = AppFactory::create();

// Define routes
$app->get('/start/{name}', function (Request $request, Response $response, $args) use ($app) {
    $processManager = new ProcessManager();
    $name = $args['name'];
    $processManager->startProcess($name, function () {
        echo 'Process is running.';
    });

    return $response->write("Process {$name} started.");
});

$app->get('/stop/{name}', function (Request $request, Response $response, $args) use ($app) {
    $processManager = new ProcessManager();
    $name = $args['name'];
    try {
        $processManager->stopProcess($name);
        return $response->write("Process {$name} stopped.");
    } catch (Exception $e) {
        return $response->withStatus(400)->write($e->getMessage());
    }
});

$app->get('/list', function (Request $request, Response $response) {
    $processManager = new ProcessManager();
    $activeProcesses = $processManager->listProcesses();

    return $response->write(json_encode($activeProcesses));
});

// Run the application
$app->run();