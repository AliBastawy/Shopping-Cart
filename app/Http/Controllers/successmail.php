<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SuccessMail as SMail;
use Illuminate\Support\Facades\Mail;
use Error;

class successmail extends Controller
{
    //
    public function store() {

      header('Content-Type: application/json');
      
      try {
        $jsonStr = file_get_contents('php://input');
        $jsonObj = json_decode($jsonStr);
        // print_r($jsonObj);

        if(isset($jsonObj->email)) {
          Mail::to($jsonObj->email)->send(new SMail());
        }
      } catch (Error $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
      }

    }
}
