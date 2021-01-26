<?php

namespace App\Api\V1\Controllers\App;

use App\Banner;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BannerController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function list()
    {
        DB::beginTransaction();
        try {
            $banner = Banner::all();
            if (!$banner) {
                throw new NotFoundHttpException('No data banner');
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $banner
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
                'path' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'location' => 'required',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $create = new Banner();
            $create->location = $request['location'];
            $create->status = $request['status'];
            if ($request->hasFile('path')) {
                $file = $request->file('path');
                $destinationFileName = $file->getClientOriginalName();
                $destinationPath = public_path() . '/assets/images/banner';
                $file->move($destinationPath, $destinationFileName);

                $create->path = 'https://sys.yachin.vn/assets/images/banner/' . $destinationFileName;;

            }
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
        $delete = Banner::find($id);
        if (!$delete) {
            throw new NotFoundHttpException('No data banner');
        }
        $delete->delete();
        return response()->json([
            'status' => true,
            'data' => $delete
        ]);
    }
}
