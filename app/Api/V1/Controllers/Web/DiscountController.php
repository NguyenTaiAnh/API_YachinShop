<?php

namespace App\Api\V1\Controllers\Web;

use App\Device_token;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Discounts;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DiscountController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('admin');
    }


    public function list()
    {
        DB::beginTransaction();
        try {
            $discount = Discounts::all();
            $temp = (float)$discount->count();

            if (!$discount)
                throw new NotFoundHttpException('Data is not available');
            for ($i = 0; $i < $temp ; $i ++){
                if ($discount[$i]['time_start'] < Carbon::now() && Carbon::now() < $discount[$i]['time_end'])
                {
                    $status = Discounts::find($discount[$i]->id);
                    $status->status = "Hoạt động";
                    $status->save();
                }
                else {
                    $status = Discounts::find($discount[$i]->id);
                    $status->status = "Hết hạn";
                    $status->save();
                }

                $discount = Discounts::all();
            }
//            foreach ($discount as $val){
//                $val->check();
//                $data = $discount;
//            }
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $discount
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function detail($id)
    {
        DB::beginTransaction();
        try {
            $data = Discounts::find($id);
            if (!$data)
                throw new NotFoundHttpException('Id not found');
            DB::commit();
            return response()->json([
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
                'code' => 'required',
                'percent' => 'required',
                'time_start' => 'required',
                'time_end' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $discount = new Discounts();
            $discount->code = $request['code'];
            $discount->percent = $request['percent'];
            $discount->time_start = $request['time_start'];
            $discount->time_end = $request['time_end'];
            $discount->save();

            $this->sendNoti($request['code'], $request['percent'], $request['time_start'], $request['time_end']);

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $discount
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public function edit($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'percent' => 'required',
                'time_start' => 'required',
                'time_end' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $discount = Discounts::find($id);
            if (!$discount)
                throw new NotFoundHttpException('ID not found');
            $discount->code = $request['code'];
            $discount->percent = $request['percent'];
            $discount->time_start = $request['time_start'];
            $discount->time_end = $request['time_end'];
            $discount->save();
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $discount
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public function delete($id)
    {
        $del = Discounts::find($id);
        if (!$del)
            throw new NotFoundHttpException('Id not found');

        $del->delete();
        return response()->json([
            'status' => true,
            'data' => $del
        ]);
    }

    public function check($id)
    {
        $discount = Discounts::find($id);

        if (!$discount)
            throw new NotFoundHttpException("ID does not exist");

        if ($discount['time_start'] < Carbon::now() && Carbon::now() < $discount['time_end']) {
            return response()->json([
                'status' => true,
                'data' => $discount
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => $discount
            ]);
        }
    }


    private function sendNoti($body, $percent, $timestart, $timeend) {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('Hot SALE!');
        $notificationBuilder->setBody('Nhập ngay mã giảm giá "'. $body . '" .Giảm giá lên đến '. $percent . '% cho tất cả các đơn hàng từ ngày '. $timestart . ' đến ngày ' . $timeend)
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
