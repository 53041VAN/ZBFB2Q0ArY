<?php
// 代码生成时间: 2025-10-19 07:26:08
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// 定义一个类来处理文件元数据提取
class FileMetadataExtractor {
    public function extractMetadata(string $filePath): array {
        // 检查文件是否存在
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException('File not found: ' . $filePath);
        }

        // 获取文件的基本信息
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $fileInfo->file($filePath);
        $size = filesize($filePath);
        $lastModified = filemtime($filePath);

        return [
            'mime_type' => $mime,
            'size' => $size,
            'last_modified' => $lastModified
        ];
    }
}

// 创建Slim应用
AppFactory::setCodingStylePrettifyErrors(true);
$app = AppFactory::create();

// 定义路由来提取文件元数据
$app->get('/extract-metadata/{filePath}', function (Request $request, Response $response, $args) {
    $filePath = $args['filePath'];
    try {
        // 创建FileMetadataExtractor实例
        $extractor = new FileMetadataExtractor();

        // 提取文件元数据
        $metadata = $extractor->extractMetadata($filePath);

        // 设置响应内容和状态码
        $response->getBody()->write(json_encode($metadata));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Throwable $e) {
        // 设置错误响应内容和状态码
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// 运行应用
$app->run();