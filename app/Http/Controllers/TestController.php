<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
// use Mail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\TestEmailSender;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public $name;
    public $email;

    public function __construct(){}

    public function sendEmail()
    {               
            $this->name = "Deepak"; //recipient name
            $this->email = "kksr4941@gmail.com"; //recipient email id
            /**
             *creating an empty object of of stdClass
             *
             */
            $emailParams = new \stdClass(); 
            $emailParams->usersName = $this->name;
            $emailParams->usersEmail = $this->email;
           
            $emailParams->subject = "Testing Email sending feature";
            Mail::to($emailParams->usersEmail)->send(new TestEmailSender($emailParams));
    }   

    public function test(){            
           $this->sendEmail();
    }

    public function mail()
    {
        $name = 'Krunal';
        Mail::to('krunal@appdividend.com')->send(new TestEmailSender($name));
        
        return 'Email was sent';
    }
}