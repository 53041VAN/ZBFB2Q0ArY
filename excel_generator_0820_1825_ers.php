<?php
// 代码生成时间: 2025-08-20 18:25:51
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Slim\Factory\ServerRequestFactory;
use Slim\Factory\RouterFactory;
use Slim\Factory\AppFactory;

// 定义一个基本的Slack应用
require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

// 路由设置
$router = RouterFactory::create();

// 定义生成Excel表格的路由
$router->post('/generate-excel', function ($request, $response, $args) {

    // 获取请求体中的数据
    $data = $request->getParsedBody();
    
    // 检查数据是否存在
    if (empty($data)) {
        return $response->withStatus(400)
                        ->withJson(['error' => 'No data provided']);
    }

    // 创建一个新的Spreadsheet实例
    $spreadsheet = new Spreadsheet();
    
    // 创建一个工作表
    $sheet = $spreadsheet->getActiveSheet();
    
    // 设置工作表的标题
    $sheet->setTitle('Generated Data');
    
    // 遍历数据并填充工作表
    foreach ($data as $rowIndex => $rowData) {
        foreach ($rowData as $columnIndex => $value) {
            // 将数据写入单元格
            $sheet->setCellValueByColumnAndRow($columnIndex + 1, $rowIndex + 1, $value);
        }
    }

    // 设置HTTP头信息，以便浏览器下载文件
    $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response = $response->withHeader('Content-Disposition', 'attachment; filename="generated_data.xlsx"');

    // 写入Excel文件
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    
    // 返回响应
    return $response;
});

// 添加路由到应用
$app->addRoute($router->getRoutes());

// 运行应用
$app->run();

?>