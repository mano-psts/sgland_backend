<?php

namespace Modules\Tenat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Tenat\Http\Requests\FaultRequest;
use Modules\Tenat\Services\FaultService;

class FaultController extends Controller
{
    public function equipmental(FaultRequest $request, FaultService $service)
    {
        $request['category_id'] = 1;
        $equipmental = $service->storeEquipmental($request);
              
        return response()->json([
            "status" => 200,
            "message" => "Submit successfully",
            "result" => $equipmental
        ]);
    }
    public function structural(FaultRequest $request, FaultService $service)
    {
        $request['category_id'] = 2;
        $equipmental = $service->storeEquipmental($request);
              
        return response()->json([
            "status" => 200,
            "message" => "Submit successfully",
            "result" => $equipmental
        ]);
    }
    public function toilet(FaultRequest $request, FaultService $service)
    {
        $request['category_id'] = 4;
        $equipmental = $service->storeEquipmental($request);
              
        return response()->json([
            "status" => 200,
            "message" => "Submit successfully",
            "result" => $equipmental
        ]);
    }
    public function other(FaultRequest $request, FaultService $service)
    {
        $request['category_id'] = 3;
        $equipmental = $service->storeEquipmental($request);
              
        return response()->json([
            "status" => 200,
            "message" => "Submit successfully",
            "result" => $equipmental
        ]);
    }

    public function getAll(FaultService $service)
    {
        $getAll = $service->getAll();    
        return response()->json([
            "status" => 200,
            "result" => $getAll
        ]);
    }
    public function draft(FaultService $service)
    {
        $draft = $service->draft();    
        return response()->json([
            "status" => 200,
            "result" => $draft
        ]);
    }
    public function getId($id,FaultService $service)
    {
        $getId = $service->getId($id);
        $res = json_decode($getId,true);
        if(sizeof($res)==0){
            return response()->json([
                "status" => 404,
                "result" => 'Not Found'
            ]);
        }  
        else {
            return response()->json([
                "status" => 200,
                "result" => $getId
            ]);
        }
    }
    public function getEquipmental(FaultService $service)
    {
        $category_id = 1;
        $equipmental = $service->getCategoryList($category_id);              
        return response()->json([
            "status" => 200,
            "result" => $equipmental
        ]);
    }
    public function getStructural(FaultService $service)
    {
        $category_id = 2;
        $equipmental = $service->getCategoryList($category_id);              
        return response()->json([
            "status" => 200,
            "result" => $equipmental
        ]);
    }
    public function getToilet(FaultService $service)
    {
        $category_id = 4;
        $equipmental = $service->getCategoryList($category_id);              
        return response()->json([
            "status" => 200,
            "result" => $equipmental
        ]);
    }
    public function getOther(FaultService $service)
    {
        $category_id = 3;
        $equipmental = $service->getCategoryList($category_id);              
        return response()->json([
            "status" => 200,
            "result" => $equipmental
        ]);
    }
    
}
