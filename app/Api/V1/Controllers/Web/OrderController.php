<?php

namespace App\Api\V1\Controllers\Web;

use App\Addresses_order;
use App\Attributes;
use App\Customer;
use App\Discounts;
use App\Http\Controllers\Controller;
use App\Item;
use App\Mail\MailStatus;
use App\Order;
use App\Order_status;
use App\Product;
use App\Shipping;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Mail;



class OrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin')->except('qrcode', 'checkQR');
    }

    public function list()
    {
        DB::beginTransaction();
        try {
            $order = Order::all();
            $new = Order::where('status', 1)->get();
            $comfirm = Order::where('status', 2)->get();
            $transpost = Order::where('status', 3)->get();
            $done = Order::where('status', 4)->get();
            $cencal = Order::where('status', 5)->get();
            if (!$order)
                throw new NotFoundHttpException('Not data category');

            foreach ($order as $item) {
//                $item->item;
                $item->statusName;
                foreach ($item->item as $value) {
                    foreach ($value->product as $pro) {
                        $pro->image;
                    }
                }
                foreach ($item->address as $addr) {
                    $addr->provinces;
                    $addr->districts;
                    $addr->wards;
                };
                foreach ($item->OrderStatus as $name) {
                    $name->statusName;
                }
                $item->discount;
                $item->ship;
                $item->paytype;
                $data = $order;
            }

            foreach ($new as $item) {
//                $item->item;
                $item->statusName;
                foreach ($item->item as $value) {
                    foreach ($value->product as $pro) {
                        $pro->image;
                    }
                }
                foreach ($item->address as $addr) {
                    $addr->provinces;
                    $addr->districts;
                    $addr->wards;
                };
                foreach ($item->OrderStatus as $name) {
                    $name->statusName;
                }
                $item->discount;
                $item->ship;
                $item->paytype;
                $data = $order;
            }

            foreach ($comfirm as $item) {
//                $item->item;
                $item->statusName;
                foreach ($item->item as $value) {
                    foreach ($value->product as $pro) {
                        $pro->image;
                    }
                }
                foreach ($item->address as $addr) {
                    $addr->provinces;
                    $addr->districts;
                    $addr->wards;
                };
                foreach ($item->OrderStatus as $name) {
                    $name->statusName;
                }
                $item->discount;
                $item->ship;
                $item->paytype;
                $data = $order;
            }


            foreach ($transpost as $item) {
//                $item->item;
                $item->statusName;
                foreach ($item->item as $value) {
                    foreach ($value->product as $pro) {
                        $pro->image;
                    }
                }
                foreach ($item->address as $addr) {
                    $addr->provinces;
                    $addr->districts;
                    $addr->wards;
                };
                foreach ($item->OrderStatus as $name) {
                    $name->statusName;
                }
                $item->discount;
                $item->ship;
                $item->paytype;
                $data = $order;
            }
            foreach ($done as $item) {
//                $item->item;
                $item->statusName;
                foreach ($item->item as $value) {
                    foreach ($value->product as $pro) {
                        $pro->image;
                    }
                }
                foreach ($item->address as $addr) {
                    $addr->provinces;
                    $addr->districts;
                    $addr->wards;
                };
                foreach ($item->OrderStatus as $name) {
                    $name->statusName;
                }
                $item->discount;
                $item->ship;
                $item->paytype;
                $data = $order;
            }

            foreach ($cencal as $item) {
//                $item->item;
                $item->statusName;
                foreach ($item->item as $value) {
                    foreach ($value->product as $pro) {
                        $pro->image;
                    }
                }
                foreach ($item->address as $addr) {
                    $addr->provinces;
                    $addr->districts;
                    $addr->wards;
                };
                foreach ($item->OrderStatus as $name) {
                    $name->statusName;
                }
                $item->discount;
                $item->ship;
                $item->paytype;
                $data = $order;
            }
            DB::commit();
            return response()
                ->json([
                    'status' => true,
                    'data' => $data->sortByDesc('created_at')->values(),
                    'new' => $new,
                    'comfirm' => $comfirm,
                    'transpost' => $transpost,
                    'done' => $done,
                    'cencal' => $cencal
                ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $ship = Shipping::find($request['shipping_id']);
            $product = (array)$request['product'];

            $temps = (float)count($product);

            $sum = 0;
            for ($i = 0; $i < $temps; $i++) {
                $attribute = Attributes::where('product_id', $product[$i]['product_id'])
                    ->where('size', $product[$i]['size'])
                    ->where('color', $product[$i]['color'])
                    ->first();
                $pro = Product::find($product[$i]['product_id']);
                if (!$attribute) {
                    if ($pro['price_sale']) {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price_sale'];
                    } else {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price'];
                    }
                } else {
                    if ($pro['price_sale']) {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price_sale'];
                    } else {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$attribute['price'];
                    }
                }
                $sum = (float)$sum + (float)$priceTemp;
            }
            $percent = Discounts::where('code', $request['discount_id'])->first();
            if (!$ship) {
                $ship = 0;
            } else {
                $ship = $ship->cost;
            }
            //test code
            if ($percent) {
                //test date time
                if ($percent['time_start'] < Carbon::now() && Carbon::now() < $percent['time_end']) {
                    $sale = ((float)$percent['percent'] * $sum) / 100;
                    $tota = $sum - $sale;
                    $total = $tota + $ship;
                } else {
                    $sale = 0;
                    $total = $sum + $ship;
                }
            }

            if (!$percent) {
                $total = (float)$sum + $ship;
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'province' => 'required',
                'district' => 'required',
                'village' => 'required'

            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            // order
            $order = new Order();
            $order->code = time();
            $order->discount_id = $request['discount_id'];
            $order->total = $total;
            $order->paystatus = 1;
            $order->status = 1;
            $order->pay_type_id = $request['paytype_id'];
            $order->shipping_id = $request['shipping_id'];
            $order->save();
//            $items = (object)$request['item'];
            $items = (array)$request['product'];
            foreach ($items as $item) {
                $value = new Item;
                $value->product_id = $item['product_id'];
                $value->amount = $item['amount'];
                $value->size = $item['size'];
                $value->color = $item['color'];
                $value->price = $item['price'];
                $value->price_sale = $item['price_sale'];
                $value->temp = $item['temp'];
                $value->order_id = $order->id;
                $value->save();
            }

            $add = new Addresses_order();
            $add->province = $request['province'];
            $add->district = $request['district'];
            $add->ward = $request['ward'];
            $add->village = $request['village'];
            $add->phone = $request['phone'];
            $add->receiver_email = $request['email'];
            $add->receiver_name = $request['name'];
            $add->order_id = $order->id;
            $add->save();


            $status = new Order_status;
            $status->name = 1;
            $status->order_id = $order->id;
            $status->save();
            $order_id = $order->id;
            $data = $this->_getValueCreate($order_id);

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /*
     * get value create
     */

    private function _getValueCreate($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::where('id', $id)->get();
            if (!$id)
                throw new NotFoundHttpException('order_id not found', 404);
            foreach ($order as $item) {
                $item->item;
                $data = $order;
            }
            DB::commit();
            return $data;


        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function edit($id, Request $request)
    {
        DB::beginTransaction();
        try {
            //          count money
            $items = (array)$request['item'];
            $temp = (float)count($items);
//            die((float)count($temp));
            $sum = 0;
            for ($i = 0; $i < $temp; $i++) {
                //discount
                if ($items[$i]['price_sale']) {
                    $result = (float)$items[$i]['price_sale'] * (float)$items[$i]['amount'];
                } else {
                    $result = (float)$items[$i]['price'] * (float)$items[$i]['amount'];
                }
                $sum = $sum + $result;
            }
//            die($sum);
            $percent = Discounts::where('code', $request['discount_id'])->first();

            //test code
            if ($percent) {
                //test date time
                if ($percent['time_start'] < Carbon::now() && Carbon::now() < $percent['time_end']) {
                    $sale = ((float)$percent['percent'] * $sum) / 100;
                    $total = $sum - $sale;
                } else {
                    $total = $sum;
                }
            }
            $validator = Validator::make($request->all(), [
                'discount_id' => 'required',
                'address_id' => 'required',
                'paystatus' => 'required',
                'pay_type_id' => 'required',
                'shipping_id' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $edit = Order::find($id);
            $edit->code = time();
            $edit->discount_id = $request['discount_id'];
            $edit->total = $total;
            $edit->address_id = $request['address_id'];
            $edit->paystatus = $request['paystatus'];
            $edit->pay_type_id = $request['pay_type_id'];
            $edit->shipping_id = $request['shipping_id'];

            $edit->save();

            $item_id = Item::where('order_id', $id)->get();
            //temp item_id
            $temp = (float)($item_id->count());

            $items = (object)$request['item'];
            foreach ($items as $item) {
                $value = new Item;
                $value->product_id = $item['product_id'];
                $value->amount = $item['amount'];
                $value->price = $item['price'];
                $value->price_sale = $item['price_sale'];
                $value->order_id = $edit->id;

                $value->save();
            }
            for ($i = 0; $i < (float)$temp; $i++) {
//                if($item_id[$i]->id == $request['item'][$i][$item_id[$i]->id]){
                $val = Item::find($item_id[$i]->id);
                $val->product_id = $request['item'][$i]['product_id'];
                $val->amount = $request['item'][$i]['amount'];
                $val->price = $request['item'][$i]['price'];
                $val->price_sale = $request['item'][$i]['price_sale'];
                $val->delete();
//                }
            }
            $data = $this->_getValueCreate($id);

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $data,
//                'tempSum' => $sum

            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {


        $del = Order::find($id);
        if (!$del)
            throw new NotFoundHttpException('Id not found');
        $del->delete();

        $item = Item::where('order_id', $id);
        $item->delete();

        $status = Order_status::where('order_id', $id);
        $status->delete();

        return response()->json([
            'order' => $del,
            'item' => $item->get(),
            'status' => $status->get()
        ]);
    }

    public function detail($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::where('id', $id)->get();
            $items = Item::where('order_id', $id)->get();
            $count = (float)count($items);

            $temp = 0;
            for ($i = 0; $i < $count; $i++) {
                $temp = $temp + $items[$i]['temp'];
            }
            if (!$order) {
                throw new NotFoundHttpException('Not data category');
            }
            foreach ($order as $item) {
                foreach ($item->item as $value) {
                    foreach ($value->product as $pro) {
                        $pro->image;
                    }
                }
                foreach ($item->address as $addr) {
                    $addr->provinces;
                    $addr->districts;
                    $addr->wards;
                };
                $item->discount;
                foreach ($item->OrderStatus as $name) {
                    $name->statusName;
                }
                $item->ship;
                $item->paytype;
                $item->statusName;
                $data = $order;
            }
            DB::commit();
            return response()
                ->json([
                    'status' => true,
                    'data' => $data,
                    'temp' => $temp
                ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function compute(Request $request)
    {
        DB::beginTransaction();
        try {
            $ship = Shipping::find($request['shipping_id']);
            $product = (array)$request['product'];

            $temps = (float)count($product);

//            die($temps);
            $sum = 0;
            for ($i = 0; $i < $temps; $i++) {
                $attribute = Attributes::where('product_id', $product[$i]['product_id'])
                    ->where('size', $product[$i]['size'])
                    ->where('color', $product[$i]['color'])
                    ->first();
                $pro = Product::find($product[$i]['product_id']);
                if (!$attribute) {
                    if ($pro['price_sale']) {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price_sale'];
                    } else {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price'];
                    }
                } else {
                    if ($pro['price_sale']) {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price_sale'];
                    } else {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$attribute['price'];
                    }
                }
                $sum = (float)$sum + (float)$priceTemp;
            }
            $percent = Discounts::where('code', $request['discount_id'])->first();
            if (!$ship) {
                $ship = 0;
            } else {
                $ship = $ship->cost;
            }
            //test code
            if ($percent) {
                //test date time
                if ($percent['time_start'] < Carbon::now() && Carbon::now() < $percent['time_end']) {
                    $sale = ((float)$percent['percent'] * $sum) / 100;
                    $tota = $sum - $sale;
                    $total = $tota + $ship;
                } else {
                    $sale = 0;
                    $total = $sum + $ship;
                }
            }

            if (!$percent) {
                $total = (float)$sum + $ship;
                return response()->json([
                    'sum' => $sum,
                    'ship' => $ship,
                    'sale' => 0,
                    'total' => $total
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'sum' => $sum,
                'ship' => $ship,
                'sale' => $sale,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function editStatus($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::find($id);
            $order->status = $request['name'];
            $order->save();

            $status = new Order_status;
            $status->name = $request['name'];
            $status->order_id = $id;
            $status->save();
            $user = User::where('id',$order->user_id)
                ->first();

            $this->email = 'abc';
            $this->id =$id;

            if($order->user_id) {
                Mail::to($user['email'])->send(new MailStatus($this->email,$this->id));
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $status,
                'order' => $order
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function statusPayment($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::find($id);
            $order->pay_type_id = 2;
            $order->save();
            DB::commit();
            return response()->json([
                'status' => true,
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function checkQR(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::where('code', $request['code'])->first();

            if (!$order)
                throw new NotFoundHttpException('Not data category');
            DB::commit();
            return response()
                ->json([
                    'status' => true,
                    'data' => $order

                ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }


}
