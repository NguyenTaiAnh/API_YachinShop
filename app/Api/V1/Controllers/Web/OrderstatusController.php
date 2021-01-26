<?php

namespace App\Api\V1\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Order_status;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderstatusController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function show()
    {
        DB::beginTransaction();
        try {
            $data = Order_status::all();
            if (!$data)
                throw new NotFoundHttpException('Not data category');
            DB::commit();
            return response()
                ->json([
                    'status' => true,
                    'data' => $data
                ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $create = new Order_status();
            $create->name = $request['name'];
            $create->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $create
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
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $edit = Order_status::find($id);
            $edit->name = $edit['name'];
            $edit->save();

            return response()->json([
                'status' => true,
                'data' => $edit
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        $del = Order_status::find($id);
        if (!$del)
            throw new NotFoundHttpException('Id not found');

        $del->delete();
        return response()->json([
            'status' => true,
            'data' => $del
        ]);
    }
}
