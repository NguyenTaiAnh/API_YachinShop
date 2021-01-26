<?php

namespace App\Api\V1\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;


class CustomerController extends Controller
{

    public $email;
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function listCustomer() {
        DB::beginTransaction();
        try {
            $cus = User::where('role_id', 2)->get();
            DB::commit();
            return response()
                ->json([
                    'status' => true,
                    'data' => $cus
                ]);

        } catch (\Exception $e) {
            throw $e;
        }

    }
}
