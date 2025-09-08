<?php
// 代码生成时间: 2025-09-08 13:44:02
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Middleware\ErrorMiddleware;
use Dflydev\DotAccessData\Data;
use Slim\Handlers\AbstractHandler;
use Slim\Csrf\Guard;
use Slim\Csrf\Token;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\AllOfException;

// Define a custom access control middleware
class AccessControlMiddleware extends AbstractHandler
{
    protected $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function handle(Request $request, Response $response, callable $next): Response
    {
        // Get the authenticated user from the request
        $user = $request->getAttribute('user');

        // Check if the user has the required roles
        if (!$user || !in_array($user->role, $this->roles)) {
            return $response->withJson(
                ['error' => 'Access denied'],
                403
            );
        }

        // If access is granted, proceed to the next middleware
        return $next($request, $response);
    }
}

// Define a route that requires admin role
$app->get('/admin', function (Request $request, Response $response, $args): Response {
    // Access control for admin route
    $user = $request->getAttribute('user');
    return $response->write('Welcome Admin ' . $user->name);
})->add(new AccessControlMiddleware(['admin']));

// Define a route that requires user role
$app->get('/user', function (Request $request, Response $response, $args): Response {
    // Access control for user route
    $user = $request->getAttribute('user');
    return $response->write('Welcome User ' . $user->name);
})->add(new AccessControlMiddleware(['user']));

// Define a route that doesn't require any role (public route)
$app->get('/', function (Request $request, Response $response, $args): Response {
    return $response->write('Welcome to the public page');
});

// Define error handler
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(
    function (Request $request, Response $response, \Exception $exception): Response {
        return $response->withJson(
            ['error' => 'An error occurred'],
            500
        );
    }
);

// Run the application
AppFactory::createFromEnvironment($app)->run();
