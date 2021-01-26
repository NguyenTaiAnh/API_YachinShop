<?php

namespace App\Api\V1\Controllers\Web;

use App\District;
use App\Http\Controllers\Controller;
use App\Province;
use App\Village;
use App\Ward;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocationController extends Controller
{
    /*
     * get Province
     */
    public function getProvince()
    {
        DB::beginTransaction();
        try {
            $data = Province::all();
            if (!$data)
                throw new NotFoundHttpException('Invalid data supplied');
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'province' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*
     * get District
     * input: province ID
     * output: all District
     */
    public function getDistrict($provinceID)
    {
        DB::beginTransaction();
        try {
            $data = District::where('provinceid', $provinceID)->get();
            if (!$data)
                throw new NotFoundHttpException('Invalid ID supplied');
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'district' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*
     * get ward
     * in: District ID
     * out: ward all
     */
    public function getWard($districtID)
    {
        DB::beginTransaction();
        try {
            $data = Ward::where('districtid', $districtID)->get();
            if (!$data)
                throw new NotFoundHttpException('Invalid ID supplied');
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'ward' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*
     * get ward
     * in: District ID
     * out: ward all
     */
    public function getVillage($wardID)
    {
        DB::beginTransaction();
        try {
            $data = Village::where('wardid', $wardID)->get();
            if (!$data)
                throw new NotFoundHttpException('Invalid ID supplied');
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'success',
                'village' => $data
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


}
