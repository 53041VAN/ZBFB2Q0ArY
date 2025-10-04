<?php
// 代码生成时间: 2025-10-05 01:49:27
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Psr7\Uri;
use Psr\Http\Server\RequestHandlerInterface;

require __DIR__ . '/../vendor/autoload.php';

// 创建SLIM应用
AppFactory::setContainer(DI\Container::getDefault());
$app = AppFactory::create();

// DNS解析和缓存工具
$app->get('/resolve/{domain}', function(Request $request, Response $response, array $args): Response {
    $domain = $args['domain'];
    // 获取DNS记录
    $dnsRecord = dns_get_record($domain);
    if (empty($dnsRecord)) {
        // DNS解析失败，返回错误信息
        return $response->withStatus(500)
                    ->withHeader('Content-Type', 'application/json')
                    ->getBody()->write(json_encode(['error' => 'DNS解析失败']));
    }
    
    // 缓存DNS记录
    cache_dns_record($domain, $dnsRecord);
    
    // 返回DNS记录
    return $response->withHeader('Content-Type', 'application/json')
                ->getBody()->write(json_encode($dnsRecord));
});

// 缓存DNS记录到文件
function cache_dns_record($domain, $dnsRecord) {
    $cacheFile = __DIR__ . '/cache/' . $domain . '.cache';
    $cacheDir = dirname($cacheFile);
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }
    file_put_contents($cacheFile, serialize($dnsRecord));
}

// 读取缓存的DNS记录
function get_cached_dns_record($domain) {
    $cacheFile = __DIR__ . '/cache/' . $domain . '.cache';
    if (file_exists($cacheFile)) {
        return unserialize(file_get_contents($cacheFile));
    }
    return null;
}

$app->run();