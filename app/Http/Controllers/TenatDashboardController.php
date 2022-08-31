<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginDetails;

class TenatDashboardController extends Controller
{
    public function downloadFile(Request $request){
        $token = $request->header('access_token');
        $customer = LoginDetails::where(['access_token' => $token])->with('customer')->get();
        if($customer[0]->customer->role_id!=1) {
            return[
                'status' => 401,
                'message' => 'No Access for fault'
            ];
        }
        $file = public_path(). "/excel/example_sheet.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );
        $name = 'exampleSheet.xlsx';
        return response()->download($file, $name, $headers);
    }
}
