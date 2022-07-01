<?php

// $stripe = new \Stripe\StripeClient('sk_test_51LEwj8KwHhxzhn4RYAYqHKfTweb3x3j4CakFdUjuZRfbOD4babCyWdyIuLjUPqTrzcMB2k55veW5ONzoYkzuaPT900VD53FjTx');
// $customer = $stripe->customers->create([
//     'description' => 'example customer',
//     'email' => 'email@example.com',
//     'payment_method' => 'pm_card_visa',
// ]);
// echo $customer;


// require 'vendor/autoload.php';

// This is your test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51LEwj8KwHhxzhn4RYAYqHKfTweb3x3j4CakFdUjuZRfbOD4babCyWdyIuLjUPqTrzcMB2k55veW5ONzoYkzuaPT900VD53FjTx');

function calculateOrderAmount(array $items): int {
    // Replace this constant with a calculation of the order's amount
    // Calculate the order total on the server to prevent
    // people from directly manipulating the amount on the client
    return 1400;
}

header("Access-Control-Allow-Origin: Content-Type");
header("Access-Control-Allow-Credentials: true");
// header('Content-Type: application/json');
header('Content-Type', 'text/plain');

try {
    // retrieve JSON from POST body
    // $jsonStr = file_get_contents('php://input');
    // $jsonObj = json_decode($jsonStr);

    // Create a PaymentIntent with amount and currency
    $paymentIntent = \Stripe\PaymentIntent::create([
        // 'amount' => calculateOrderAmount($jsonObj->items),
        'amount' => 100,
        'currency' => 'eur',
        'automatic_payment_methods' => [
            'enabled' => true,
        ],
    ]);

    $output = [
        'clientSecret' => $paymentIntent->client_secret,
    ];

    echo json_encode($output);
    return json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
