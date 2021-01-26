<?php

namespace App\Api\V1\Controllers\Web;

use App\Attributes;
use App\Device_token;
use App\Http\Controllers\Controller;
use App\Image;
use App\Order;
use App\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class ProductController extends Controller
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
            $product = Product::all();
            $count = $product->count();
            if (!$product) {
                throw new NotFoundHttpException('Not data prodouct', 404);
            }
            if ($count != 0) {
                foreach ($product as $item) {
                    $item->attributes;
                    $item->image;
                    $item->category;
                    $data = $product;
                }
            } else {
                $data = [];
            }
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
            $product = Product::where('id', $id)->get();
            if (!$product) {
                throw new NotFoundHttpException('Id not found');
            }
            foreach ($product as $item) {
                $item->attributes;
                $item->image;
                $item->category;
                $data = $product;
            }
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
                'description' => 'required',
                'price' => 'required',
//                'category_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $user = auth()->user();
            $create = new Product;
            $create->name = $request['name'];
            $create->price = $request['price'];
            $create->price_sale = $request['price_sale'];
            $create->description = $request['description'];
            $create->category_id = $request['category_id'];
            $create->user_id = $user->id;
            $create->save();
            $product_id = $create->id;
            //attribute
            $attribues = (object)$request['attribute'];
            if ($attribues) {
                foreach ($attribues as $attribue) {
                    $val = new Attributes;
                    $val->size = $attribue['size'];
                    $val->color = $attribue['color'];
                    $val->price = $attribue['price'];
                    $val->amount = $attribue['amount'];
                    $val->product_id = $product_id;
                    $val->save();
                }
            }

            //image
            if ($request->file('image')) {
                $destinationPath = public_path() . '/assets/images/product';
                foreach ($request->file('image') as $fileKey => $fileObject) {
                    // make sure each file is valid
                    $truckImages = new Image;
                    if ($fileObject->isValid()) {
                        $destinationFileName = time() . $fileObject->getClientOriginalName();

                        $fileObject->move($destinationPath, $destinationFileName);
                        $truckImages->product_id = $product_id;
                        $truckImages->path = 'https://sys.yachin.vn/assets/images/product/' . $destinationFileName;
                    }
                    $truckImages->save();
                }

            }

            $data = $this->_getValueCreate($product_id);

            $title = $request['name'];
            $content = 'Sản phẩm mới có giá:  '. $request['price'] . '.' . 'Giá khuyễn mãi: ' .$request['price_sale']  .'. Vào app mua sắm để được hưởng những ưu đãi của shop.';
            $url = "product/".$product_id;
            $this->createNotifi($title, $content, $url);
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function edit($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $edit = Product::find($id);
            $edit->name = $request['name'];
            $edit->price = $request['price'];
            $edit->description = $request['description'];
            $edit->price = $request['price'];
            $edit->price_sale = $request['price_sale'];
            $edit->save();
            $attribues = (object)$request['attribute'];
            if ($attribues) {
                $attri_id = Attributes::where('product_id', $id);
                $attri_id->delete();
                foreach ($attribues as $attribue) {
                    $val = new Attributes;
                    $val->size = $attribue['size'];
                    $val->color = $attribue['color'];
                    $val->price = $attribue['price'];
                    $val->amount = $attribue['amount'];
                    $val->product_id = $id;
                    $val->save();
                }

            }

            //image
            $destinationPath = public_path() . '/assets/images/product';

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $fileKey => $fileObject) {
                    // make sure each file is valid
                    $truckImages = new Image;
                    if ($fileObject->isValid()) {
                        $destinationFileName = time() . $fileObject->getClientOriginalName();
                        $fileObject->move($destinationPath, $destinationFileName);
                        $truckImages->product_id = $id;
                        $truckImages->path = 'https://sys.yachin.vn/assets/images/product/' . $destinationFileName;
                    }
                    $truckImages->save();
                }

            }


            $data = $this->_getValueCreate($id);
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function edit_image($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $edit = Product::find($id);

            $product_id = $edit->id;

            $destinationPath = public_path() . '/assets/images/product';
            foreach ($request->file('image') as $fileKey => $fileObject) {
                // make sure each file is valid
                $truckImages = new Image;
                if ($fileObject->isValid()) {
                    $destinationFileName = time() . $fileObject->getClientOriginalName();
                    $fileObject->move($destinationPath, $destinationFileName);
                    $truckImages->product_id = $product_id;
                    $truckImages->path = $destinationFileName;
                }
                $truckImages->save();
            }

//                dd('asdasd');

            $data = $this->_getValueCreate($id);
            DB::commit();
//            die($value);
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public function delete($id, Request $request)
    {
        $del = Product::find($id);
        if (!$del) {
            throw new NotFoundHttpException('Id not found');
        }

        $attri = Attributes::where('product_id', $id);
        $attri->delete();
        //image
        $image = Image::where('product_id', $id);
        $image->delete();

        $del->delete();
        return response()->json([
            'status' => true,
            'data' => $del
        ]);
    }

    private function _getValueCreate($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::where('id', $id)->get();
            if (!$id) {
                throw new NotFoundHttpException('not found', 404);
            }

            foreach ($product as $value) {
                $value->attributes;
                $value->image;
                $data = $value;
            }
            DB::commit();
            return $data;


        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function attributes($id)
    {
        DB::beginTransaction();
        try {
            $data = Attributes::where('product_id', $id)->get();
            if (!$data) {
                throw new NotFoundHttpException('Id not found');
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

    public function size($id, $size)
    {
        DB::beginTransaction();
        try {
            $data = Attributes::where('product_id', $id)
                ->where('size', $size)
                ->get();
            if (!$data) {
                throw new NotFoundHttpException('Size not found');
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

    public function price($id, Request $request)
    {
        DB::beginTransaction();
        try {
            if (!$id) {
                throw new NotFoundHttpException('Id not found');
            }
            $product = Product::find($id);
            $attribute = Attributes::where('product_id', $id)
                ->where('size', $request['size'])
                ->where('color', $request['color'])
                ->first();
            DB::commit();
            return response()->json([
                'status' => true,
                'product' => $product,
                'attributes' => $attribute
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete_image($id)
    {
        $image = Image::find($id);
        if (!$image) {
            throw new NotFoundHttpException('Id not found');
        }
        $image->delete();
        return response()->json([
            'status' => true,
            'data' => $image
        ]);
    }


    public function totalproduct(Request $request)
    {
        DB::beginTransaction();
        try {
            $total = 0;
            $order = Order::where('status', 4)->get();
            foreach ($order as $value) {
                foreach ($value->item as $product) {
                    if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
                        error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
                    }
                    $count = (float)count($product);
                    for ($i = 0; $i < $count; $i++) {
                        $count_it = (float)count($product->amount);
                        for ($i = 0; $i < $count_it; $i++) {
                            $total += (float)$product->amount;
                        }
                    }
                }
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $total

            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function uploadImage($productId, Request $request) {
        DB::beginTransaction();
        try {
            if ($request->file('image')) {
                $destinationPath = public_path() . '/assets/images/product';
                foreach ($request->file('image') as $fileKey => $fileObject) {
                    // make sure each file is valid
                    $truckImages = new Image;
                    if ($fileObject->isValid()) {
                        $destinationFileName = time() . $fileObject->getClientOriginalName();

                        $fileObject->move($destinationPath, $destinationFileName);
                        $truckImages->product_id = $productId;
                        $truckImages->path = 'https://sys.yachin.vn/assets/images/product/' . $destinationFileName;
                    }
                    $truckImages->save();
                }

            }

        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function createNotifi($title, $content, $url) {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($content)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $tokens = Device_token::pluck('token')->toArray();
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

    }

}
