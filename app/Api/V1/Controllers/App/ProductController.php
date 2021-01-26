<?php

namespace App\Api\V1\Controllers\App;

use App\Attributes;
use App\Http\Controllers\Controller;
use App\Product;
use App\Shipping;
use App\Wishlist;
use Auth;
use filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{

    public function category($id)
    {
        DB::beginTransaction();
        try {

            $product = Product::where('category_id', $id)->get();
            $count = (float)count($product);
            if (!$product) {
                throw new NotFoundHttpException('Not data category');
            }

            if ($count == 0) {
                $data = [];
            } else {
                foreach ($product as $pro) {
                    $pro->attributes;
                    $pro->image;
                    $data = $product;
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

    private function _getValueCreate($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::where('id', $id)->get();
            if (!$id)
                throw new NotFoundHttpException('not found', 404);
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

    private function _getValue()
    {
        DB::beginTransaction();
        try {
            $pro = Product::all();
            foreach ($pro as $value) {
                $value->attributes;
                $value->image;
                $data = $pro;
            }

            DB::commit();
            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);
            $color = Attributes::where('product_id', $id)->get();
            $size = Attributes::where('product_id', $id)->get();
//            $order_id = $order['id'];
            if (!$product) {
                throw new NotFoundHttpException('Not data product');
            }
            $data = $this->_getValueCreate($id);
            DB::commit();
            return response()
                ->json([
                    'status' => true,
                    'data' => $data,
                    'size' => $size,
                    'color' => $color
                ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function new()
    {
        DB::beginTransaction();
        try {
            $new = Product::all()->sortByDesc('created_at')->take('5')->values();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $new
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function sale(Request $request)
    {
        DB::beginTransaction();
        try {
//            $product = DB::table('products')->where('price_sale','!=',null)->get();
            $product = Product::where('price_sale', '!=', 0)->get();
            foreach ($product as $pro) {
                $pro->attributes;
                $pro->image;
                $data = $product;
            }
//            die($product);
            if ($product) {
                return response()->json([
                    'status' => true,
                    'data' => $data
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function all()
    {
        DB::beginTransaction();
        try {
            $product = Product::all();
            $data = $this->_getValue();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public function increase()
    {
        DB::beginTransaction();
        try {
            $pro = Product::all()->sortBy('price');
            foreach ($pro as $product) {
                $product->attributes;
                $product->image;
                $data = $pro;
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

    public function search(Request $request)
    {
        DB::beginTransaction();
        try {
            $key = $request['key'];
            $product = Product::where('name', 'like', "%$key%")->get();
            foreach ($product as $item) {
                $item->attributes;
                $item->image;
                $item->category;
                $data = $product;
            }
            if ($key) {
//                return $product;
                return response()->json([
                    'status' => true,
                    'data' => $data
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function new_old(Request $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::where('name', '!=', null)->orderBy('created_at', 'desc')
                ->get();
            foreach ($product as $pro) {
                $pro->attributes;
                $pro->image;
                $data = $product;
            }
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function color($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $product = Attributes::where('product_id', $id)
                ->where('size', $request['size'])
                ->get();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function size($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $product = Attributes::where('product_id', $id)->where('color', $request['color'])
                ->get();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $product
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function reduction()
    {
        DB::beginTransaction();
        try {
//            $product = Product::where('price','!=',null)->orderBy('price','DESC')->get();
            $product = Product::all()->sortByDesc('price')->values();
//            die($product);
            foreach ($product as $pro) {
                $pro->attributes;
                $pro->image;
                $data = $product;
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

    public function filter(Request $request)
    {
        DB::beginTransaction();
        try {
            $product = Product::where('price', '>=', (float)$request['price1'])
                ->where('price', '<=', (float)$request['price2'])->get();

            foreach ($product as $pro) {
                $pro->attributes;
                $pro->image;
                $data = $product;
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

    //    price attibute
    public function priceAtt($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $product = Attributes::where('product_id', $id)
                ->where('color', $request['color'])
                ->where('size', $request['size'])
                ->get();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $product
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    // get status shippng
    public function getShiping()
    {
        DB::beginTransaction();
        try {
            $data = Shipping::all();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getShipingDetail($id)
    {
        DB::beginTransaction();
        try {
            $data = Shipping::find($id);
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function createwish(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, array(
                'productid' => 'required',
            ));
            $status = Wishlist::where('user_id', auth()->user()->id)
                ->where('product_id', $request['productid'])
                ->first();
            DB::commit();

            if ($status['user_id'] == auth()->user()->id && $status['product_id'] == $request['productid']) {
                return response()->json([
                    'status' => 1,
                    'message' => 'This item is already in your wishlist!'
                ]);
            } else {
                $wishlist = new Wishlist;
                $wishlist->user_id = auth()->user()->id;
                $wishlist->product_id = $request['productid'];
                $wishlist->status = 1;
                $wishlist->save();
                return response()->json([
                    'status' => 2,
                    'data' => $wishlist
                ]);
            }


        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detailWish($id)
    {
        DB::beginTransaction();
        try {
            $wishlist = Wishlist::where('user_id', auth()->user()->id)
                ->where('product_id', $id)
                ->first();
            DB::commit();
            return response()->json([
                'status' => 2,
                'data' => $wishlist
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

    }


    public function deletewish($id)
    {
        $del = Wishlist::find($id);
        if (!$del)
            throw new NotFoundHttpException('Id not found');
        $del->delete();
        return response()->json([
            'status' => true,
            'data' => $del
        ]);
    }

    public function getwish()
    {
        DB::beginTransaction();
        try {
            $wish = Wishlist::where('user_id', auth()->user()->id)->get();
            foreach ($wish as $val) {
                foreach ($val->product as $pro) {
                    $pro->image;
                }
                $data = $wish;
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
}
