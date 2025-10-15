<?php
// 代码生成时间: 2025-10-15 18:54:46
// 引入Slim框架的自动加载器
require 'vendor/autoload.php';

// 使用Slim创建应用
$app = new \Slim\Slim();

// 定义代币经济模型
class TokenEconomyModel {
    // 存储代币的总量
    private $totalSupply;
    // 用户持有的代币
    private $userBalances;

    public function __construct() {
        $this->totalSupply = 1000000;
        $this->userBalances = [];
    }

    // 添加新的代币到系统
    public function addTokens($amount) {
        if ($amount < 0) {
            throw new Exception('添加的代币数量不能为负。');
        }
        $this->totalSupply += $amount;
    }

    // 用户创建账户并分配代币
    public function createUser($username, $balance = 0) {
        if (array_key_exists($username, $this->userBalances)) {
            throw new Exception('用户名已存在。');
        }
        $this->userBalances[$username] = $balance;
    }

    // 用户之间转移代币
    public function transferTokens($from, $to, $amount) {
        if (!isset($this->userBalances[$from]) || !isset($this->userBalances[$to])) {
            throw new Exception('用户名不存在。');
        }
        if ($this->userBalances[$from] < $amount) {
            throw new Exception('代币数量不足。');
        }
        $this->userBalances[$from] -= $amount;
        $this->userBalances[$to] += $amount;
    }

    // 获取用户的代币余额
    public function getBalance($username) {
        if (!isset($this->userBalances[$username])) {
            throw new Exception('用户名不存在。');
        }
        return $this->userBalances[$username];
    }

    // 获取代币的总量
    public function getTotalSupply() {
        return $this->totalSupply;
    }
}

// 定义路由
$app->get('/add-tokens/:amount', function ($amount) use ($app) {
    $model = new TokenEconomyModel();
    try {
        $model->addTokens((int) $amount);
        $app->response()->body("代币添加成功，当前总量为：" . $model->getTotalSupply());
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->body("错误：" . $e->getMessage());
    }
});

$app->get('/create-user/:username/:balance', function ($username, $balance) use ($app) {
    $model = new TokenEconomyModel();
    try {
        $model->createUser($username, (int) $balance);
        $app->response()->body("用户创建成功。");
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->body("错误：" . $e->getMessage());
    }
});

$app->get('/transfer-tokens/:from/:to/:amount', function ($from, $to, $amount) use ($app) {
    $model = new TokenEconomyModel();
    try {
        $model->transferTokens($from, $to, (int) $amount);
        $app->response()->body("代币转移成功。");
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->body("错误：" . $e->getMessage());
    }
});

$app->get('/balance/:username', function ($username) use ($app) {
    $model = new TokenEconomyModel();
    try {
        $balance = $model->getBalance($username);
        $app->response()->body("用户的代币余额：" . $balance);
    } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->body("错误：" . $e->getMessage());
    }
});

// 运行应用
$app->run();
