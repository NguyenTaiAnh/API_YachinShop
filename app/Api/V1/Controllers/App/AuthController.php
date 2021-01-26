<?php

namespace App\Api\V1\Controllers\App;

use App\Device_token;
use App\Mail\ResetPassword;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\JWTAuth;
use Auth;
use Input;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('customer')->except('loginEmail', 'loginPhone', 'image', 'register', 'editpassword', 'createDevice');
    }

    public function show()
    {
        DB::beginTransaction();
        try {
            $data = User::all();
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

    public function loginEmail(Request $request, JWTAuth $JWTAuth)
    {
        DB::beginTransaction();
            $credentials = $request->only(['email', 'password']);
            try {
                $device = Device_token::where('token', $request['device'])->first();
                if (!$device) {
                    $tokens = new Device_token;
                    $tokens->token = $request['device'];
                    $tokens->save();
                }
                $token = Auth::guard()->attempt($credentials);
                if (!$token) {
                    throw new AccessDeniedHttpException('Email or password is incorrect');
                }
            } catch (JWTException $e) {
                throw new \HttpException(500);
            }
        DB::commit();
        return response()->json([
            'status' => true,
            'token' => $token,
            'data' => Auth::guard()->user()
        ]);
    }

    public function loginPhone(Request $request, JWTAuth $JWTAuth)
    {
        DB::beginTransaction();
        $credentials = $request->only(['phone', 'password']);
        try {
            $device = Device_token::where('token', $request['device'])->first();
            if (!$device) {
                $tokens = new Device_token;
                $tokens->token = $request['device'];
                $tokens->save();
            }
            $token = Auth::guard()->attempt($credentials);
            if (!$token) {
                throw new AccessDeniedHttpException('Phone or password is incorrect');
            }
        } catch (JWTException $e) {
            throw new \HttpException(500);
        }
        DB::commit();
        return response()->json([
            'status' => true,
            'token' => $token,
            'data' => Auth::guard()->user()
        ]);
    }

    public function register(Request $request, JWTAuth $JWTAuth) {
        DB::beginTransaction();
        try {
            if(!$request['password'] && !$request['phone'] && !$request['name']){
                return response()->json([
                    'status' => 11,
                    'message' => 'pasword validate'
                ]);
            }

            if(!$request['phone']){
                return response()->json([
                    'status' => 1,
                    'message' => 'phone validate'
                ]);
            }
            if(!$request['name']){
                return response()->json([
                    'status' => 2,
                    'message' => 'name validate'
                ]);
            }
            if(!$request['password']){
                return response()->json([
                    'status' => 3,
                    'message' => 'pasword validate'
                ]);
            }

            $phone = User::where('phone', $request['phone'])->first();
            $email = User::where('email', $request['email'])->first();
            if ($email) {
                return response()->json([
                    'status' => 4,
                    'message' => 'Email already exists'
                ]);
            }
            else if ($phone) {
                return response()->json([
                    'status' => 4,
                    'message' => 'Email already exists'
                ]);
            }
            else {
                $user = new User();
                $user->name = $request['name'];
                $user->phone = $request['phone'];
                $user->email = $request['email'];
                $user->role_id = 2;
                $user->avatar = 'https://images.pexels.com/photos/2783477/pexels-photo-2783477.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260';
                $user->password = bcrypt($request['password']);
                $user->save();
            }

            $device = Device_token::where('token', $request['device'])->first();
            if (!$device) {
                $tokens = new Device_token;
                $tokens->token = $request['device'];
                $tokens->save();
            }
            $token = $JWTAuth->fromUser($user);


            DB::commit();
            return response()->json([
                'status' => 'ok',
                'token' => $token
            ], 201);

        } catch (JWTException $e) {
            throw new \HttpException(500);
        }

    }

    public function logout()
    {
        Auth::guard()->logout();
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        $token = Auth::guard()->refresh();
        return response()->json([
            'status' => 'ok',
            'token' => $token,
            'expires_in' => Auth::guard()->factory()->getTTL() * 60
        ]);
    }


    public function info(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->phone = $request['phone'];
        if($request['password']) {
            $user->password = bcrypt($request['password']);
        }
        $user->save();

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function image(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->hasFile('avatar')) {
            $destinationPath = public_path() . '/assets/images/user';
            $file = $request->file('avatar');
            $destinationFileName = time() . $file->getClientOriginalName();
            // move the file from tmp to the destination path
            $file->move($destinationPath, $destinationFileName);
            $user->avatar = 'https://sys.yachin.vn/assets/images/user/' . $destinationFileName;
        }
        $user->save();

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function check()
    {
        $user = User::find(auth()->user()->id);

        if (!$user) {
            return response()->json([
                'status' => false
            ]);
        } else {
            return response()->json([
                'status' => true
            ]);
        }
    }

    public function detail()
    {
        DB::beginTransaction();
        try {
            $user = User::find(auth()->user()->id);
            if (!$user) {
                throw new NotFoundHttpException('Id not found');
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function check_facebook(Request $request){
        DB::beginTransaction();
        try{
            $user = User::where('facebook_id',$request['facebook_id'])->get();
            if($user){
                return response()->json([
                    'status'=>true,
                    'data' => $user
                ]);
            }
            else{
                return response()->json([
                    'status'=>false
                ]);
            }
        }catch (\Exception $e){
            throw $e;
        }
    }

    public function editpassword(Request $request){
        DB::beginTransaction();
        try{
            $email = User::where('email', $request['email'])
                ->where('role_id', 2)
                ->first();
            if (!$email){
                return response()->json([
                    'status' => false,
                    'message' => 'Email does not exist'
                ]) ;
            }
            else{
                $user = User::find($email['id']);
                $user->password = bcrypt(time());
                $user->save();
            }

            DB::commit();

            Mail::to($request['email'])->send(new ResetPassword($request['email']));
            return response()->json([
                'status'=>true,
//                'data'=>$email
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }


    public function createDevice(Request $request) {

        DB::beginTransaction();
        try{
            $device = Device_token::where('token', $request['device'])->first();
            if (!$device) {
                $tokens = new Device_token;
                $tokens->token = $request['device'];
                $tokens->save();
            }

            DB::commit();

            return response()->json([
                'status'=>true,
//                'data'=>$email
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }

}

