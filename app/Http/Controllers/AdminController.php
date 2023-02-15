<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointments;

class AdminController extends Controller
{
    function show()
    {
        $data = Appointments::all();
        return view('admin', ['data'=>$data]);
    }

    public function delete(Request $request)
    {
    	if($request->ajax())
    	{
    		if($request->type == 'delete')
    		{
    			$event = Appointments::find($request->id)->delete();

    			return response()->json($event);
    		}
    	}
    }
}
