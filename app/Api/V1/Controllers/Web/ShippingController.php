<?php

namespace App\Api\V1\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ShippingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function get()
    {
        $data = Shipping::all();
        if (!$data)
            throw new NotFoundHttpException('Not data Shipping');
        DB::commit();
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function detail($id)
    {
        DB::beginTransaction();
        try {
            $data = Shipping::find($id);
            if (!$data)
                throw new NotFoundHttpException('Id not found');
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
            $ship = new Shipping();
            $ship->name = $request['name'];
            $ship->cost = $request['cost'];
            $ship->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $ship
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $ship = Shipping::find($id);
            $ship->name = $request['name'];
            $ship->cost = $request['cost'];
            $ship->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $ship
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        $data  = Shipping::find($id);
        $data->delete();
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
