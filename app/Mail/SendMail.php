<?php

namespace App\Mail;

use App\Item;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $id ;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$id)
    {
        //
        $this->email = $email;
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(Request $request)
    {


        $order = Order::where('id', $this->id)->get();
        if (!$order) {
            throw new NotFoundHttpException('Not data category');
        }
        foreach ($order as $item){
            foreach ($item->item as $value){
                $value->product;
            }
            foreach ($item->address as $addr) {
                $addr->provinces;
                $addr->districts;
                $addr->wards;
            };
            $item->discount;
            $item->OrderStatus;
            $item->ship;
            $item->paytype;
            $data = $order;
        }
        $items = Item::where('order_id', $this->id)->get();
        $totaltempcost = 0;
        foreach ($items as $val) {
            $totaltempcost += (float)$val->temp;

        }
        //            if($item->discount->time_start < Carbon::now() && $item->discount->time_end < Carbon::now())

        if ($item->discount){
            if($item->discount->time_start < Carbon::now() && Carbon::now() < $item->discount->time_end  ){
                $discount=((float)$totaltempcost*(float)$item->discount->percent)/100;
            }
            else{
                $discount = 0;
            }
        }
        else{
//            $discount = (float)$totaltempcost;
            $discount = 0;
        }
        return $this->subject('Thông tin chi tiết đơn hàng')->view('order',compact('data','totaltempcost','discount'));
    }
}
