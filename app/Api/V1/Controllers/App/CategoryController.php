<?php

namespace App\Api\V1\Controllers\App;

use App\Category;
use App\Device_token;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use FCM;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    //
    public function __construct()
    {
//        $this->middleware('customer');
    }

    public function list()
    {
        DB::beginTransaction();
        try {
            $data = Category::all();
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

    public function new()
    {
        DB::beginTransaction();
        try {
            $new = Category::all()->sortByDesc('created_at')->take('8')->values();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $new
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }


}
