<?php

namespace App\Api\V1\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Notifi;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class NotifiController extends Controller
{

    public $email;
    public function __construct()
    {
        $this->middleware('admin');
    }


    public function shownotifi(){
        DB::beginTransaction();
        try{
            $notifi = Notifi::all();
            if (!$notifi){
                throw new NotFoundHttpException('Not data notification');
            }
            DB::commit();
            return response()->json([
                'status'=>true,
                'data'=>$notifi
            ]);

        }catch (\Exception $e){
            throw $e;
        }

    }
    public function editnotifi($id, Request $request){
        DB::beginTransaction();
        try{
            $notifi = Notifi::find($id)->first();
            $notifi->status = $request['status'];
            $notifi->save();

            DB::commit();
            return response()->json([
                'status'=>true,
                'data'=>$notifi
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }
    public function new(){
        DB::beginTransaction();
        try{
            $new = Notifi::where('status',0)->orderBy('created_at', 'desc')->get();
            if(!$new){
                throw new NotFoundHttpException('Not data notification');
            }
            DB::commit();
            return response()->json([
                'status'=>true,
                'data'=>$new
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }
    public function  confirm(){
        DB::beginTransaction();
        try{
            $confirm= Notifi::where('status',1)->orderBy('created_at', 'desc')->get();
            if(!$confirm){
                throw new NotFoundHttpException('Not data notification');
            }
            DB::commit();
            return response()->json([
                'status'=>true,
                'data'=>$confirm
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }
}
