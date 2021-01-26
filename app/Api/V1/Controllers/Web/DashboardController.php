<?php

namespace App\Api\V1\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Order;
use App\Order_status;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
    }
//======================================================================================================================
    //day
    public function order_day(Request $request)
    {
        DB::beginTransaction();
        try {
//            $time = Carbon::now()->toDateString();
            $done = Order::whereDate('created_at', Carbon::today())->where('status', 4)->count();
            $new = Order::whereDate('created_at', Carbon::today())->where('status', 1)->count();
            $confim = Order::whereDate('created_at', Carbon::today())->where('status', 2)->count();
            $vc = Order::whereDate('created_at', Carbon::today())->where('status', 3)->count();
            $cancel = Order::whereDate('created_at', Carbon::today())->where('status', 5)->count();
            $total = $done + $new + $cancel + $confim + $vc;
            return response()->json([
                'status' => true,
                'order_new' => $new,
                'order_confim' => $confim,
                'order_transport' => $vc,
                'order_done' => $done,
                'order_cancel' => $cancel,
                'total_day' => $total
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //week
    public function order_week()
    {
//        Data::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();

        $new = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 1)->count();
        $confim = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 2)->count();
        $vc = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 3)->count();
        $done = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 4)->count();
        $cancel = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 5)->count();
        $total = $done + $new + $cancel + $confim + $vc;

        return response()->json([
            'status' => true,
            'order_new' => $new,
            'order_confim' => $confim,
            'order_transport' => $vc,
            'order_done' => $done,
            'order_cancel' => $cancel,
            'total_week' => $total
        ]);
    }

    //month
    public function order_month()
    {
        DB::beginTransaction();
        try {
            $new = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 1)->count();
            $confim = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 2)->count();
            $vc = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 3)->count();
            $done = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 4)->count();
            $cancel = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 5)->count();
            $total = $done + $new + $cancel + $confim + $vc;

            DB::commit();
            return response()->json([
                'status' => true,
                'order_new' => $new,
                'order_confim' => $confim,
                'order_transport' => $vc,
                'order_done' => $done,
                'order_cancel' => $cancel,
                'total_month' => $total
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //year
    public function order_year()
    {
        DB::beginTransaction();
        try {
            $order = Order::select(DB::raw('YEAR(created_at) as year'))->groupBy('year')->get()->keyBy('year');
            if ($order) {
                $new = Order::where('status', 1)->count();
                $confim = Order::where('status', 2)->count();
                $vc = Order::where('status', 3)->count();
                $done = Order::where('status', 4)->count();
                $cancel = Order::where('status', 5)->count();
                $total = $done + $new + $cancel + $confim + $vc;

            }
            DB::commit();
            return response()->json([
                'status' => true,
                'order_new' => $new,
                'order_confim' => $confim,
                'order_transport' => $vc,
                'order_done' => $done,
                'order_cancel' => $cancel,
                'total_year' => $total
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

//======================================================================================================================
    //day
    public function total_day(Request $request)
    {
        DB::beginTransaction();
        try {

            $total_new = 0;
            $total_cancel = 0;
            $total_done = 0;
            $total_confim = 0;
            $total_vc = 0;
            $new = Order::whereDate('created_at', Carbon::today())->where('status', 1)->get('total');
//            die(var_dump($new));
            $count = (float)count($new);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_new = $total_new + (float)$new[$i]['total'];
            }
            $confim = Order::whereDate('created_at', Carbon::today())->where('status', 2)->get('total');
            $count = (float)count($confim);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_confim = $total_confim + (float)$confim[$i]['total'];
            }
            $vc = Order::whereDate('created_at', Carbon::today())->where('status', 3)->get('total');
            $count = (float)count($vc);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_vc = $total_vc + (float)$vc[$i]['total'];
            }
            $done = Order::whereDate('created_at', Carbon::today())->where('status', 4)->get('total');
//            die($done);
            $count = (float)count($done);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_done = $total_done + (float)$done[$i]['total'];
            }
            $cancel = Order::whereDate('created_at', Carbon::today())->where('status', 5)->get('total');
            $count = (float)count($cancel);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_cancel = $total_cancel + (float)$cancel[$i]['total'];
            }
            $total = $total_new + $total_cancel + $total_done + $total_confim + $total_vc;

            DB::commit();
            return response()->json([
                'status' => true,
                'total_new' => $total_new,
                'total_confim' => $total_confim,
                'total_vc' => $total_vc,
                'total_done' => $total_done,
                'total_cancel' => $total_cancel,
                'total_day' => $total
            ]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function total_week()
    {
        DB::beginTransaction();
        try {
            $total_new = 0;
            $total_cancel = 0;
            $total_done = 0;
            $total_confim = 0;
            $total_vc = 0;
            $new = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', 1)->get('total');
            $count = (float)count($new);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_new = $total_new + (float)$new[$i]['total'];
            }
            $confim = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', 2)->get('total');
            $count = (float)count($confim);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_confim = $total_confim + (float)$confim[$i]['total'];
            }
            $vc = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', 3)->get('total');
            $count = (float)count($vc);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_vc = $total_vc + (float)$vc[$i]['total'];
            }
            $done = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', 4)->get('total');
            $count = (float)count($done);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_done = $total_done + (float)$done[$i]['total'];
            }
            $cancel = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', 5)->get('total');
            $count = (float)count($cancel);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_cancel = $total_cancel + (float)$cancel[$i]['total'];
            }
            $total = $total_new + $total_cancel + $total_done + $total_confim + $total_vc;
            DB::commit();
            return response()->json([
                'status' => true,
                'total_new' => $total_new,
                'total_confim' => $total_confim,
                'total_vc' => $total_vc,
                'total_done' => $total_done,
                'total_cancel' => $total_cancel,
                'total_week' => $total
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function total_month()
    {
        DB::beginTransaction();
        try {
            $total_new = 0;
            $total_cancel = 0;
            $total_done = 0;
            $total_confim = 0;
            $total_vc = 0;
            $new = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 1)->get('total');
            $count = (float)count($new);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_new = $total_new + (float)$new[$i]['total'];
            }
            $confim = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 2)->get('total');
            $count = (float)count($confim);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_confim = $total_confim + (float)$confim[$i]['total'];
            }
            $vc = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 3)->get('total');
            $count = (float)count($vc);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_vc = $total_vc + (float)$vc[$i]['total'];
            }
            $done = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 4)->get('total');
            $count = (float)count($done);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_done = $total_done + (float)$done[$i]['total'];
            }
            $cancel = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 5)->get('total');
            $count = (float)count($cancel);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total_cancel = $total_cancel + (float)$cancel[$i]['total'];
            }
            $total = $total_new + $total_cancel + $total_done + $total_confim + $total_vc;

            DB::commit();
            return response()->json([
                'status' => true,
                'total_new' => $total_new,
                'total_confim' => $total_confim,
                'total_vc' => $total_vc,
                'total_done' => $total_done,
                'total_cancel' => $total_cancel,
                'total_month' => $total
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function total_year()
    {
        DB::beginTransaction();
        try {
            $total_new = 0;
            $total_cancel = 0;
            $total_done = 0;
            $total_confim = 0;
            $total_vc = 0;
            $order = Order::select(DB::raw('YEAR(created_at) as year'))->groupBy('year')->get()->keyBy('year');
            if ($order) {
                $new = Order::where('status', 1)->get('total');
                $count = (float)count($new);
                for ($i = 0; $i <= $count - 1; $i++) {
                    $total_new = $total_new + (float)$new[$i]['total'];
                }
                $confim = Order::where('status', 2)->get('total');
                $count = (float)count($confim);
                for ($i = 0; $i <= $count - 1; $i++) {
                    $total_confim = $total_confim + (float)$confim[$i]['total'];
                }
                $vc = Order::where('status', 3)->get('total');
                $count = (float)count($vc);
                for ($i = 0; $i <= $count - 1; $i++) {
                    $total_vc = $total_vc + (float)$vc[$i]['total'];
                }
                $done = Order::where('status', 4)->get('total');
                $count = (float)count($done);
                for ($i = 0; $i <= $count - 1; $i++) {
                    $total_done = $total_done + (float)$done[$i]['total'];
                }
                $cancel = Order::where('status', 5)->get('total');
                $count = (float)count($cancel);
                for ($i = 0; $i <= $count - 1; $i++) {
                    $total_cancel = $total_cancel + (float)$cancel[$i]['total'];
                }

            }
            $total = $total_new + $total_cancel + $total_done + $total_confim + $total_vc;

            DB::commit();
            return response()->json([
                'status' => true,
                'total_new' => $total_new,
                'total_confim' => $total_confim,
                'total_vc' => $total_vc,
                'total_done' => $total_done,
                'total_cancel' => $total_cancel,
                'total_year' => $total
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
//======================================================================================================================
    //day
    public function cancel_day()
    {
        DB::beginTransaction();
        try {
            $cancel = Order::whereDay('created_at', Carbon::today())->where('status', 5)->count();
            DB::commit();
            return response()->json([
                'status' => true,
                'order_cancel' => $cancel
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //week
    public function cancel_week()
    {
        DB::beginTransaction();
        try {
            $cancel = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('status', 5)->count();
            return response()->json([
                'status' => true,
                'order_cancel' => $cancel
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //month
    public function cancel_month()
    {
        DB::beginTransaction();
        try {
            $cancel = Order::whereMonth('created_at', Carbon::today()->month)->where('status', 5)->count();
            return response()->json([
                'status' => true,
                'order_cancel' => $cancel
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //year
    public function cancel_year()
    {
        DB::beginTransaction();
        try {
            $order = Order::select(DB::raw('YEAR(created_at) as year'))->groupBy('year')->get()->keyBy('year');
            if ($order) {
                $cancel = Order::where('status', 5)->count();
            }
            return response()->json([
                'status' => true,
                'order_cancel' => $cancel
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function total_order()
    {
        DB::beginTransaction();
        try{
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $total5 = 0;
            $total6 = 0;
            $total7 = 0;
            $total8 = 0;
            $total9 = 0;
            $total10 = 0;
            $total11 = 0;
            $total12 = 0;
            $month1 = Order::WhereMonth('created_at', 1)->where('status', 4)->get('total');
            $count = (float)count($month1);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total1 = $total1 + (float)$month1[$i]['total'];
            }
            $month2 = Order::WhereMonth('created_at', 2)->where('status', 4)->get('total');
            $count = (float)count($month2);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total2 = $total2 + (float)$month2[$i]['total'];
            }
            $month3 = Order::WhereMonth('created_at', 3)->where('status', 4)->get('total');
            $count = (float)count($month3);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total3 = $total3 + (float)$month3[$i]['total'];
            }
            $month4 = Order::whereMonth('created_at', 4)->where('status', 4)->get('total');
            $count = (float)count($month4);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total4 = $total4 + (float)$month4[$i]['total'];
            }
            $month5 = Order::WhereMonth('created_at', 5)->where('status', 4)->get('total');
            $count = (float)count($month5);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total5 = $total5 + (float)$month5[$i]['total'];
            }
            $month6 = Order::WhereMonth('created_at', 6)->where('status', 4)->get('total');
            $count = (float)count($month6);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total6 = $total6 + (float)$month6[$i]['total'];
            }
            $month7 = Order::WhereMonth('created_at', 7)->where('status', 4)->get('total');
            $count = (float)count($month7);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total7 = $total7 + (float)$month7[$i]['total'];
            }
            $month8 = Order::WhereMonth('created_at', 8)->where('status', 4)->get('total');
            $count = (float)count($month8);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total8 = $total8 + (float)$month8[$i]['total'];
            }
            $month9 = Order::WhereMonth('created_at', 9)->where('status', 4)->get('total');
            $count = (float)count($month9);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total9 = $total9 + (float)$month9[$i]['total'];
            }
            $month10 = Order::WhereMonth('created_at', 10)->where('status', 4)->get('total');
            $count = (float)count($month10);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total10 = $total10 + (float)$month10[$i]['total'];
            }
            $month11 = Order::WhereMonth('created_at', 11)->where('status', 4)->get('total');
            $count = (float)count($month11);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total11 = $total11 + (float)$month11[$i]['total'];
            }
            $month12 = Order::WhereMonth('created_at', 12)->where('status', 4)->get('total');
            $count = (float)count($month12);
            for ($i = 0; $i <= $count - 1; $i++) {
                $total12 = $total12 + (float)$month12[$i]['total'];
            }

            $collection = collect(['month1' => $total1,'month2' => $total2,'month3' => $total3,'month4' => $total4,'month5' => $total5,'month6' => $total6,'month7' => $total7,'month8' => $total8, 'month9' => $total9,'month10' => $total10,'month11' => $total1,'month12' => $total12]);
//        $merged = $collection->merge(['month8' => $total8, 'month9' => $total9]);
            DB::commit();
            return response()->json([
               'status'=>true,
                'data'=>$collection
            ]);
        }catch (\Exception $e){
            throw $e;
        }



    }

    public function total_new()
    {
        DB::beginTransaction();
        try {
            $new = Order::where('status', 1)->count();
            return response()->json([
                'status' => true,
                'data' => $new
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function customer()
    {
        DB::beginTransaction();
        try {
            $user = User::where('role_id', 2)->count();
            return response()->json([
                'status' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
