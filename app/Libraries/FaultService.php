<?php

namespace App\Libraries;


use App\Models\Customers;
use App\Models\EventLog;
use App\Models\LoginDetails;
use App\Models\FaultCategory;
use App\Models\FaultReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FaultService {
    public function storeEquipmental($item)
    {

        $customer = LoginDetails::where(['access_token' => $item->token])->with('customer')->get();
        if($customer[0]->customer->role_id!=1) {
            return[
                'status' => 401,
                'message' => 'No Access for fault'
            ];
        }
        if (!Storage::disk('public')->has('fault')){
            Storage::disk('public')->makeDirectory('fault');
        }
        $imageFiles = [];
        $files = $item->Images;
        foreach($files as $mediaFiles) {
            $file_encode = Str::random(20);
            $upload_file = $mediaFiles;
            $extension = $upload_file->getClientOriginalExtension();
            $upload_filename = time() . '_' . $file_encode . '.' . $extension;
            $upload_file->storeAs('fault', $upload_filename);
            array_push($imageFiles, $upload_filename);
        }
        $ticketIdIncrement = 1;
        $ticketId = FaultReport::max('ticket_id');
        if(!empty($ticketId)) {
            $ticketIdIncrement = $ticketId + 1;
        }
        else {
            $ticketIdIncrement = $ticketIdIncrement;
        }

        $create = new FaultReport();
        $create->level = $item->level;
        $create->location = $item->location;
        $create->title = $item->title;
        $create->description = $item->description;
        $create->Images = json_encode($imageFiles);
        $create->fault_category_id = $item->category_id;
        $create->status = 0;
        $create->ticket_id = $ticketIdIncrement;
        $create->save();
        $lastInsert = $create->id;
        $result = FaultReport::where(['id' => $lastInsert])->with('fault_category')->get();
        $item = [
            'level' => $result[0]->level,
            'location' => $result[0]->location,
            'title' => $result[0]->title,
            'description' => $result[0]->description,
            'Images' => $result[0]->Images,
            'category' => $result[0]->fault_category->name,
            'status' => $result[0]->status,
            'ticket_id' => $result[0]->ticket_id
        ];

        $resultOutput = [
            "status" => 200,
            'data' => $item
        ];
        return $resultOutput;
    }
    public function getAll($token) {

        $customer = LoginDetails::where(['access_token' => $token])->with('customer')->get();
        if($customer[0]->customer->role_id!=1) {
            return[
                'status' => 401,
                'message' => 'No Access for fault'
            ];
        }
        $result = FaultReport::orderByDesc('id')->with('fault_category')->get();
        return response()->json([
            "status" => 200,
            "result" => $result
        ]);
    }
    public function draft($token) {
        $customer = LoginDetails::where(['access_token' => $token])->with('customer')->get();
        if($customer[0]->customer->role_id!=1) {
            return[
                'status' => 401,
                'message' => 'No Access for fault'
            ];
        }
        $result = FaultReport::orderByDesc('id')->where('status',1)->with('fault_category')->get();
        
        return response()->json([
            "status" => 200,
            "result" => $result
        ]);
    }
    public function getId($id,$token) {
        $customer = LoginDetails::where(['access_token' => $token])->with('customer')->get();
        if($customer[0]->customer->role_id!=1) {
            return[
                'status' => 401,
                'message' => 'No Access for fault'
            ];
        }
        $result = FaultReport::orderByDesc('id')->where('id',$id)->with('fault_category')->get();
          
        $res = json_decode($result,true);
        if(sizeof($res)==0){
            return response()->json([
                "status" => 404,
                "result" => 'Not Found'
            ]);
        }  
        else {
            return response()->json([
                "status" => 200,
                "result" => $result
            ]);
        }
    }
    public function getCategoryList($category_id,$token) {
        $customer = LoginDetails::where(['access_token' => $token])->with('customer')->get();
        if($customer[0]->customer->role_id!=1) {
            return[
                'status' => 401,
                'message' => 'No Access for fault'
            ];
        }
        $result = FaultReport::orderByDesc('id')->where('fault_category_id',$category_id)->with('fault_category')->get();
        
        return response()->json([
            "status" => 200,
            "result" => $result
        ]);
    }
}