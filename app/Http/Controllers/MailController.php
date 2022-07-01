<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
// use Mail;

class MailController extends Controller
{
  public function txt_mail()
  {
      $info = array(
          'name' => "Alex"
      );
      Mail::send(['text' => 'mail'], $info, function ($message)
      {
          $message->to('alijudo3@gmail.com', 'W3SCHOOLS')
              ->subject('Basic test eMail from W3schools.');
          $message->from('cartshopping123@gmail.com', 'Alex');
      });
      echo "Successfully sent the email";
  }

  public function html_mail()
  {
      $info = array(
          'name' => "Alex"
      );
      Mail::send('mail', $info, function ($message)
      {
          $message->to('alijudo3@gmail.com', 'w3schools')
              ->subject('HTML test eMail from W3schools.');
          $message->from('cartshopping123@gmail.com', 'Alex');
      });
      echo "Successfully sent the email";
  }

  public function attached_mail()
  {
      $info = array(
          'name' => "Alex"
      );
      Mail::send('mail', $info, function ($message)
      {
          $message->to('alijudo3@gmail.com', 'w3schools')
              ->subject('Test eMail with an attachment from W3schools.');
          $message->attach('D:\laravel_main\laravel\public\uploads\pic.jpg');
          $message->attach('D:\laravel_main\laravel\public\uploads\message_mail.txt');
          $message->from('cartshopping123@gmail.com', 'Alex');
      });
      echo "Successfully sent the email";
  }
}
