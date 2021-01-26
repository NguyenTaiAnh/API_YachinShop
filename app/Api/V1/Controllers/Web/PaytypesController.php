<?php

namespace App\Api\V1\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Paytypes;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaytypesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin')->except('list');
    }

    public function list()
    {
        DB::beginTransaction();
        try {
            $data = Paytypes::all();
            if (!$data)
                throw new NotFoundHttpException('Data is not available');
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail($id){
        DB::beginTransaction();
        try{
            $data = Paytypes::find($id);
            if (!$data)
                throw new NotFoundHttpException('Id not found');
            DB::commit();
            return response()->json([
                'status'=>true,
                'data'=> $data
            ]);
        } catch (\Exception $e){
            throw $e;
        }
    }


    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'payment' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $add = new Paytypes;
            $add->payment = $request['payment'];
            $add->save();
            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $add
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function edit($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'payment' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $paytypes = Paytypes::find($id);
            $paytypes->payment = $request['payment'];
            $paytypes->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $paytypes
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

    }

    Public function delete($id)
    {
        $del = Paytypes::find($id);
        if (!$del)
            throw new NotFoundHttpException('Id not found');

        $del->delete();
        return response()->json([
            'status' => true,
            'data' => $del
        ]);
    }
}
