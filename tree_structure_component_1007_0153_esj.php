<?php
// 代码生成时间: 2025-10-07 01:53:20
// 使用Slim框架，创建一个树形结构组件
require 'vendor/autoload.php';

$app = new \Slim\App();

// 树形结构组件类
class TreeComponent {
    protected $items;

    public function __construct($items) {
        $this->items = $items;
    }

    // 添加节点
    public function addNode($parentId = null, $data) {
        if (!array_key_exists('id', $data) || !array_key_exists('name', $data)) {
            throw new \Exception('Node data must contain id and name keys.');
        }

        $node = [
            'id' => $data['id'],
            'name' => $data['name'],
            'children' => []
        ];

        if ($parentId === null) {
            $this->items[$node['id']] = $node;
        } else {
            if (!isset($this->items[$parentId])) {
                throw new \Exception('Parent node does not exist.');
            }

            $this->items[$parentId]['children'][] = $node;
        }
    }

    // 获取树形结构
    public function getTree() {
        return $this->items;
    }
}

// 树形结构组件的路由
$app->get('/tree', function ($request, $response, $args) {
    $tree = new TreeComponent([]);
    $tree->addNode(null, ['id' => 1, 'name' => 'Root']);
    $tree->addNode(1, ['id' => 2, 'name' => 'Child 1']);
    $tree->addNode(1, ['id' => 3, 'name' => 'Child 2']);
    $tree->addNode(2, ['id' => 4, 'name' => 'Grandchild 1']);

    $response->getBody()->write(json_encode($tree->getTree()));
    return $response;
});

$app->run();