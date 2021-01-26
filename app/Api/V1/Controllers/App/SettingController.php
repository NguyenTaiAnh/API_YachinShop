<?php

namespace App\Api\V1\Controllers\App;

use App\Http\Controllers\Controller;

use App\Setting_slide;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    //
//    public function __construct()
//    {
//        $this->middleware('customer');
//    }
    public function list()
    {
        DB::beginTransaction();
        try {
            $show = Setting_slide::all()->sortByDesc('created_at')->take('3')->values();
            return response()->json([
               'status'=>true,
               'data'=>$show
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }

    public function create(Request $request){
        DB::beginTransaction();
        try{
            $create = new Setting_slide;
            if ($request->hasFile('image')){
                $path = public_path() . '/assets/images/slide';
                $file = $request->file('image');
                $filename = time() . $file->getClientOriginalName();
                $file->move($path,$filename);
                $create->image = $filename;
            }
            //thieu
            $create->save();
            DB::commit();
            return response()->json([
               'data'=>$create
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }
}
