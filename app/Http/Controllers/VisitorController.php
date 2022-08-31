<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitors;
use App\Models\User;
use App\Models\Tenats;
use App\Models\UploadEmployeeFiles;
use App\Models\Employees;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class VisitorController extends Controller
{
    public function signUp(Request $request) {
        $rules = [
            'name' => 'required',
            'last_name' => 'required',
            'email'    => 'unique:visitors|required|regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/',
            'password' => 'required|regex:/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])([a-zA-Z0-9@$!%*?&]{8,12})$/',
            'building_id' => 'required',
        ];
        $input     = $request->only('name', 'last_name', 'email','password','building_id');
        $validator = Validator::make($input, $rules);
    
        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->messages()]);
        }
        $result = Visitors::where(['email' => $request->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            $name = $request->name;
            $last_name    = $request->last_name;
            $email    = $request->email;
            $password = $request->password;
            $building_id    = $request->building_id;
            $visitor = new Visitors;
            $visitor->name = $name; 
            $visitor->last_name = $last_name; 
            $visitor->email = $email; 
            $visitor->password = crypt::encrypt($password); 
            $visitor->building_id = $building_id;
            $visitor->save();

            $email = $request->email;
            $mail_details['name'] = $request->name;
        
            Mail::send('mail.visitorAccount', $mail_details, function($message) use ($email) {
                $message->to($email)->subject
                   ('Visitor Account Created Successfully!');
            });
            return response()->json(['status' => 200, 'Message' => 'Create visitor account successfully']);
        }
        else {
            return response()->json(['status' => 400, 'Message' => 'Already exists']);
        }
    }
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->messages()]);
        }
        $result = Visitors::where(['email' => $request->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            return response()->json(['status' => 404, 'Message' => 'Visitor Not Exists']);
        }
        else {
            if($result[0]->attempts==3) {
                $visitor = Visitors::where('email','=',$request->email)->update(['attempts' => NULL]);
                return response()->json(['status' => 404, 'Message' => 'You have entered the wrong password thrice. Please reset your password below.']);
            }
            $encrypted_password = $result[0]->password;
            $decrypted_password = crypt::decrypt($encrypted_password);
            if($decrypted_password==$request->input('password')) {
                $visitor = Visitors::where('email','=',$request->email)->update(['attempts' => NULL]);
                return response()->json(['status' => 200, 'Message' => 'Login Successfully!']);
            }
            else {
                $count = 1;
                if($result[0]->attempts) {
                    $count = $result[0]->attempts+1;
                }
                else {
                    $count = 1;
                }
                $visitor = Visitors::where('email','=',$request->email)->update(['attempts' => $count]);
                return response()->json(['status' => 404, 'Message' => 'Wrong password, please re-enter.']);
            }
        }
        
    }
    public function forgotPassword(Request $request) {
        $result = Visitors::where(['email' => $request->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            return response()->json(['status' => 404, 'Message' => 'Visitor Not Exists']);
        }
        else {
            $encrypted_password = crypt::encrypt($request->input('password'));
            $result = Visitors::where("email", $email)->update(["password" => $encrypted_password]);
            return response()->json(['status' => 200, 'Message' => 'password Update successfully']);
        }
    }
    public function requestOtp(Request $request)
    {
        $otp = rand(100000,999999);
        $validator = Validator::make($request->all(), [
            'email'=>'required|regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->messages()]);
        }
        $result = Visitors::where(['email' => $request->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            return response()->json(['status' => 404, 'Message' => 'Visitor Not Exists']);
        }
        else {
            $validationTime = Carbon::now()->addMinutes(3);
            $visitor = Visitors::where('email','=',$request->email)->update(['otp' => $otp,'validate_time' => $validationTime]);

            $mail_details['otp'] = $otp;
            $mail_details['name'] = $result[0]->name;
            $email = $request->email;
        
            Mail::send('mail.requestLoginVisitorOtp', $mail_details, function($message) use ($email) {
                $message->to($email)->subject
                   ('OTP for singland');
            });
    
            return response(["status" => 200, "message" => "OTP sent successfully"]);
        }
    }
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/',
            'otp' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->messages()]);
        }
        $result = Visitors::where(['email' => $request->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            return response()->json(['status' => 404, 'Message' => 'Visitor Not Exists']);
        }
        else {
            $now = Carbon::now();
            if ($result[0]['validate_time'] >= $now) {
                return response()->json(['status' => 400, 'Message' => 'Time Expiredd']);
            }
            else {
                $otp = $request->otp;
                if($otp==$result[0]['otp']) {
                    $visitor = Visitors::where('email','=',$request->email)->update(['otp' => NULL,'validate_time' => NULL]);
                    return response()->json(['status' => 200, 'Message' => 'OTP Verified']);
                }
                else {
                    return response()->json(['status' => 400, 'Message' => 'OTP Mismatch']);
                }
            }
        }

    }

    public function uploadFileEmployee(Request $request) {
        $validator = Validator::make($request->all(),[ 
            'file' => 'required|mimes:csv,xlsx|max:2048',
        ]);   

        if($validator->fails()) {        
            return response()->json(['error'=>$validator->errors()], 401);                        
        } 
        if ($request->file()) {


            if (!Storage::disk('public')->has('employees')){
                Storage::disk('public')->makeDirectory('employees');
            }
            $file_encode = Str::random(20);
            $upload_file = $request->file('file');
            $extension = $upload_file->getClientOriginalExtension();
            $upload_filename = time() . '_' . $file_encode . '.' . $extension;
            $upload_file->storeAs('employees', $upload_filename);
 
            $save = new UploadEmployeeFiles();
            $save->file_Name = $upload_filename;
            $save->total_employees= 3;
            $save->save();

            $agreement_file_not_exists = $upload_trade_license_not_exists = false;
            $agreement_path = 'employees/' . $upload_filename;
            $agreement_file_path = 'javascript:;';

            if (Storage::disk('public')->exists($agreement_path))
            $agreement_file_path = Storage::url($agreement_path);
            else
            $agreement_file_not_exists = true;
            $index = 0;
            $rows = Excel::toArray(new Employees, $request->file('file'));
            $insertEmployees = [];
            foreach ($rows[0] as $key => $value) {
                if($key!=0) {
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value[5]);
                    $result = Employees::where(['email' => $value[1]])->get();                    
                    $res = json_decode($result,true);
                    if(sizeof($res)==0){
                        $index = $key;
                        $save = new Employees();
                        $save->name = $value[0];
                        $save->email= $value[1];
                        $save->job_position = $value[2];
                        $save->phone_number = $value[3];
                        $save->role = $value[4];
                        $save->start_date = $date->format('d M Y');
                        $save->end_date = $value[6];
                        $save->save();
                        $lastInsert = $save->id;
                        $employee = Employees::where(['id' => $lastInsert])->get();
                        array_push($insertEmployees,$employee);
                    }

                }
            }
              
            return response()->json([
                "success" => 200,
                "message" => "File successfully uploaded",
                "result" => $insertEmployees
            ]);
  
        }
    }

    public function adminLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    "success" => 200,
                    "message" => "Login successfully"
                ]);
            } else {
                return response()->json([
                    "success" => 422,
                    "message" => "Password mismatch"
                ]);
            }
        } else {
            return response()->json([
                "success" => 422,
                "message" => "User does not exist"
            ]);
        }
    }


}
