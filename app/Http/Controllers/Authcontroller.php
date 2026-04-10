<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\wallet;
use App\Traits\WalletTrait;
use App\Mail\sendCodeWallet;
use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Mail\adminSendCodePassword;
use App\Mail\sendResetCodePassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class Authcontroller extends Controller
{
    use WalletTrait;
    public function user_registar(Request $request)
    {
     $validator = Validator::make($request->all(), [
     'email' => 'required|email|unique:user',
     ]);

     $input = $request->all();
     $code = mt_rand(100000, 999999);   
     ResetCodePassword::create([  
     'email' => $input['email'],
     'code' => $code,
     ]);
     Mail::to($input['email'])->send(new sendResetCodePassword($code));
     return response()->json(['message' => 'Verification code sent to your email.']);
     }

     /////////////////////////////////////////////////////////////////////////////////////////
    public function user_verify_code(Request $request)
    {

     $request->validate([
     'email' => 'required|email',
     'code' => 'required|numeric',
     ]);

     $resetCode = ResetCodePassword::where('email', $request->email)->latest()->first();

     if (!$resetCode || $resetCode->code != $request->code) {
     return response()->json(['error' => 'Invalid verification code.'], 422);
     }

     $ali=$request->password;
     $ali=bcrypt($ali);




      $user = User::create([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'email' => $request->email,
      'password' => $ali,
      'phone_number' => $request->phone_number,
      'location' => $request->location,
      'annual_income' => $request->annual_income,
      'gender' => $request->gender,
      'country' => $request->country,
      'birth' => $request->birth,
      ]);
      $user_id=$user->id;
      $path= 'App\\User';
      $wallet = $this->wallet($user_id,$path);
      Mail::to($request->email)->send(new sendCodeWallet($wallet));

      $resetCode->delete();

      $accessToken = $user->createToken('MyApp', ['user'])->accessToken;

      return response()->json(['message' => 'Verification code is true' ]);
    }
//////////////////////////////////////////////////////////////
public function user_login(Request $request)
          {
        $request->validate([
         'email'    => 'required',
         'password' => 'required' ]);

            $credentials = $request->only('email', 'password');  

         if (auth()->guard('user')->attempt($credentials))
           {

           config(['auth.guards.api.provider'=>'user']);

           $user=User::query()->select('users.*')->find(auth()->guard('user')->user()['id']);

           $success['token']=$user->createToken('MyApp',['user'])->accessToken;
           return response()->json($success['token'],200);
           }

           else{
            return response()->json(['error'=>['Unauthorized'] ,401]);

        }
    }
/////////////////////////////////////////////////////////
  public function user_logout()
         {
         Auth::guard('user-api')->user()->token()->revoke();
         return response()->json([
          'message'=>'logged out done']);

         }
         //////////////////////////////////////////////
  public function user_forget_password(Request $request)
         {
            $data = $request->validate([
                'email' => 'required|email|exists:users',
            ]);
            ResetCodePassword::where('email', $request->email)->delete();


            $data['code'] = mt_rand(100000, 999999); 


            $codeData = ResetCodePassword::create($data);

   
            Mail::to($request->email)->send(new sendResetCodePassword($codeData->code));

            return response(['message' => trans('passwords.sent')], 200);

        }

     public function user_check_code(Request $request)
        {
         $request->validate([
             'code' => 'required|string|exists:reset_code_passwords', ]);

            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            if ($passwordReset->created_at > now()->addHour()) { 
                $passwordReset->delete();
                return response(['message' => trans('passwords.code_is_expire')], 422);
            }
            return response([
                'code' => $passwordReset->code,
                'message' => trans('passwords.code_is_valid')
            ], 200);
        }


         public function user_reset_password(Request $request)
         {
            $request->validate([
                'code' => 'required|string|exists:reset_code_passwords',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            if ($passwordReset->created_at > now()->addHour()) { 
                $passwordReset->delete();
                return response(['message' => trans('passwords.code_is_expire')], 422);
            }

            $user = User::firstWhere('email', $passwordReset->email);


            $password=$request->input('password');
            $pass=Hash::make($password);
            $user->update(['password' => $pass]);
            $passwordReset->delete();

            return response(['message' =>'password has been successfully reset'], 200);

        }



public function admin_registar(Request $request)
{
 $validator = Validator::make($request->all(), [
 'email' => 'required|email|unique:user',
 ]);

 $input = $request->all();
 $code = mt_rand(100000, 999999);
 ResetCodePassword::create([
 'email' => $input['email'],
 'code' => $code,
 ]);
 //
 Mail::to($input['email'])->send(new sendResetCodePassword($code));

 return response()->json(['message' => 'Verification code sent to your email.']);
 }

 /////////////////////////////////////////////////////////////////////////////////////////
public function admin_verify_code(Request $request)
{
 $request->validate([
 'email' => 'required|email',
 'code' => 'required|numeric',
 ]);
 $resetCode = ResetCodePassword::where('email', $request->email)->latest()->first();

 if (!$resetCode || $resetCode->code != $request->code) {
 return response()->json(['error' => 'Invalid verification code.'], 422);
 }
 $ali=$request->password;
 $ali=bcrypt($ali);

  $admin = Admin::create([
    'first_name' => $request->first_name,
    'last_name' => $request->last_name,
    'email' => $request->email,
    'password' => $ali,
    'phone_number' => $request->phone_number,
  ]);
  $resetCode->delete();
  $accessToken = $admin->createToken('MyApp', ['admin'])->accessToken;
  return response()->json([ 'message' => 'Verification code is true']);
}

//////////////////////////////////////////////////////////////
public function admin_login(Request $request)
          {
            $request->validate([
            'email'    => 'required',
            'password' => 'required' ]);

            $credentials = $request->only('email', 'password');  

        if (auth()->guard('admin')->attempt($credentials))
           {
           config(['auth.guards.api.provider'=>'admin']);
           $user=Admin::query()->select('admins.*')->find(auth()->guard('admin')->user()['id']);

         $success[]=$user->createToken('MyApp',['admin'])->accessToken;
           $role=$user->role;
           return response()->json(['token' => $success ,
        'role' => $role]);
           }
           else{
            return response()->json([
                'error'=>['Unauthorized'] ,401
            ]);

        }
    }
/////////////////////////////////////////////////////////
         public function admin_logout()
         {
         Auth::guard('admin-api')->user()->token()->revoke();
         return response()->json([ 'success'=>'logged out done' ]);
         }

////////////////////////////////////////////////////////////////////////

         public function admin_forget_password(Request $request)
         {
            $data = $request->validate([
                'email' => 'required|email|exists:admins',
            ]);

            ResetCodePassword::where('email', $request->email)->delete();

            $data['code'] = mt_rand(100000, 999999);

            $codeData = ResetCodePassword::create($data);

            Mail::to($request->email)->send(new sendResetCodePassword($codeData->code));

            return response(['message' => trans('passwords.sent')], 200);

        }
////////////////////////////////////////////////////////////////////////
        public function admin_check_code(Request $request)
        {
            $request->validate([
                'code' => 'required|string|exists:reset_code_passwords',
            ]);

            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);


            if ($passwordReset->created_at > now()->addHour()) {
                $passwordReset->delete();
                return response(['message' => trans('passwords.code_is_expire')], 422);
            }

            return response([
                'code' => $passwordReset->code,
                'message' => trans('passwords.code_is_valid')
            ], 200);
        }

////////////////////////////////////////////////////////////////////////

         public function admin_reset_password(Request $request)
         {
            $request->validate([
                'code' => 'required|string|exists:reset_code_passwords',
                'password' => 'required|string|min:6|confirmed',
            ]);

       // return  $request->password;
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);


            if ($passwordReset->created_at > now()->addHour()) {
                $passwordReset->delete();
                return response(['message' => trans('passwords.code_is_expire')], 422);
            }


            $user = admin::firstWhere('email', $passwordReset->email);


            $password=$request->input('password');
            $pass=Hash::make($password);
            $user->update(['password' => $pass]);


            $passwordReset->delete();

            return response(['message' =>'password has been successfully reset'], 200);

        }






          }
