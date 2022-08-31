<?php

namespace App\Libraries;

use Mail;
use App\Models\Customers;
use App\Models\EventLog;
use App\Models\LoginDetails;
use Illuminate\Support\Facades\Crypt;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Customer {
    public function onBoard($item) {
        $result = Customers::where(['email' => $item->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            $customer = new Customers;
            $customer->email = $item->email;
            $customer->role_id = $item->role_id;
            $customer->save();
            $lastInsert = $customer->id;

            if($lastInsert) {
                $event = new EventLog;
                $event->action = "Add"; 
                $event->role = "Super Admin"; 
                $event->message = "Add Customer"; 
                $event->status = 200;
                $event->save();
            }

            $email = $item->email;
            $mail_details['email'] = $item->email;
        
            Mail::send('mail.onBoard', $mail_details, function($message) use ($email) {
                $message->to($email)->subject
                   ('Onboard Successfully!');
            });
            return true;
        }
        else {
            return false;
        }
    }
    public function checkVerified($item) {
        $result = Customers::where(['email' => $item])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            return false;
        }
        else {
            $resultItem = [
                'name' => $result[0]->name,
                'verified' => $result[0]->verified,
                'email' => $result[0]->email
            ];
            return $resultItem;
        }
    }
    public function firstTimelogin($item) {
        $resultCustomers = Customers::where(['email' => $item->email])->get();
        
        $res = json_decode($resultCustomers,true);
        if(sizeof($res)==0){
            $result = [
                "status" => 404,
                'Message' => 'Customers Account Not Exists'
            ];
            return $result;
        }
        else {
            if($resultCustomers[0]->verified==1) {
                $result = [
                    "status" => 400,
                    'Message' => 'Customers Account is already verfied'
                ];
                return $result;
            }
            $Customers = Customers::where('email','=',$item->email)->update([
                'password' => crypt::encrypt($item->password),
                'name' => $item->name,
                'last_name' => $item->last_name,
                'verified' => 1
            ]);

            if($item->save_account) {
                Session::put('customerId',$resultCustomers[0]->id);
            }
            $agent = new \Jenssegers\Agent\Agent;   
            $is_mobile = $agent->isMobile();
            $token = Str::random(60);
            $customerDetails = new LoginDetails;
            $customerDetails->customer_id = $resultCustomers[0]->id; 
            $customerDetails->login = Carbon::now();
            $customerDetails->is_mobile = $is_mobile;
            $customerDetails->access_token = $token;
            $customerDetails->save();
            $result = [
                "status" => 200,
                "token" => $token,
                'Message' => 'Login Successfully!'
            ];
            return $result;
        }
    }
    public function login($item) {
        $resultCustomers = Customers::where(['email' => $item->email])->get();
        
        $res = json_decode($resultCustomers,true);
        if(sizeof($res)==0){
            $result = [
                "status" => 404,
                'Message' => 'Customer Account Not Exists'
            ];
            return $result;
        }
        else {
            if($resultCustomers[0]->verified==0) {
                $result = [
                    "status" => 400,
                    'Message' => 'Customer Account is not verfied. Please login via url sending your mail.'
                ];
                return $result;
            }
            $encrypted_password = $resultCustomers[0]->password;
            $decrypted_password = crypt::decrypt($encrypted_password);
            if($decrypted_password==$item->password) {
                if($item->save_account) {
                    Session::put('customerId',$resultCustomers[0]->id);
                }
                $resultCustomerDetails = LoginDetails::where(['customer_id' => $resultCustomers[0]->id])->latest()->get()->first();
                $res1 = json_decode($resultCustomerDetails,true);
                if(sizeof($res1)==0){
                    return "true";
                    $agent = new \Jenssegers\Agent\Agent;   
                    $is_mobile = $agent->isMobile();
                    $token = Str::random(60);
                    $customerDetails = new LoginDetails;
                    $customerDetails->customer_id = $resultCustomers[0]->id; 
                    $customerDetails->login = Carbon::now();
                    $customerDetails->is_mobile = $is_mobile;
                    $customerDetails->access_token = $token;
                    $customerDetails->save();
                    $result = [
                        "status" => 200,
                        "token" => $token,
                        'Message' => 'Login Successfully!'
                    ];
                    return $result;
                }
                else {
                    if($resultCustomerDetails->logoff==null) {
                        LoginDetails::where('id','=',$resultCustomerDetails->id)->update([
                            'logoff' => Carbon::now()
                        ]);
                        $agent = new \Jenssegers\Agent\Agent;   
                        $is_mobile = $agent->isMobile();
                        $token = Str::random(60);
                        $customerDetails = new LoginDetails;
                        $customerDetails->customer_id = $resultCustomers[0]->id; 
                        $customerDetails->login = Carbon::now();
                        $customerDetails->is_mobile = $is_mobile;
                        $customerDetails->access_token = $token;
                        $customerDetails->save();
                        if($item->phone_number) {
                            $customer = Customers::where('id','=',$resultCustomer[0]->id)->update([
                                'phone_number' => $item->phone_number
                            ]);
                        }
                        $result = [
                            "status" => 200,
                            "token" => $token,
                            'Message' => 'Logout previous account and Login new account Successfully!'
                        ];
                        return $result;
                    }
                    else {
                        $agent = new \Jenssegers\Agent\Agent;   
                        $is_mobile = $agent->isMobile();
                        $token = Str::random(60);
                        $customerDetails = new LoginDetails;
                        $customerDetails->customer_id = $resultCustomers[0]->id; 
                        $customerDetails->login = Carbon::now();
                        $customerDetails->is_mobile = $is_mobile;
                        $customerDetails->access_token = $token;
                        $customerDetails->save();
                        $result = [
                            "status" => 200,
                            "token" => $token,
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
    public function logout() {
        $customerId = Session::get('customerId');
        $resultCustomerDetails = LoginDetails::where(['customer_id' => $customerId])->latest()->get()->first();
        LoginDetails::where('id','=',$resultCustomerDetails->id)->update([
            'logoff' => Carbon::now()
        ]);
    }
    public function forgotPassword($item) {
        $result = Customers::where(['email' => $item->email])->get();
        
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
            $result = Customers::where("email", $item->email)->update(["password" => $encrypted_password]);
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
        $result = Customers::where(['email' => $item->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            $output = [
                'status' => 404, 
                'Message' => 'Customers Not Exists'
            ];
            return $output;
        }
        else {
            $validationTime = Carbon::now()->addMinutes(3);
            $Customers = Customers::where('email','=',$item->email)->update(['otp' => $otp,'validate_time' => $validationTime]);

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
        $result = Customers::where(['email' => $item->email])->get();
        
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            $output = [
                'status' => 404, 
                'Message' => 'Customers Not Exists'
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
                    $visitor = Customers::where('email','=',$item->email)->update(['otp' => NULL,'validate_time' => NULL]);
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