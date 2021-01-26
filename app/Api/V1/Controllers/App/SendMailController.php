<?php

namespace App\Api\V1\Controllers\App;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;



class SendMailController extends Controller
{
    //
//    public $email;
    public function __construct()
    {
        $this->middleware('customer');
    }

    public function sendmail($id,Request $request){

        $this->email = 'abc';
        $this->id = $id;
//        die($this->id);
        $toEmail = Auth()->user()->email;
        Mail::to($toEmail)->send(new SendMail($this->email,$this->id));
        return response()->json([
            'status'=>true,
        ]);
    }
}
