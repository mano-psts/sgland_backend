<?php

namespace Modules\Tenat\Services;

use Modules\Tenat\Entities\FaultReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FaultService {
    public function storeEquipmental($item)
    {
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
        return $item;
    }

    public function getAll() {
        $result = FaultReport::orderByDesc('id')->with('fault_category')->get();
        return $result;
    }
    public function draft() {
        $result = FaultReport::orderByDesc('id')->where('status',1)->with('fault_category')->get();
        return $result;
    }
    public function getId($id) {
        $result = FaultReport::orderByDesc('id')->where('id',$id)->with('fault_category')->get();
        return $result;
    }
    public function getCategoryList($category_id) {
        $result = FaultReport::orderByDesc('id')->where('fault_category_id',$category_id)->with('fault_category')->get();
        return $result;
    }
}