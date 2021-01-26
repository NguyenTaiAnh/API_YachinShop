<?php

namespace App\Api\V1\Controllers\Web;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
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

    public function detail($id)
    {
        DB::beginTransaction();
        try {
            $data = Category::find($id);
            if (!$data)
                throw new NotFoundHttpException('Id not found');
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

    /*
     * create
     */

    public function create(Request $req)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($req->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'name' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $insert = new Category;
            $insert->name = $req['name'];

            if ($req->hasFile('image')) {
//                $destinationPath = public_path() . '/assets/images/category';
                $file = $req->file('image');
                $destinationFileName =  $file->getClientOriginalName();
                // move the file from tmp to the destination path
                $destinationPath = public_path() . '/assets/images/category';
                $file->move($destinationPath, $destinationFileName);
                $insert->image = $destinationFileName;

                }
            $insert->save();
            DB::commit();
            return response()
                ->json([
                    'status' => true,
                    'data' => $insert
                ]);


        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
     * edit
     * @param id
     */
    public function edit($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
//                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'name' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $edit = Category::find($id);
            $edit->name = $request['name'];
            if ($request->hasFile('image')) {
                $destinationPath = public_path() . '/assets/images/category';
                $file = $request->file('image');
                $destinationFileName = time() . $file->getClientOriginalName();
//                $filename = pathinfo($destinationFileName, PATHINFO_FILENAME);
//                $extension = $request->file('image')->getClientOriginalExtension();

                $file->move($destinationPath, $destinationFileName);
                $edit->image = $destinationFileName;
            }
            $edit->save();
            DB::commit();
            return response()
                ->json([
                    'status' => true,
                    'data' => $edit
                ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function delete($id)
    {
        $del = Category::find($id);
        if (!$del)
            throw new NotFoundHttpException('Id not found');

        $del->delete();
        return response()->json([
            'status' => true,
            'data' => $del
        ]);
    }

}
