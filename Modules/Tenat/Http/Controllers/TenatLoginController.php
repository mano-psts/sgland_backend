<?php

namespace Modules\Tenat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Tenat\Http\Requests\LoginRequest;
use Modules\Tenat\Http\Requests\TenatNewLoginRequest;
use Modules\Tenat\Http\Requests\ForgotPasswordTenatRequest;
use Modules\Tenat\Http\Requests\VerifyOtpTenatRequest;
use Modules\Tenat\Services\TenatLoginService;
use Illuminate\Support\Facades\Validator;

class TenatLoginController extends Controller
{
    public function firstTimelogin(LoginRequest $request, TenatLoginService $service) {
        $result = $service->firstTimelogin($request);    
        return response()->json([
            "result" => $result
        ]);        
    }
    public function login(TenatNewLoginRequest $request, TenatLoginService $service) {
        $result = $service->login($request);     
        return response()->json([
            "result" => $result
        ]);        
    }

    public function logout(Request $request){  
        $request->session()->flush();
        $request->session()->regenerate(); 
        $result = $service->logout();                
        return response()->json([
            "status" => 200,
            "message" => "Logout Successfully!!"
        ]);    
    }

    public function forgotPasswordTenat(ForgotPasswordTenatRequest $request, TenatLoginService $service) {
        $result = $service->forgotPasswordTenat($request);         
        return response()->json([
            "result" => $result
        ]);  
    }

    public function requestOtp(Request $request, TenatLoginService $service)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->messages()]);
        }
        $result = $service->requestOtp($request);         
        return response()->json([
            "result" => $result
        ]);  
    }
    public function verifyOtp(VerifyOtpTenatRequest $request, TenatLoginService $service)
    {
        $result = $service->verifyOtp($request);         
        return response()->json([
            "result" => $result
        ]);  

    }
}
