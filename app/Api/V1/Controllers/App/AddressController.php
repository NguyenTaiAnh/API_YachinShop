<?php

namespace App\Api\V1\Controllers\App;

use App\Addresses;
use App\Http\Controllers\Controller;

use Dingo\Api\Auth\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AddressController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('customer');
    }

    public function list()
    {
        DB::beginTransaction();
        try {
            $addr = Addresses::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
            $count = (float)count($addr);
            if (!$addr)
                throw new NotFoundHttpException('Not data category');
            if ($count == 0) {
                $data = [];
            } else {
                foreach ($addr as $item) {
                    $item->provinces;
                    $item->districts;
                    $item->wards;
                    $data = $addr;
                }
            }

            DB::commit();
            return response()->json([
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
                'receiver_name' => 'required',
                'province' => 'required',
                'district' => 'required',
                'ward' => 'required',
                'village' => 'required',
                'phone' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $create = new Addresses();
            $create->receiver_name = $request['receiver_name'];
            $create->receiver_email = $request['receiver_email'];
            $create->province = $request['province'];
            $create->district = $request['district'];
            $create->ward = $request['ward'];
            $create->village = $request['village'];
            $create->phone = $request['phone'];
            $create->user_id = auth()->user()->id;
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
                'receiver_name' => 'required',
                'province' => 'required',
                'district' => 'required',
                'ward' => 'required',
                'village' => 'required',
                'phone' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $create = Addresses::find($id);
            $create->receiver_name = $request['receiver_name'];
            $create->receiver_email = $request['receiver_email'];
            $create->province = $request['province'];
            $create->district = $request['district'];
            $create->ward = $request['ward'];
            $create->village = $request['village'];
            $create->phone = $request['phone'];
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

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $delete = Addresses::find($id);
            if (!$delete) {
                throw new NotFoundHttpException('Id not fuount');
            }
            $delete->delete();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $delete
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $address = Addresses::where('id', $id)->get();
            if (!$address) {
                throw new NotFoundHttpException('Id not found');
            }
            foreach ($address as $item) {
                $item->provinces;
                $item->districts;
                $item->wards;
                $data = $address;
            }
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function sort()
    {
        DB::beginTransaction();
        try {
            $add = Addresses::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => true,
                'data' => $add
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
