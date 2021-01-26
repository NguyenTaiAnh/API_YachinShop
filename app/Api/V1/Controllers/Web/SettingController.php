<?php

namespace App\Api\V1\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Infostore;
use App\Setting_slide;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin')->except('detailStore');
    }

    public function list()
    {
        DB::beginTransaction();
        try {
            $show = Setting_slide::all()->sortByDesc('created_at')->take('4')->values();
            return response()->json([
                'status' => true,
                'data' => $show
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $create = new Setting_slide;
            if ($request->hasFile('image')) {
                $path = public_path() . '/assets/images/slide';
                $file = $request->file('image');
                $filename = time() . $file->getClientOriginalName();
                $file->move($path, $filename);
                $create->image = $filename;
            }
            $create->save();
            DB::commit();
            return response()->json([
                'data' => $create
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($id){

            $del = Setting_slide::find($id);
            if (!$del)
                throw new NotFoundHttpException('Id not found');
            $del->delete();
            return response()->json([
               'status'=>true,
               'data'=>$del
            ]);


    }

    public function detailStore()
    {
        DB::beginTransaction();
        try {
            $store = Infostore::find(1);
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $store
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateStore(Request $request) {
        DB::beginTransaction();
        try {
            $store = Infostore::find(1);
//            die($store);
            $store->name = $request['name'];
            $store->phone = $request['phone'];
            $store->address = $request['address'];
            if ($request->hasFile('logo')) {
//                $destinationPath = public_path() . '/assets/images/category';
                $file = $request->file('logo');
                $destinationFileName = 'http://sys.yachin.vn/assets/images/logo/'.time().$file->getClientOriginalName();
                // move the file from tmp to the destination path
                $destinationPath = public_path() . '/assets/images/logo';
                $file->move($destinationPath, $destinationFileName);
                $store->logo = $destinationFileName;

            }
            $store->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $store
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }


}
