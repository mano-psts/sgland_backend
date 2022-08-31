<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FaultRequest;
use App\Libraries\FaultService;

class FaultReportController extends Controller
{
    public function equipmental(FaultRequest $request, FaultService $service)
    {
        $request['category_id'] = 1;
        $request['token'] = $request->header('access_token');
        $equipmental = $service->storeEquipmental($request);
              
        return response()->json($equipmental);
    }
    public function structural(FaultRequest $request, FaultService $service)
    {
        $request['category_id'] = 2;
        $request['token'] = $request->header('access_token');
        $equipmental = $service->storeEquipmental($request);             
        
        return response()->json($equipmental);
    }
    public function toilet(FaultRequest $request, FaultService $service)
    {
        $request['category_id'] = 4;
        $request['token'] = $request->header('access_token');
        $equipmental = $service->storeEquipmental($request);
              
        
        return response()->json($equipmental);
    }
    public function other(FaultRequest $request, FaultService $service)
    {
        $request['category_id'] = 3;
        $request['token'] = $request->header('access_token');
        $equipmental = $service->storeEquipmental($request);              
        
        return response()->json($equipmental);
    }

    public function getAll(Request $request,FaultService $service)
    {
        $token['token'] = $request->header('access_token');
        $getAll = $service->getAll($token);  
        return response()->json($getAll);  
        
    }
    public function draft(Request $request,FaultService $service)
    {
        $token['token'] = $request->header('access_token');
        $draft = $service->draft($token);   
        return response()->json($draft);  
    }
    public function getId($id,Request $request,FaultService $service)
    {
        $token['token'] = $request->header('access_token');
        $getId = $service->getId($id,$token);
        return response()->json($getId);  
        
    }
    public function getEquipmental(Request $request,FaultService $service)
    {
        $token['token'] = $request->header('access_token');
        $category_id = 1;
        $equipmental = $service->getCategoryList($category_id, $token);              
        return response()->json($equipmental);  
    }
    public function getStructural(Request $request,FaultService $service)
    {

        $token['token'] = $request->header('access_token');
        $category_id = 2;
        $equipmental = $service->getCategoryList($category_id, $token);              
        return response()->json($equipmental); 
    }
    public function getToilet(Request $request,FaultService $service)
    {

        $token['token'] = $request->header('access_token');
        $category_id = 4;
        $equipmental = $service->getCategoryList($category_id, $token);              
        return response()->json($equipmental); 
    }
    public function getOther(Request $request,FaultService $service)
    {
        $token['token'] = $request->header('access_token');
        $category_id = 3;
        $equipmental = $service->getCategoryList($category_id, $token);              
        return response()->json($equipmental); 
    }
}
