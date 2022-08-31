<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\SuperAdminService;
use App\Http\Requests\AddTenat;

class SuperAdminController extends Controller
{ 
    public function addTenat(AddTenat $request, SuperAdminService $service)
    {


        $result = $service->addTenat($request);

        if($result){           
            return response()->json([
                "status" => 200,
                "message" => "Tenat Added successfully",
                "result" => $result
            ]);
        }
        else {
            return response()->json(['status' => 400, 'Message' => 'Already exists']);
        }
    }
}
