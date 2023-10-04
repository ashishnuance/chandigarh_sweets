<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\MyCustomMail;

class MailController extends Controller
{
    public function index()
    {
        $mailData = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp.'
        ];
         
        // Mail::getSwiftMailer()->registerPlugin (
        //     new Swift_Plugins_LoggerPlugin(new Swift_Plugins_Loggers_EchoLogger(false))
        // );
        // $to = 'youraddress@example.com';
        // $mail = Mail::raw('Testmail', function ($message)  {  
        //     $message->to('ashishnuance@gmail.com')->subject('Testmail'); 
        // });
        
        $mail = Mail::to('ashishnuance@gmail.com')->send(new MyCustomMail($mailData));
        echo env('MAIL_HOST');
        echo "Email is sent successfully.";
        dd($mail);
    }
}
