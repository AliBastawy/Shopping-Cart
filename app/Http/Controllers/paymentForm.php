<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Error;

// class paymentForm extends Controller
// {
//     //
//     public function store()
//     {      
//       \Stripe\Stripe::setApiKey('sk_test_51LEwj8KwHhxzhn4RYAYqHKfTweb3x3j4CakFdUjuZRfbOD4babCyWdyIuLjUPqTrzcMB2k55veW5ONzoYkzuaPT900VD53FjTx');
      
//       function calculateOrderAmount(array $items): int {
//         // Replace this constant with a calculation of the order's amount
//         // Calculate the order total on the server to prevent
//         // people from directly manipulating the amount on the client
//         return 1400;
//       }
    
//       header('Content-Type: application/json');

//       $stripe = new \Stripe\StripeClient(
//         'sk_test_51LEwj8KwHhxzhn4RYAYqHKfTweb3x3j4CakFdUjuZRfbOD4babCyWdyIuLjUPqTrzcMB2k55veW5ONzoYkzuaPT900VD53FjTx'
//       );
//       $sess = $stripe->checkout->sessions->create([
//         'success_url' => 'http://localhost:8081',
//         'cancel_url' => 'http://localhost:8081',
//         'line_items' => [
//           [
//             'price' => 'price_1LEwrdKwHhxzhn4RpTyQuaNW',
//             'quantity' => 2,
//           ],
//         ],
//         'mode' => 'payment',
//       ]);

//       $intent = \Stripe\PaymentIntent::retrieve(
//         $sess->payment_intent
//       );
      
//       try {
//           // retrieve JSON from POST body
//           $jsonStr = file_get_contents('php://input');
//           $jsonObj = json_decode($jsonStr);
      
//           // Create a PaymentIntent with amount and currency
//           $paymentIntent = \Stripe\PaymentIntent::create([
//               // 'payment_method' => $jsonObj->payment_method_id,  
//               'amount' => calculateOrderAmount($jsonObj->items),
//               'currency' => $jsonObj->currency,
//               'automatic_payment_methods' => [
//                   'enabled' => true,
//               ],
//           ]);
      
//           $output = [
//               'total' => $paymentIntent,
//               'clientSecret' => $paymentIntent->client_secret,
//               'sess' => $sess,
//               'intent' => $intent,

//           ];
      
//           echo json_encode($output);
//       } catch (Error $e) {
//           http_response_code(500);
//           echo json_encode(['error' => $e->getMessage()]);
//       }
  
//       // return ['clientSecret' => $paymentIntent->client_secret];
//       // }
//     }
// }

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Error;

class paymentForm extends Controller
{
    //
    public function store()
    {    
      header('Content-Type: application/json');

      $stripe = new \Stripe\StripeClient(
        'sk_test_51LEwj8KwHhxzhn4RYAYqHKfTweb3x3j4CakFdUjuZRfbOD4babCyWdyIuLjUPqTrzcMB2k55veW5ONzoYkzuaPT900VD53FjTx'
      );
      
      $customer = $stripe->customers->create([
        'description' => 'New Customer',
      ]);
      
      try {
          // retrieve JSON from POST body
          $jsonStr = file_get_contents('php://input');
          $jsonObj = json_decode($jsonStr);

            if ($jsonObj->lineItems) {
        
            // Create Invoice Items Connected to the Products Created in Stripe
            foreach ($jsonObj->lineItems as $item) {
              $stripe->invoiceItems->create([
                'customer' => $customer->id,
                'price' => $item->price,
                'quantity' => $item->quantity
              ]);
            }
            
            // Create Invoice connected to the created invoice items
            $invoices = $stripe->invoices->create([
              'customer' => $customer->id,
            ]);

            // Finalize Invoice to make payment intent
            $finalizedInvoices = $stripe->invoices->finalizeInvoice(
              $invoices->id,
            );

            // Get payment intent from the Finalized invoice and get the client secret from it
            $intent = $stripe->paymentIntents->retrieve(
              $finalizedInvoices->payment_intent
            );
        
            $output = [
                'clientSecret' => $intent->client_secret,
            ];
        
            echo json_encode($output);
          } else {
            // Send email to customer
            $output = [
                'data' => $jsonObj,
            ];
        
            echo json_encode($output);

          }
      } catch (Error $e) {
          http_response_code(500);
          echo json_encode(['error' => $e->getMessage()]);
      }
  
      // return ['clientSecret' => $paymentIntent->client_secret];
      // }
    }
}