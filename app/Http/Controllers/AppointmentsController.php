<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointments;
use Illuminate\Support\Facades\Auth;


class AppointmentsController extends Controller
{
    public function index(Request $request)
    {
			$userId = Auth::id();
    		$data = Appointments::where('user_id','=',$userId)
                       ->get(['id', 'event_name AS title', 'event_start AS start', 'event_end AS end', 'user_id']);
			return view("dashboard",compact('data'));
    }

    public function action(Request $request)
    {
    	if($request->ajax())
    	{
    		if($request->type == 'add')
    		{
    			$event = Appointments::create([
    				'event_name'		=>	$request->event_name,
					'event_date'		=>  $request->event_date, 
    				'event_start'		=>	$request->event_start,
    				'event_end'		    =>	$request->event_end,
					'user_id'			=> 	$request->user_id
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'update')
    		{
    			$event = Appointments::find($request->id)->update([
    				'event_name'		=>	$request->event_name,
					'event_date'		=>  $request->event_date,
    				'event_start'		=>	$request->event_start,
    				'event_end'		    =>	$request->event_end,
					'user_id'			=> 	$request->user_id
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'delete')
    		{
    			$event = Appointments::find($request->id)->delete();

    			return response()->json($event);
    		}
    	}
    }
}
