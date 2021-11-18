<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Crypt;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as RulesPassword;

use App\Models\User;
class AuthController extends Controller
{
    public function login(Request $request) {

        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);//

        if ($validate->fails()) {
            $respon = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null,
            ];
            return response()->json($respon, 200);
        } else {
            $credentials = request(['email', 'password']);
            $credentials = Arr::add($credentials, 'status', 'aktif');
            if (!Auth::attempt($credentials)) {
                $respon = [
                    'status' => 'error',
                    'msg' => 'Unathorized',
                    'errors' => null,
                    'content' => null,
                ];
                return response()->json($respon, 401);
            }

            // $encrypted = Crypt::encryptString($request->password);
		    // $decrypted_password_request = Crypt::decryptString($encrypted);
            
            $user = User::where('email', $request->email)->first();
		    // $decrypted_password_user = Crypt::decryptString($user->password);
            if (! \Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }
            // else if($decrypted_password_request !== $decrypted_password_user){
            //     throw new \Exception('Error in Login');
            // }

            $tokenResult = $user->createToken('token-auth')->plainTextToken;
            $respon = [
                'status' => 'success',
                'msg' => 'Login successfully',
                'errors' => null,
                'content' => [
                    'status_code' => 200,
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                ]
            ];
            return response()->json($respon, 200);
        }
    }


    public function daftar(Request $request)
    {
      
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
      
        
            if ($validate->fails()) {
                $respon = [
                    'status' => 'error',
                    'msg' => 'Validator error',
                    'errors' => $validate->errors(),
                    'content' => null,
                ];
                return response()->json($respon, 204);
            }

            $created = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password)
                // 'password' => Crypt::encryptString($request->password)
            ]);
            
             $respon = [
                'status' => 'Successfully',
                'msg' => 'Success Created Data',
                'Data' => $created,
                'content' => null,
            ];
               return response()->json($respon, 201);

                
 




            
        
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();
       $respon = [
           'msg' => $user->name.' logged out Successfully'
       ];
        return response()->json($respon, 200);
    }

    public function logoutall(Request $request) {
        $user = $request->user();
        $user->tokens()->delete();
        $respon = [
            'status' => 'success',
            'msg' => 'Logout successfully',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($respon, 200);
    }


    public function forgotPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return[
                'status' => __($status)
            ];
        }
         throw ValidationException::withMessages([
                'email' => [trans($status)],
         ]);
    }

    public function reset(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required',
            'password' => ['required', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email','password','token'),
            function($user) use ($request){
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60)
                ])->save(); 
                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message' => 'Password  reset Successfully'
            ]);
        }
        return response([
            'message' => __($status)
        ],500);


    }
}
