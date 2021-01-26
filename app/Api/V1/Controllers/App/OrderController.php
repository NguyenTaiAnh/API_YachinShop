<?php

namespace App\Api\V1\Controllers\App;

use App\Addresses;
use App\Addresses_order;
use App\Attributes;
use App\Discounts;
use App\Http\Controllers\Controller;

use App\Item;
use App\Notifi;
use App\Order;
use App\Order_status;
use App\Paytypes;
use App\Product;
use App\Shipping;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('customer')->except('check');
    }
    public function list(){
        DB::beginTransaction();
        try {
            $order = Order::where('user_id', auth()->user()->id)->orderBy('created_at','desc')->get();
            $count = (float)count($order);
            if (!$order)
                throw new NotFoundHttpException('Not data category');

            if ($count==0) {
                $data = [];
            }
            else {
                foreach ($order as $item){
                    $item->item;
                    $item->statusName;
                    foreach ($item->address as $addr) {
                        $addr->provinces;
                        $addr->districts;
                        $addr->wards;
                    };
                    $item->discount;
                    foreach ($item->OrderStatus as $name){
                        $name->statusName;
                    }
                    $item->ship;
                    $item->paytype;

                    $data = $order;
                }
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
            $ship = Shipping::find($request['shipping_id']);
            $product = (array)$request['product'];

            $temps = (float)count($product);

            $sum = 0;
            for($i = 0; $i < $temps ; $i++){
                $attribute= Attributes::where('product_id',$product[$i]['productId'])
                    ->where('size',$product[$i]['size'])
                    ->where('color',$product[$i]['color'])
                    ->first();
                $pro = Product::find($product[$i]['productId']);
                if (!$attribute){
                    if ($pro['price_sale']){
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price_sale'];
                    }
                    else {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price'];
                    }
                }
                else {
                    if ($pro['price_sale']){
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price_sale'];
                    }
                    else {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$attribute['price'];
                    }
                }
                $sum = (float)$sum + (float)$priceTemp;
            }
            $percent = Discounts::where('code', $request['discount_id'])->first();
            if (!$ship){
                $ship = 0;
            }
            else{
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
                    $total = $sum+$ship;
                }
            }

            if (!$percent){
                $total = (float)$sum + $ship;
            }
            // order

            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'paystatus' => 'required',
                'paytype_id' => 'required',
                'shipping_id' => 'required'

            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }


            $order = new Order();
            $order->code = $request['code'];
            $order->discount_id = $request['discount_id'];
            $order->total = $total;
            $order->address_id = $request['address_id'];
            $order->paystatus = $request['paystatus'];
            $order->status = $request['status'];
            $order->user_id = auth()->user()->id;
            $order->pay_type_id = $request['paytype_id'];
            $order->shipping_id = $request['shipping_id'];
            $order->save();

//            addrdess
            $addr = Addresses::where('id', $request['address_id'])->first();
            $add = new Addresses_order();
            $add->province = $addr['province'];
            $add->district = $addr['district'];
            $add->ward = $addr['ward'];
            $add->village = $addr['village'];
            $add->phone = $addr['phone'];
            $add->receiver_email = $addr['receiver_email'];
            $add->receiver_name = $addr['receiver_name'];
            $add->order_id = $order->id;
            $add->save();

//            $items = (object)$request['item'];
            $items = (array)$request['product'];
            foreach ($items as $item) {
                $value = new Item;
                $value->product_id = $item['productId'];
                $value->amount = $item['amount'];
                $value->size = $item['size'];
                $value->color = $item['color'];
                $value->price = $item['price'];
                $value->price_sale = $item['price_sale'];
                $value->temp = $item['temp'];
                $value->order_id = $order->id;
                $value->save();
            }

            $status = new Order_status;
            $status->name = $request['status'];
            $status->order_id = $order->id;
            $status->save();
            $order_id = $order->id;
            $data = $this->_getValueCreate($order_id);

            $title = "Đơn hàng mới: ". $request['code'];
            $content = "Đơn hàng mới: ". $request['code'];
            $url = "order-detail/".$order->id;
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
                'data' => $data

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
            for ($i = 0; $i < $count ; $i ++){
                $temp = $temp + $items[$i]['temp'];
            }

            if ($order[0]['discount']){
                if($order[0]['discount']['time_start'] < Carbon::now() && Carbon::now() < $order[0]['discount']['time_end']  ){
                    $discount =((float)$temp*(float)$order[0]['discount']['percent']) /100;
                }
                else{
                    $discount = 0;
                }
            }
            else{
                $discount = 0;
            }

            if (!$order) {
                throw new NotFoundHttpException('Not data category');
            }
            foreach ($order as $item){
                foreach ($item->item as $value){
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
                foreach ($item->OrderStatus as $name){
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
                    'temp' => $temp,
                    'discount' => $discount
                ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function compute(Request $request){
        DB::beginTransaction();
        try{
            $ship = Shipping::find($request['shipping_id']);
            $product = (array)$request['product'];

            $temps = (float)count($product);

//            die($temps);
            $sum = 0;
            for($i = 0; $i < $temps ; $i++){
                $attribute= Attributes::where('product_id',$product[$i]['product_id'])
                    ->where('size',$product[$i]['size'])
                    ->where('color',$product[$i]['color'])
                    ->first();
                $pro = Product::find($product[$i]['product_id']);
                if (!$attribute){
                    if ($pro['price_sale']){
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price_sale'];
                    }
                    else {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price'];
                    }
                }
                else {
                    if ($pro['price_sale']){
                        $priceTemp = (float)$product[$i]['amount'] * (float)$pro['price_sale'];
                    }
                    else {
                        $priceTemp = (float)$product[$i]['amount'] * (float)$attribute['price'];
                    }
                }
                $sum = (float)$sum + (float)$priceTemp;
            }
            $percent = Discounts::where('code', $request['discount_id'])->first();
            if (!$ship){
                $ship = 0;
            }
            else{
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
                    $total = $sum+$ship;
                }
            }

            if (!$percent){
                $total = (float)$sum + $ship;
                return response()->json([
                    'sum'=>$sum,
                    'ship'=>$ship,
                    'sale'=>0,
                    'total'=>$total
                ]);
            }

            DB::commit();
            return response()->json([
                'status'=>true,
                'sum'=>$sum,
                'ship'=>$ship,
                'sale'=>$sale,
                'total'=>$total
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }


    public function check(Request $request){
        DB::beginTransaction();
        try{
            $percent = Discounts::where('code',$request['code'])->first();
            if ($percent['time_start'] < Carbon::now() && Carbon::now() < $percent['time_end']) {
                return response()->json([
                    'status' => true,
                    'data'=>$percent
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'data'=>$percent
                ]);
            }

        }catch (\Exception $e){
            throw $e;
        }
    }

    public function checkQR (Request $request) {
        DB::beginTransaction();
        try{
            $order  = Order::where('code', $request['code'])->first();
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

    public function statusPayment(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $order = Order::find($id);
            $order->paystatus = $request['paystatus'];
            $order->status = $request['status'];
            $order->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $order
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function listPaytype()
    {
        DB::beginTransaction();
        try {
            $pay = Paytypes::all();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $pay
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function createNotifi($title, $content, $url) {

        $noti = new Notifi();
        $noti->content = $content;
        $noti->url = $url;
        $noti->status = 0;
        $noti->save();

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
        $tokens = "dskkk5d6HDs:APA91bGSrNPsgLfTrdkcuQcftSdkD8FABj1fKg20uJlJidJsIdK2jOqY8_npoEZRNOEa2lEA_VIZ8NgDpNKHLTeXdQHgaXeWyoNZMQTL9moAuN3RHqp97ZNH3EoC6SoT45DPJTjndtQe";

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

    }
}
