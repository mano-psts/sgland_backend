<?php

namespace Modules\Tenat\Services;

use Modules\Tenat\Entities\Tenat;
use Modules\Tenat\Entities\TenatLoginDetails;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Session;
use Carbon\Carbon;
use Mail;

class TenatLoginService {
    public function firstTimelogin($item) {
        $resultTenat = Tenat::where(['email' => $item->email])->get();
        
        $res = json_decode($resultTenat,true);
        if(sizeof($res)==0){
            $result = [
                "status" => 404,
                'Message' => 'Tenat Account Not Exists'
            ];
            return $result;
        }
        else {
            if($resultTenat[0]->is_verified==1) {
                $result = [
                    "status" => 400,
                    'Message' => 'Tenat Account is already verfied'
                ];
                return $result;
            }
            if (Hash::check($item->password,$resultTenat[0]->password)){
                $Tenat = Tenat::where('email','=',$item->email)->update([
                    'password' => crypt::encrypt($item->new_password),
                    'enable_face_login' => $item->enable_face_login,
                    'is_verified' => 1
                ]);

                if($item->save_account) {
                    Session::put('tenatId',$resultTenat[0]->id);
                }
                $tenatDetails = new TenatLoginDetails;
                $tenatDetails->tenat_id = $resultTenat[0]->id; 
                $tenatDetails->login = Carbon::now();
                $tenatDetails->save();
                $result = [
                    "status" => 200,
                    'Message' => 'Login Successfully!'
                ];
                return $result;
            }
            else {
                $result = [
                    "status" => 400,
                    'Message' => 'Wrong password, please re-enter.'
                ];
                return $result;
            }
        }
    }
    public function login($item) {
        $resultTenat = Tenat::where(['email' => $item->email])->get();
        
        $res = json_decode($resultTenat,true);
        if(sizeof($res)==0){
            $result = [
                "status" => 404,
                'Message' => 'Tenat Account Not Exists'
            ];
            return $result;
        }
        else {
            if($resultTenat[0]->is_verified==0) {
                $result = [
                    "status" => 400,
                    'Message' => 'Tenat Account is not verfied. Please login via url sending your mail.'
                ];
                return $result;
            }
            $encrypted_password = $resultTenat[0]->password;
            $decrypted_password = crypt::decrypt($encrypted_password);
            if($decrypted_password==$item->password) {
                if($item->save_account) {
                    Session::put('tenatId',$resultTenat[0]->id);
                }
                $resultTenatDetails = TenatLoginDetails::where(['tenat_id' => $resultTenat[0]->id])->latest()->get()->first();
                $res1 = json_decode($resultTenatDetails,true);
                if(sizeof($res1)==0){
                    $tenatDetails = new TenatLoginDetails;
                    $tenatDetails->tenat_id = $resultTenat[0]->id; 
                    $tenatDetails->login = Carbon::now();
                    $tenatDetails->save();
                }
                else {
                    if(isset($resultTenatDetails[0]->logoff)) {
                        TenatLoginDetails::where('id','=',$resultTenatDetails[0]->id)->update([
                            'logoff' => Carbon::now()
                        ]);
                        $tenatDetails = new TenatLoginDetails;
                        $tenatDetails->tenat_id = $resultTenat[0]->id; 
                        $tenatDetails->login = Carbon::now();
                        $tenatDetails->save();
                        if($item->phone_number) {
                            $Tenat = Tenat::where('id','=',$resultTenat[0]->id)->update([
                                'phone_number' => $item->phone_number
                            ]);
                        }
                        $result = [
                            "status" => 200,
                            'Message' => 'Logout previous account and Login new account Successfully!'
                        ];
                        return $result;
                    }
                    else {
                        $tenatDetails = new TenatLoginDetails;
                        $tenatDetails->tenat_id = $resultTenat[0]->id; 
                        $tenatDetails->login = Carbon::now();
                        $tenatDetails->save();
                        $result = [
                            "status" => 200,
                            'Message' => 'Login Successfully!'
                        ];
                        return $result;
                    }
                }
            }
            else {
                $result = [
                    "status" => 400,
                    'Message' => 'Wrong password, please re-enter.'
                ];
                return $result;
            }
        }
    }
    public function logoff() {
        $tenatId = Session::get('tenatId');
        $resultTenatDetails = TenatLoginDetails::where(['tenat_id' => $tenatId])->latest()->get()->first();
        TenatLoginDetails::where('id','=',$resultTenatDetails[0]->id)->update([
            'logoff' => Carbon::now()
        ]);
    }
    public function forgotPasswordTenat($item) {
        $result = Tenat::where(['email' => $item->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            $result = [
                'status' => 404, 
                'Message' => 'Tenot Not Exists'
            ];
            return $result;
        }
        else {
            $encrypted_password = crypt::encrypt($item->password);
            $result = Tenat::where("email", $item->email)->update(["password" => $encrypted_password]);
            $result1 = [
                'status' => 200, 
                'Message' => 'password Update successfully'
            ];
            return $result1;
        }
    }
    public function requestOtp($item)
    {
        $otp = rand(100000,999999);
        $result = Tenat::where(['email' => $item->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            $output = [
                'status' => 404, 
                'Message' => 'Tenat Not Exists'
            ];
            return $output;
        }
        else {
            $validationTime = Carbon::now()->addMinutes(3);
            $tenat = Tenat::where('email','=',$item->email)->update(['otp' => $otp,'validate_time' => $validationTime]);

            $mail_details['otp'] = $otp;
            $mail_details['name'] = $result[0]->name;
            $email = $item->email;
        
            Mail::send('mail.requestLoginVisitorOtp', $mail_details, function($message) use ($email) {
                $message->to($email)->subject
                   ('OTP for singland');
            });
            $output = [
                "status" => 200, 
                "message" => "OTP sent successfully"
            ];
            return $output;
        }
    }
    public function verifyOtp($item)
    {
        $result = Tenat::where(['email' => $item->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            $output = [
                'status' => 404, 
                'Message' => 'Tenat Not Exists'
            ];
            return $output;
        }
        else {
            $now = Carbon::now();
            if ($result[0]['validate_time'] >= $now) {
                $output = [
                    'status' => 400, 
                    'Message' => 'Time Expired'
                ];
                return $output;
            }
            else {
                $otp = $item->otp;
                if($otp==$result[0]['otp']) {
                    $visitor = Tenat::where('email','=',$item->email)->update(['otp' => NULL,'validate_time' => NULL]);
                    $output = [
                        'status' => 200, 
                        'Message' => 'OTP Verified'
                    ];
                    return $output;
                }
                else {
                    $output = [
                        'status' => 400, 
                        'Message' => 'OTP Mismatch'
                    ];
                    return $output;
                }
            }
        }

    }
}