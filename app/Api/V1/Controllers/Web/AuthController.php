<?php

namespace App\Api\V1\Controllers\Web;

use App\Device_token;
use App\Http\Controllers\Controller;
use App\Mail\MailLogin;
use App\Mail\ResetPassAdmin;
use App\Mail\SendMail;
use App\Notifi;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use JWTAuth;
use Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{

    public $email;
    public function __construct()
    {
        $this->middleware('admin')->except('login','reset', 'image', 'createDevice');
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

    public function login(Request $request, JWTAuth $JWTAuth)
    {

        $credentials = $request->only(['phone','email', 'password']);
        $this->email = "";

        try {
            $token = Auth::guard()->attempt($credentials);
            if (!$token) {
                throw new AccessDeniedHttpException('Email or password is incorrect');
            }
        } catch (JWTException $e) {
            throw new HttpException(500);
        }
//        $toMail = $request['email'];
//        $toMail = User::where('role_id',1)->get();
//        Mail::to($toMail)->send(new MailLogin($this->email));
        return response()
            ->json([
                'status' => true,
                'token' => $token,
                'data' => Auth::guard()->user(),
                'expires_in' => Auth::guard()->factory()->getTTL() * 60
            ]);

    }

    /*
     * input: token
     */
    public function logout()
    {
        Auth::guard()->logout();
        return response()
            ->json([
                'status' => true,
                'message' => 'Successfully logged out'
            ]);
    }

    public function check()
    {
        $user = Auth::guard()->user();

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

    public function delete($id){
        $del = User::destroy($id);
        return response()->json([
           'status'=>true,
           'data'=>$del
        ]);
    }

    public function me() {
        $user = Auth::guard()->user();

        return response()->json([
            'status'=>true,
            'data'=>$user
        ]);
    }
    public function reset(Request $request){
        DB::beginTransaction();
        try{
            $email = User::where('email', $request['email'])
                ->where('role_id', 1)
                ->first();
            if (!$email){
                return response()->json([
                    'Email does not exist'
                ]) ;
            }
            else{
                $user = User::find($email['id']);
                $user->password = bcrypt(time());
                $user->save();
            }

            DB::commit();

            Mail::to($request['email'])->send(new ResetPassAdmin($request['email']) );
            return response()->json([
                'status'=>true,
//                'data'=>$email
            ]);
        }catch (\Exception $e){
            throw $e;
        }
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
    public function shownotifi(){
        DB::beginTransaction();
        try{
            $notifi = Notifi::all();
            if (!$notifi){
                throw new NotFoundHttpException('Not data notification');
            }
            DB::commit();
            return response()->json([
                'status'=>true,
                'data'=>$notifi
            ]);

        }catch (\Exception $e){
            throw $e;
        }

    }
    public function editnotifi($id, Request $request){
        DB::beginTransaction();
        try{
            $notifi = Notifi::find($id)->first();
            $notifi->status = $request['status'];
            $notifi->save();

            DB::commit();
            return response()->json([
                'status'=>true,
                'data'=>$notifi
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }
    public function new(){
        DB::beginTransaction();
        try{
            $new = Notifi::where('status',0)->orderBy('created_at', 'desc')->get();
            if(!$new){
                throw new NotFoundHttpException('Not data notification');
            }
            DB::commit();
            return response()->json([
                'status'=>true,
                'data'=>$new
            ]);
        }catch (\Exception $e){
            throw $e;
        }
    }
    public function  confirm(){
        DB::beginTransaction();
        try{
            $confirm= Notifi::where('status',1)->orderBy('created_at', 'desc')->get();
            if(!$confirm){
                throw new NotFoundHttpException('Not data notification');
            }
            DB::commit();
            return response()->json([
                'status'=>true,
                'data'=>$confirm
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
