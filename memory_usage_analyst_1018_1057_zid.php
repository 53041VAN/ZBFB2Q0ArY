<?php
// 代码生成时间: 2025-10-18 10:57:44
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Exception\HttpSpecialResponseException;
use Slim\Exception\HttpNotFoundException;

// MemoryUsageAnalystService 用于分析内存使用情况
class MemoryUsageAnalystService {
    public function analyzeMemoryUsage() {
        // 获取当前使用的内存
        $currentMemoryUsage = memory_get_usage();
        // 获取当前峰值内存使用量
        $peakMemoryUsage = memory_get_peak_usage();

        return [
            'current' => $currentMemoryUsage,
            'peak' => $peakMemoryUsage,
        ];
    }
}

// 创建Slim应用
AppFactory::settings(["displayErrorDetails