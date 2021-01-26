<?php

namespace App\Api\V1\Controllers\Web;

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
        $this->middleware('admin');
    }

    public function sendmail(Request $request){

        $this->email = 'abc';
        $toEmail = $request['email'];
        Mail::to($toEmail)->send(new SendMail($this->email));

        return response()->json([
           'status'=>true,
//            'data'=>$order
        ]);
    }
}
