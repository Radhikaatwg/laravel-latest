<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Auth;
use Image;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use App\Models\eventtracker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
class AuthController extends Controller
{
    public function user_signup(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users',
            'other_mobile_number' => 'required|integer',
            'profile_pic' => 'required',
            'password' => 'required|string|confirmed'
        ]);



        $base64_image = $request->input('profile_pic'); // your base64 encoded
        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);
        $imageName = 'IMAGE'.Str::random(30).'.'.'png';
        Storage::disk('public')->put('profile_image_file/'.$imageName, base64_decode($file_data));

        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create("+91".$request->other_mobile_number, "sms");

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'profile_pic' => 'profile_image_file/'.$imageName,
            'usertype' => 1,
            'other_mobile_number' => $request->other_mobile_number,
            'password' => bcrypt($request->password)
        ]);

        $user->save();
        eventtracker::create(['symbol_code' => '1', 'event' => $request->name.' created a new account as a User']);

        return response()->json([
            'data' => $user,
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function owner_signup(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users',
            'other_mobile_number' => 'required|integer',
            'address' => 'required',
            'city' => 'required',
            'pan_number' => 'required',
            'aadhar_number' => 'required',
            'profile_pic' => 'required',
            'password' => 'required|string|confirmed'
        ]);

        $base64_image = $request->input('profile_pic'); // your base64 encoded
        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);
        $imageName = 'IMAGE'.Str::random(30).'.'.'png';
        Storage::disk('public')->put('profile_image_file/'.$imageName, base64_decode($file_data));

        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create("+91".$request->other_mobile_number, "sms");

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'other_mobile_number' => $request->other_mobile_number,
            'address' => $request->address,
            'city' => $request->city,
            'pan_number' => $request->pan_number,
            'aadhar_number' => $request->aadhar_number,
            'profile_pic' => 'profile_image_file/'.$imageName,
            'usertype' => 2,
            'password' => bcrypt($request->password)
        ]);

        $user->save();
        eventtracker::create(['symbol_code' => '2', 'event' => $request->name.' created a new account as a Owner']);


        return response()->json([
            'data' => $user,
            'message' => 'Successfully created owner'
        ], 201);
    }

    public function dealer_company_signup(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users',
            'other_mobile_number' => 'required|integer',
            'address' => 'required',
            'city' => 'required',
            'pan_number' => 'required',
            'aadhar_number' => 'required',
            'company_name' => 'required',
            'company_url' => 'required',
            'landline_number' => 'required',
            'company_profile' => 'required',
            'profile_pic' => 'required',
            'password' => 'required|string|confirmed'
        ]);

        $base64_image = $request->input('profile_pic'); // your base64 encoded
        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);
        $imageName = 'IMAGE'.Str::random(30).'.'.'png';
        Storage::disk('public')->put('profile_image_file/'.$imageName, base64_decode($file_data));

        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create("+91".$request->other_mobile_number, "sms");

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'other_mobile_number' => $request->other_mobile_number,
            'address' => $request->address,
            'city' => $request->city,
            'pan_number' => $request->pan_number,
            'aadhar_number' => $request->aadhar_number,
            'landline_number' => $request->landline_number,
            'company_name' => $request->company_name,
            'company_url' => $request->company_url,
            'company_profile' => $request->company_profile,
            'profile_pic' => 'profile_image_file/'.$imageName,
            'usertype' => 3,
            'password' => bcrypt($request->password)
        ]);

        $user->save();
        eventtracker::create(['symbol_code' => '3', 'event' => $request->name.' created a new account as a Dealer']);


        return response()->json([
            'data' => $user,
            'message' => 'Successfully created dealer/company'
        ], 201);
    }



    public function lawyer_signup(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users',
            'other_mobile_number' => 'required|integer',
            'address' => 'required',
            'city' => 'required',
            'pan_number' => 'required',
            'aadhar_number' => 'required',
            'landline_number' => 'required',
            'practice_number' =>'required',
            'law_firm_number' =>'required',
            'provided_service' =>'required',
            'place_of_practice' =>'required',
            'price_for_service' =>'required',
            'profile_pic' => 'required',
            'password' => 'required|string|confirmed'
        ]);

        $base64_image = $request->input('profile_pic'); // your base64 encoded
        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);
        $imageName = 'IMAGE'.Str::random(30).'.'.'png';
        Storage::disk('public')->put('profile_image_file/'.$imageName, base64_decode($file_data));

        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create("+91".$request->other_mobile_number, "sms");

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'pan_number' => $request->pan_number,
            'aadhar_number' => $request->aadhar_number,
            'provided_service' =>$request->provided_service,
            'place_of_practice' =>$request->place_of_practice,
            'price_for_service' =>$request->price_for_service,
            'law_firm_number' =>$request->law_firm_number,
            'practice_number' =>$request->practice_number,
            'other_mobile_number' => $request->other_mobile_number,
            'landline_number' => $request->landline_number,
            'profile_pic' => 'profile_image_file/'.$imageName,
            'usertype' => 4,
            'password' => bcrypt($request->password)
        ]);

        $user->save();
        eventtracker::create(['symbol_code' => '4', 'event' => $request->name.' created a new account as a Lawyer']);


        return response()->json([
            'data' => $user,
            'message' => 'Successfully created lawyer'
        ], 201);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($data['verification_code'], array('to' => "+91".$data['phone_number']));

        if ($verification->valid) {
            User::where('other_mobile_number', $data['phone_number'])->update(['phone_number_verification_status' => 1]);
            return response()->json([
                'message' => 'Successfully verified'
            ], 201);
        }
        return response()->json([
            'message' => 'verification error'
        ], 401);
    }

    public function reverify(Request $request)
    {
        $data = $request->validate([
            'verification_code' => 'required|string',
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create(Auth::user()->other_mobile_number, "sms");

        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($data['verification_code'], array('to' => Auth::user()->other_mobile_number));

        if ($verification->valid) {
            User::where('other_mobile_number', Auth::user()->other_mobile_number)->update(['phone_number_verification_status' => 1]);
            return response()->json([
                'message' => 'Successfully verified'
            ], 201);
        }
        return response()->json([
            'message' => 'verification error'
        ], 201);
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Invalid Username or Password'
            ], 401);
        $user = $request->user();

        if ($user->blocked == 1)
            return response()->json([
                'message' => 'Your account is blocked'
            ], 403);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(20);
        $token->save();
        return response()->json([
            'username' => $user->name,
            'id' => $user->id,
            'usertype' => $user->usertype,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'misc' => $user
        ]);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request){
        return response()->json($request->user());
    }


    public function company_signup(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users',
            'profile_pic' => 'required',
            'usertype' => 'required',
            'other_mobile_number' => 'required|integer|between:1000000000,9999999999',
            'password' => 'required|string|confirmed'
        ]);

        $base64_image = $request->input('profile_pic'); // your base64 encoded
        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);
        $imageName = 'IMAGE'.Str::random(30).'.'.'png';
        Storage::disk('public')->put('profile_image_file/'.$imageName, base64_decode($file_data));

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'profile_pic' => 'profile_image_file/'.$imageName,
            'usertype' => $request->usertype,
            'other_mobile_number' => $request->other_mobile_number,
            'password' => bcrypt($request->password)
        ]);

        $user->save();
        eventtracker::create(['symbol_code' => '6', 'event' => $request->name.' Company Member Created']);


        return response()->json([
            'data' => $user,
            'message' => 'Successfully created admin!'
        ], 201);
    }

    public function admin_signup(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:users',
            'profile_pic' => 'required',
            'other_mobile_number' => 'required|integer|between:1000000000,9999999999',
            'password' => 'required|string|confirmed'
        ]);

        $base64_image = $request->input('profile_pic'); // your base64 encoded
        @list($type, $file_data) = explode(';', $base64_image);
        @list(, $file_data) = explode(',', $file_data);
        $imageName = 'IMAGE'.Str::random(30).'.'.'png';
        Storage::disk('public')->put('profile_image_file/'.$imageName, base64_decode($file_data));

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'profile_pic' => 'profile_image_file/'.$imageName,
            'usertype' => 11,
            'other_mobile_number' => $request->other_mobile_number,
            'password' => bcrypt($request->password)
        ]);

        $user->save();
        eventtracker::create(['symbol_code' => '6', 'event' => $request->name.' Admin Created']);


        return response()->json([
            'data' => $user,
            'message' => 'Successfully created admin!'
        ], 201);
    }

    public function admin_login(Request $request){

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Invalid Admin Credentials'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(20);
        $token->save();
        return response()->json([
            'username' => $user->name,
            'id' => $user->id,
            'usertype' => $user->usertype,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'misc' => $user
        ]);
    }

    public function forgot_password(Request $request){
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                    case Password::INVALID_USER:
                        return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
                }
            } catch (\Swift_TransportException $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            } catch (Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }

    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);
    }

    public function googleredirect(){
        return Socialite::driver('google')->redirect();
    }

    public function googlecallback(){
        $user = Socialite::driver('google')->user();

        $finduser = User::where('email', $user->email)->first();

        if($finduser){

            Auth::login($finduser);


            $tokenResult = $finduser->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(20);
            $token->save();

            return redirect()->to('https://www.housingstreet.com/login?token='.$tokenResult->accessToken.'&data='.$finduser);

            // return response()->json([

            //     'username' => $finduser->name,
            //     'id' => $finduser->id,
            //     'usertype' => $finduser->usertype,
            //     'access_token' => $tokenResult->accessToken,
            //     'token_type' => 'Bearer',
            //     'expires_at' => Carbon::parse(
            //         $tokenResult->token->expires_at
            //     )->toDateTimeString(),
            //     'misc' => $finduser
            //     ]);

        }else{
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'usertype' => 1,
                'profile_pic' => $user->avatar_original,
                'other_mobile_number' => 1234567890,
                'phone_number_verification_status' => 1,
                'id'=> $user->id,
                'password' => encrypt('123456dummy')
            ]);

            Auth::login($newUser);

            $datauser = User::where('email', $newUser->email)->first();

            $tokenResult = $datauser->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(20);
            $token->save();

            return redirect()->to('https://www.housingstreet.com/login?token='.$tokenResult->accessToken.'&data='.$datauser);

            // return response()->json([
            //     'username' => $datauser->name,
            //     'id' => $datauser->id,
            //     'usertype' => $datauser->usertype,
            //     'access_token' => $tokenResult->accessToken,
            //     'token_type' => 'Bearer',
            //     'expires_at' => Carbon::parse(
            //         $tokenResult->token->expires_at
            //     )->toDateTimeString(),
            //     'misc' => $datauser
            //     ]);
        }

    }

}
