<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarType;
use App\Models\City;
use App\Models\Appointment;
use App\Models\User;
use Auth;
use Event as mycalendarevent;
use Carbon;

class AppointmentController extends Controller
{
    
    public function index(Request $request){
         if($request->ajax()){
            if(Auth::user()->hasRole('Admin')){
                $query = Appointment::orderby('id', 'desc')->where('id', '>', 0);
            }elseif(Auth::user()->hasRole('EPC Developer')){
                $query = Appointment::orderby('id', 'desc')->where('customer_id', Auth::user()->id)->where('id', '>', 0);
            }
            if($request['search'] != ""){
				$custmers = User::where('name', 'like', '%'. $request['search'] .'%')->get(['id']);
				
                $query->whereIn('customer_id', $custmers);
                    // ->orWhere('product_slug', 'like', '%'. $request['search'] .'%')
                    // ->orWhere('trip_start_date', 'like', '%'. $request['search'] .'%');
            }
            if($request['status']!="All"){
                //if($request['status']==3){
                  //  $request['status'] = 3;
                //}
                $query->where('status', $request['status']);
            }
            $models = $query->paginate(10);
            return (string) view('website.appointment.search', compact('models'));
        }
        $page_title = 'All Appointments';
        if(Auth::user()->hasRole('Admin')){
            $models = Appointment::orderby('id', 'desc')->paginate(10);
        }elseif(Auth::user()->hasRole('EPC Developer')){
            $models = Appointment::orderby('id', 'desc')->where('customer_id', Auth::user()->id)->paginate(10);
        }
        
        return View('website.appointment.index', compact("models", "page_title"));
    }
    
    public function create()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }

        $cartypes = CarType::where('status',1)->get();
		$cities = City::where('time_zone' , '!=' , 'null')->get();
        return view('website.appointment.create', compact('cartypes' , 'cities'));
    }

    public function store(Request  $request)
    {
        $validator = $request->validate([
            'pickup_date' => 'required',
            'pickup_time' => 'required',
            'description' => 'required|max:1000',
        ]);

        /*$event = new mycalendarevent;
        $event->name = $request->description;
        $event->startDateTime = Carbon\Carbon::now();
        $event->endDateTime = Carbon\Carbon::now()->addHour();
        $event->save();*/
        /* $e = Event::get();
        dd($e) */

        $model = new Appointment();
        $model->customer_id = Auth::user()->id;
        $model->pickup_date = $request->pickup_date;
		$model->time_zone = $request->time_zone;
        $model->pickup_time = $request->pickup_time;
        $model->description = $request->description;
        $model->save();
        return redirect()->route('book-appointment')->with('success', 'Appointment resquest sent successfully');
    }

    public function appointmentDetail()
    {
      $page_title = 'All Appointment Details';
      $appointments = Appointment::where('customer_id', Auth::user()->id)->get();      
      return view('website.user-dashboard.appointment-detail', compact('appointments' , 'page_title'));
    }
    
    public function status(Request $request)
    {	//return $request;
	$appointment_status = Appointment::where('id', $request->booking_number)->first();
    $appointment_status->status = $request->status;
    $appointment_status->update();
    return redirect()->back()->with('message' , 'Status Update Successfully');
    }
    
    public function show($id)
    {
        $appointment = Appointment::where('id', $id)->first();
        $page_title = 'Appointment Details';
        return view('website.appointment.show', compact('appointment', 'page_title'));
    }
	
	 public function appointmentReview(Request $request)
    {	
        $reviews = Appointment::where('customer_id', $request->appointmentss)->first();
        $reviews->review = $request->review;
		$reviews->comment = $request->comment;
        $reviews->update();
        
        return redirect()->back()->with('message','Review Added Successfully');
    }

}

