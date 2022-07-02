<?php

namespace App\Http\Controllers;
use Error;
use App\Mail\SuccessMail as SMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class secret extends Controller
{
    public function store(Request $request)
    {
      //
      header('Content-Type: application/json');
      
      $stripe = new \Stripe\StripeClient(
        'sk_test_51LEwj8KwHhxzhn4RYAYqHKfTweb3x3j4CakFdUjuZRfbOD4babCyWdyIuLjUPqTrzcMB2k55veW5ONzoYkzuaPT900VD53FjTx'
      );
      
      # retrieve json from POST body
      $json_str = file_get_contents('php://input');
      $json_obj = json_decode($json_str);
      
      $intent = null;
      try {
        // return ['json_obj' => $json_obj];
        if (isset($json_obj->payment_method_id)) {

          # Create the PaymentIntent
          $intent = $stripe->paymentIntents->create([
            'payment_method' => $json_obj->payment_method_id,
            'amount' => $json_obj->amount * 100,
            'currency' => 'usd',
            'confirmation_method' => 'manual',
            'confirm' => true,
          ]);
        }
        
        if (isset($json_obj->payment_intent_id)) {
          $intent = $stripe->paymentIntents->retrieve(
            $json_obj->payment_intent_id
          );
          $intent->confirm();
        }
        // Send Mail with payment info to Customer Email
        Mail::to($json_obj->email)->send(new SMail());
        $this->generateResponse($intent);

      } catch (\Stripe\Exception\ApiErrorException $e) {
        # Display error on client
        echo json_encode([
          'error' => $e->getMessage()
        ]);
      }
    }
    public function generateResponse($intent) {
      # Note that if your API version is before 2019-02-11, 'requires_action'
      # appears as 'requires_source_action'.
      if ($intent->status == 'requires_action' &&
          $intent->next_action->type == 'use_stripe_sdk') {
        # Tell the client to handle the action
        echo json_encode([
          'requires_action' => true,
          'payment_intent_client_secret' => $intent->client_secret
        ]);
      } else if ($intent->status == 'succeeded') {
        # The payment didn’t need any additional actions and completed!
        # Handle post-payment fulfillment
        echo json_encode([
          "success" => true
        ]);
      } else {
        # Invalid status
        http_response_code(500);
        echo json_encode(['error' => 'Invalid PaymentIntent status']);
      }
    }
}
