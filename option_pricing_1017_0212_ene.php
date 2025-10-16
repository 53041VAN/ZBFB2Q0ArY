<?php
// 代码生成时间: 2025-10-17 02:12:37
require 'vendor/autoload.php';

// Define the OptionPricing class
class OptionPricing {

    // Calculate the Black-Scholes option price
    public function blackScholes($S, $K, $T, $r, $sigma) {
        // Check for invalid inputs
        if ($S <= 0 || $K <= 0 || $T <= 0 || $r < 0 || $sigma <= 0) {
            throw new InvalidArgumentException('Invalid input parameters for Black-Scholes formula.');
        }

        // Calculate d1 and d2
        $d1 = (log($S / $K) + ($r + pow($sigma, 2) / 2) * $T) / ($sigma * sqrt($T));
        $d2 = $d1 - $sigma * sqrt($T);

        // Calculate N(d1) and N(d2) using the cumulative normal distribution function
        $Nd1 = $this->cumulativeNormal($d1);
        $Nd2 = $this->cumulativeNormal($d2);

        // Calculate the option price using the Black-Scholes formula
        $price = $S * exp(-$r * $T) * $Nd1 - $K * exp(-$r * $T) * $Nd2;

        return $price;
    }

    // Calculate the cumulative normal distribution function
    private function cumulativeNormal($x) {
        $a1 = 0.319381530;
        $a2 = -0.356563782;
        $a3 = 1.781477937;
        $a4 = -1.821255978;
        $a5 = 1.330274429;
        $p = 0.231641822;
        $y = $x;
        if (abs($x) > 1) {
            $y = 1 / $x;
            $z = 1 - $y;
            $y = 1 - $z * $z;
        } else {
            $z = $x * $x;
            $y = 1 - $z;
        }
        $y = ((((($a5 * $y + $a4) * $y + $a3) * $y + $a2) * $y + $a1) * $y + 1) / 
              sqrt(2 * pi()) * exp(-$z / 2);
        if (abs($x) > 1) {
            return 1 - $p * $y;
        } else {
            return $p * $y;
        }
    }
}

// Initialize the SLIM application
$app = new \Slim\Slim();

// Define the route for calculating option price
$app->get('/option-price', function () use ($app) {
    // Get the input parameters from the query string
    $S = $app->request->get('S');
    $K = $app->request->get('K');
    $T = $app->request->get('T');
    $r = $app->request->get('r');
    $sigma = $app->request->get('sigma');

    try {
        // Validate and sanitize input parameters
        $S = filter_var($S, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        $K = filter_var($K, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        $T = filter_var($T, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        $r = filter_var($r, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        $sigma = filter_var($sigma, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);

        if ($S === null || $K === null || $T === null || $r === null || $sigma === null) {
            $app->response()->status(400);
            $app->response()->body(json_encode(array('error' => 'Invalid input parameters.')));
            return;
        }

        // Create an instance of the OptionPricing class
        $optionPricing = new OptionPricing();

        // Calculate the option price using the Black-Scholes formula
        $price = $optionPricing->blackScholes($S, $K, $T, $r, $sigma);

        // Return the option price as a JSON response
        $app->response()->body(json_encode(array('optionPrice' => $price)));
    } catch (InvalidArgumentException $e) {
        $app->response()->status(400);
        $app->response()->body(json_encode(array('error' => $e->getMessage())));
    }
});

// Run the SLIM application
$app->run();