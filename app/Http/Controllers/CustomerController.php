<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Customer;
use App\Http\Requests\OnBoard;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\FirstTimeLogin;
use App\Http\Requests\Login;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\VerifyOtpRequest;

class CustomerController extends Controller
{
    public function onBoard(OnBoard $request, Customer $customer)
    {
        $result = $customer->onBoard($request);
        if($result){           
            return response()->json([
                "status" => 200,
                "message" => "Onboarded successfully"
            ]);
        }
        else {
            return response()->json(['status' => 400, 'Message' => 'Already exists']);
        }
    }
    public function checkVerified(Request $request, Customer $customer)
    {
        $email= $request->email;
        $result = $customer->checkVerified($email);
        if($result){           
            return response()->json([
                "status" => 200,
                "data" => $result
            ]);
        }
        else {
            return response()->json(['status' => 404, 'Message' => 'Email id not exists']);
        } 
    }
    public function firstTimelogin(FirstTimeLogin $request, Customer $customer) {
        $result = $customer->firstTimelogin($request);    
        return response()->json([
            "result" => $result
        ]);        
    }
    public function login(Login $request, Customer $customer) {
        $result = $customer->login($request);     
        return response()->json([
            "result" => $result
        ]);        
    }
    public function logout(Request $request){  
        $request->session()->flush();
        $request->session()->regenerate(); 
        $result = $customer->logout();                
        return response()->json([
            "status" => 200,
            "message" => "Logout Successfully!!"
        ]);    
    }

    public function forgotPassword(ForgotPasswordRequest $request, Customer $customer) {
        $result = $customer->forgotPassword($request);         
        return response()->json([
            "result" => $result
        ]);  
    }

    public function requestOtp(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->messages()]);
        }
        $result = $customer->requestOtp($request);         
        return response()->json([
            "result" => $result
        ]);  
    }
    public function verifyOtp(VerifyOtpRequest $request, Customer $customer)
    {
        $result = $customer->verifyOtp($request);         
        return response()->json([
            "result" => $result
        ]);  

    }
}
