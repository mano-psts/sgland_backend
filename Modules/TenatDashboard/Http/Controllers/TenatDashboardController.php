<?php

namespace Modules\TenatDashboard\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TenatDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('tenatdashboard::index');
    }

    public function downloadFile(){
        $file = public_path(). "/excel/example_sheet.xlsx";

        $headers = array(
            'Content-Type: application/xlsx',
        );
        $name = 'exampleSheet.xlsx';
        return response()->download($file, $name, $headers);
    }
}
