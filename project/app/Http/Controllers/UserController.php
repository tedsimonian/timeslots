<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\CustomEvent;
use App\Event;
use App\GoogleCalendar;
use App\GoogleCustomClient;
use App\Jobs\CheckPendingEvents;
use App\Mail\AppointmentPaidEmployee;
use App\Mail\AppointmentPaidUser;
use App\Mail\AppointmentPayLaterEmployee;
use App\Mail\AppointmentPayLaterUser;
use App\Profile;
use App\Schedule;
use App\SpecialSchedule;
use App\SpecialTimeslot;
use App\Timeslot;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /*
     *
     * Constructor
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     * Homepage view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function home(){


        return view('home_user')->with('vue',true)->with('stripeKey',env('STRIPE_PUB_KEY'));
    }

    /**
     *
     * Book appointment view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function bookAppointmentView(){


        return view('layouts.user.book_appointment')->with('vue',true)->with('stripeKey',env('STRIPE_PUB_KEY'));
    }


    /**
     *
     * Check if user has the permission to pay.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPermission(){


        return response()->json(array('success'=>true,'message'=>Auth::user()->can('pay')));

    }


    /**
     *
     * User Profile view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function profileView(){

        return view('layouts.user.profile')->with('vue',true);
    }

    /**
     *
     * Get User data for Profile edit.
     *
     * @param $id
     * @return mixed
     */
    public function getUser($id){

        $user=User::where('id','=',$id)->with('profile')->first();

        $roles=$user->roles()->get();


        $user->role=$roles[0]->name;

        if($user->profile){

            if($user->profile->access_token!=null){

                $user->gcalendar_auth=true;

            }else{

                $user->gcalendar_auth=false;
            }
        }else{

            $user->gcalendar_auth=false;
        }



        return $user;
    }


    /**
     *
     * Update profile.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile($id,Request $request){

        $this->validate($request,[

            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:users,email,'.$id

        ]);

        $user=User::find($id);
        $user->first_name=$request['first_name'];
        $user->last_name=$request['last_name'];
        $user->email=$request['email'];



        if($request['password']!=''){

            $user->password=bcrypt($request['password']);
        }
        $user->save();


        Profile::updateOrCreate(
            ['user_id'=>$user->id],
            ['address'=>$request['address'],'city'=>$request['city'],'state'=>$request['state'],
                'postal_code'=>$request['postal_code'],'notifications'=>$request['notifications'],'gcalendar'=>$request['gcalendar']]

        );



        return response()->json(array('success'=>true,'message'=>'Profile updated!'));


    }

    /**
     *
     * Get all employees.
     *
     * @return mixed
     */

    public function getEmployees(){


        $employees=User::role('employee')->get();

        foreach ($employees as $employee) {

            $employee->name=$employee->first_name.' '.$employee->last_name;
        }

        return $employees;

    }


    /**
     *
     * Get Employee days availability.
     *
     * @param $id
     * @return array
     *
     */
    public function getCalendarAvailability($id){


        $schedule=Schedule::where('user_id','=',$id)->with('timeslots')->first();


        $disabled_weekdays=[];
        if(!$schedule->sunday_available){
            $disabled_weekdays[]=1;
        }

        if(!$schedule->monday_available){
            $disabled_weekdays[]=2;
        }
        if(!$schedule->tuesday_available){
            $disabled_weekdays[]=3;
        }
        if(!$schedule->wednesday_available){
            $disabled_weekdays[]=4;
        }
        if(!$schedule->thursday_available){
            $disabled_weekdays[]=5;
        }
        if(!$schedule->friday_available){
            $disabled_weekdays[]=6;
        }
        if(!$schedule->saturday_available){
            $disabled_weekdays[]=7;
        }


        $special_schedule=SpecialSchedule::where('user_id','=',$id)->get();

        $available_days=[];
        $disabled_days=[];



        foreach ($special_schedule as $item){


            if($item['available']){

                $available_days[]=$item['day'];
            }else{

                $disabled_days[]=$item['day'];
            }



        }

        $availability=array('disabled_weekdays'=>$disabled_weekdays,'available_days'=>$available_days,'disabled_days'=>$disabled_days);


        return $availability;

    }


    /**
     *
     * Get employee calendar data. (price,increment,duration)
     *
     * @param $id
     * @return mixed
     *
     */
    public function getCalendarInfo($id){

        $calendar=Calendar::where('user_id','=',$id)->first();

        return $calendar;

    }


    /**
     *
     * Get days that are fully booked.
     *
     * @param Request $request
     * @return array
     *
     */
    public function getFullBookings(Request $request){


        $events=Event::select(DB::raw('"event" as type'),'day as date',DB::raw('COUNT("day") as count'))->where('employee_id','=',$request['id'])->distinct('date')->groupBy('day')->get()->toArray();
        $custom_events=CustomEvent::select(DB::raw('"custom_event" as type'),'day as date',DB::raw('COUNT("day") as count'))->where('employee_id','=',$request['id'])->distinct('date')->groupBy('day')->get()->toArray();
        $events=array_merge($events,$custom_events);

        $days_markers=[];

        $counter=0;

        $empty_request=new Request();
        foreach($events as $event){

            $counter++;

            $customRequest=array('id'=>$request['id'],'day'=>$event['date'],'increment'=>$request['increment'],'event_duration'=>$request['event_duration'],'weekday'=>date('N',strtotime($event['date'])),'today'=>$request['today']);

            $availability=$this->getHourAvailability($empty_request,$customRequest);

            if(empty($availability['availability'])){

                $days_markers[]=array('id'=>$counter,'date'=>$event['date'],'backgroundColor'=>'red','borderColor'=>'red','color'=>'green');
            }
        }

        return array('days_markers'=>$days_markers);



    }

    /**
     *
     * Get timeslots availability by day.
     *
     * @param Request $request
     * @param null $customRequest
     * @return array
     */
    public function getHourAvailability(Request $request,$customRequest=null){



        if($customRequest!=null){

            $request['day']=$customRequest['day'];
            $request['increment']=$customRequest['increment'];
            $request['event_duration']=$customRequest['event_duration'];
            $request['weekday']=intval($customRequest['weekday']);
            $request['today']=$customRequest['today'];
            $request['id']=$customRequest['id'];
        }



        $schedule=SpecialSchedule::where('user_id','=',$request['id'])->whereDate('day','=',$request['day'])->first();

        if(!$schedule){

            if($request['weekday']==1){

                $weekday='monday';

            }
            if($request['weekday']==2){

                $weekday='tuesday';

            }
            if($request['weekday']==3){

                $weekday='wednesday';

            }
            if($request['weekday']==4){

                $weekday='thursday';

            }
            if($request['weekday']==5){

                $weekday='friday';

            }
            if($request['weekday']==6){

                $weekday='saturday';

            }
            if($request['weekday']==7){

                $weekday='sunday';

            }

            $schedule=Schedule::where('user_id','=',$request['id'])->first();

            if($request['day']==Carbon::parse($request['today'])->format('Y-m-d')){


                $timeslots=Timeslot::where('schedule_id','=',$schedule->id)->where('day','=',$weekday)->where(DB::raw('CAST(timeslot as time)'),'>',Carbon::parse($request['today'])->format('G:i'))->pluck('timeslot')->toArray();
            }else{


                $timeslots=Timeslot::where('schedule_id','=',$schedule->id)->where('day','=',$weekday)->pluck('timeslot')->toArray();
            }



            $available_timeslots=[];

            $increment=$request['increment'];
            $event_duration=$request['event_duration'];

            $slots=$event_duration/15;


            $events=Event::where('employee_id','=',$request['id'])->whereDate('day','=',$request['day'])->get();
            $custom_events=CustomEvent::where('employee_id','=',$request['id'])->whereDate('day','=',$request['day'])->get();

            $events_slots=[];


            foreach($custom_events as $event){


                $numbers_start=explode(':',$event->start);

                $minutes_start=intval($numbers_start[0])*60+intval($numbers_start[1]);

                $numbers_end=explode(':',$event->end);

                $minutes_end=intval($numbers_end[0])*60+intval($numbers_end[1]);

                for($i=$minutes_start;$i<$minutes_end;$i+=15){

                    $events_slots[]=date('G:i', mktime(0,$i));

                }


            }


            //loop through events
            foreach($events as $event){

                $numbers=explode(':',$event->timeslot);

                $minutes=intval($numbers[0])*60+intval($numbers[1]);

                //number of slots containing event duration
                $tmp_slots=$event->duration/15;


                //add beggining slot
                $events_slots[]=$event->timeslot;

                //loop through slots
                for($i=1;$i<=$tmp_slots;$i++){


                    //if slot index not equal to slots length add next slot+15
                    if($i!=$tmp_slots){

                        $events_slots[]=date('G:i', mktime(0,$minutes+($i*15)));

                    }

                    //if slot less than MAIN EVENT DURATION slots add previous slot-15
                    if($i<$slots){


                        $events_slots[]=date('G:i', mktime(0,$minutes-($i*15)));
                    }

                }


            }


            $hours=[];
            //loop through available timeslots
            foreach($timeslots as $slot){




                $numbers=explode(':',$slot);

                $minutes=intval($numbers[0])*60+intval($numbers[1]);




                //if timeslot is dividable by settings increment
                if($minutes%$increment==0){


                    $hours[]=$slot;

                    $checked=true;

                    //if slot is inside events slots dont add to available timeslots
                    if(in_array($slot,$events_slots)){


                        $checked=false;
                    }



                    if($checked){

                        $available_timeslots[]=$slot;
                    }


                }


            }




            return array('availability'=>$available_timeslots,'hours'=>$hours);

        }




        if($request['day']==Carbon::parse($request['today'])->format('Y-m-d')){


            $timeslots=SpecialTimeslot::where('special_schedule_id','=',$schedule->id)->where(DB::raw('CAST(timeslot as time)'),'>',Carbon::parse($request['today'])->format('G:i'))->pluck('timeslot')->toArray();
        }else{


            $timeslots=SpecialTimeslot::where('special_schedule_id','=',$schedule->id)->pluck('timeslot')->toArray();
        }



        $available_timeslots=[];

        $increment=$request['increment'];
        $event_duration=$request['event_duration'];

        $slots=$event_duration/15;


        $events=Event::where('employee_id','=',$request['id'])->whereDate('day','=',$request['day'])->get();
        $custom_events=CustomEvent::where('employee_id','=',$request['id'])->whereDate('day','=',$request['day'])->get();


        $events_slots=[];

        foreach($custom_events as $event){


            $numbers_start=explode(':',$event->start);

            $minutes_start=intval($numbers_start[0])*60+intval($numbers_start[1]);

            $numbers_end=explode(':',$event->end);

            $minutes_end=intval($numbers_end[0])*60+intval($numbers_end[1]);

            for($i=$minutes_start;$i<$minutes_end;$i+=15){

                $events_slots[]=date('G:i', mktime(0,$i));

            }


        }

        foreach($events as $event){

            $numbers=explode(':',$event->timeslot);

            $minutes=intval($numbers[0])*60+intval($numbers[1]);

            $tmp_slots=$event->duration/15;


            $events_slots[]=$event->timeslot;
            for($i=1;$i<=$tmp_slots;$i++){



                if($i!=$tmp_slots){

                    $events_slots[]=date('G:i', mktime(0,$minutes+($i*15)));

                }

                //if slot less than MAIN EVENT DURATION slots add previous slot-15
                if($i<$slots){


                    $events_slots[]=date('G:i', mktime(0,$minutes-($i*15)));
                }





            }



        }



        $hours=[];
        foreach($timeslots as $slot){




            $numbers=explode(':',$slot);

            $minutes=intval($numbers[0])*60+intval($numbers[1]);




            //increment
            if($minutes%$increment==0){

                $hours[]=$slot;

                $checked=true;


                if(in_array($slot,$events_slots)){


                    $checked=false;
                }



                if($checked){

                    $available_timeslots[]=$slot;
                }


            }

        }


        return array('availability'=>$available_timeslots,'hours'=>$hours);






    }


    /**
     *
     * Book an Appointment, pay now or later.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeEvent(Request $request){


        if(isset($request['later']) && $request['later']==true){


                $event=Event::create([
                    'user_id'=>Auth::user()->id,
                    'employee_id'=>$request->employee_id,
                    'day'=>$request->day,
                    'timeslot'=>$request->timeslot,
                    'duration'=>$request->duration,
                    'price'=>$request->amount,
                    'status'=>'pending'

                ]);


                $profile_user=Profile::where('user_id','=',Auth()->user()->id)->first();
                $profile_employee=Profile::where('user_id','=',$request->employee_id)->first();
                $user=User::where('id','=',Auth()->user()->id)->first();
                $employee=User::where('id','=',$request->employee_id)->first();

                if($profile_employee->notifications==1){


                    Mail::to($employee->email)->send(new AppointmentPayLaterEmployee('User marked the appointment for later payment',$event,$user,$employee));

                }

                if($profile_user->notifications==1){

                    Mail::to($user->email)->send(new AppointmentPayLaterUser('Appointment marked for later payment',$event,$user,$employee));
                }


                return response()->json(array('success'=>true,'message'=>'Appointment pending! Please confirm in the next 2 hours!'));


        }


        if(isset($request['later']) && $request['later']==false){

            try {
                Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

                $customer = Customer::create(array(
                    'email' => $request->token['email'],
                    'source' => $request->token['id']
                ));

                $charge = Charge::create(array(
                    'customer' => $customer->id,
                    'amount' => $request->amount,
                    'currency' => 'usd'
                ));

                $event_mail=Event::create([
                    'user_id'=>Auth::user()->id,
                    'employee_id'=>$request->employee_id,
                    'day'=>$request->day,
                    'timeslot'=>$request->timeslot,
                    'duration'=>$request->duration,
                    'price'=>$request->amount/100,
                    'status'=>'completed',
                    'completed_at'=>Carbon::now()

                ]);


                $profile=Profile::where('user_id','=',Auth()->user()->id)->first();
                $user=User::where('id','=',Auth()->user()->id)->first();
                $employee=User::where('id','=',$request->employee_id)->first();


                $employee_profile=Profile::where('user_id','=',$request->employee_id)->first();

                if($profile->notifications==1){


                    Mail::to($user->email)->send(new AppointmentPaidUser('Appointment created successfully',$event_mail,$user,$employee));

                }

                if($employee_profile->notifications==1){

                    Mail::to($employee->email)->send(new AppointmentPaidEmployee('Appointment created successfully',$event_mail,$user,$employee));
                }


                if($profile->gcalendar && $profile->access_token!=''){

                    $google=new GoogleCustomClient();
                    $client = $google->client();
                    $token=Profile::where('user_id','=',Auth()->user()->id)->pluck('access_token')->first();
                    $calendar_id=GoogleCalendar::where('user_id','=',Auth()->user()->id)->pluck('calendar_id')->first();
                    $client->setAccessToken($token);

                    $cal = new \Google_Service_Calendar($client);

                    $event = new \Google_Service_Calendar_Event();
                    $event->setSummary('Timeslots Appointment');
                    $event->setDescription('Appointment with Employee:'.$employee->first_name.' '.$employee->last_name);
                    $event->setColorId(1);



                    $numbers=explode(':',$request->timeslot);
                    $hour=intval($numbers[0]);
                    $minutes=intval($numbers[1]);
                    $total=($hour*60)+$minutes+intval($request->duration);
                    $total_hours=floor($total/60);
                    $total_minutes=($total%60)==0 ? '00' : $total%60;

                    $endtime=$total_hours.':'.$total_minutes;



                    $start_datetime=Carbon::createFromTimestamp(strtotime($request->day .$request->timeslot . ":00"));
                    $end_datetime=Carbon::createFromTimestamp(strtotime($request->day . $endtime . ":00"));

                    $start = new \Google_Service_Calendar_EventDateTime();
                    $start->setDateTime($start_datetime->toAtomString());
                    $event->setStart($start);
                    $end = new \Google_Service_Calendar_EventDateTime();
                    $end->setDateTime($end_datetime->toAtomString());
                    $event->setEnd($end);


                    $created_event = $cal->events->insert($calendar_id, $event);


                }

                if($employee_profile->gcalendar && $employee_profile->access_token!=''){

                    $google=new GoogleCustomClient();
                    $client = $google->client();
                    $token=Profile::where('user_id','=',$request->employee_id)->pluck('access_token')->first();
                    $calendar_id=GoogleCalendar::where('user_id','=',$request->employee_id)->pluck('calendar_id')->first();
                    $client->setAccessToken($token);

                    $cal = new \Google_Service_Calendar($client);

                    $event = new \Google_Service_Calendar_Event();
                    $event->setSummary('Timeslots Appointment');
                    $event->setDescription('Appointment with User:'.$user->first_name.' '.$user->last_name);
                    $event->setColorId(1);



                    $numbers=explode(':',$request->timeslot);
                    $hour=intval($numbers[0]);
                    $minutes=intval($numbers[1]);
                    $total=($hour*60)+$minutes+intval($request->duration);
                    $total_hours=floor($total/60);
                    $total_minutes=($total%60)==0 ? '00' : $total%60;

                    $endtime=$total_hours.':'.$total_minutes;

                    $start_datetime=Carbon::createFromTimestamp(strtotime($request->day .$request->timeslot . ":00"));
                    $end_datetime=Carbon::createFromTimestamp(strtotime($request->day . $endtime . ":00"));

                    $start = new \Google_Service_Calendar_EventDateTime();
                    $start->setDateTime($start_datetime->toAtomString());
                    $event->setStart($start);
                    $end = new \Google_Service_Calendar_EventDateTime();
                    $end->setDateTime($end_datetime->toAtomString());
                    $event->setEnd($end);


                    $created_event = $cal->events->insert($calendar_id, $event);


                }


                return response()->json(array('success'=>true,'message'=>'Appointment booking completed!'));
            } catch (\Exception $ex) {
                return response()->json(array('success'=>false,'message'=>$ex->getMessage()));
            }
        }


        if(isset($request['pay_later']) && $request['pay_later']==true){

            try {
                Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

                $customer = Customer::create(array(
                    'email' => $request->token['email'],
                    'source' => $request->token['id']
                ));

                $charge = Charge::create(array(
                    'customer' => $customer->id,
                    'amount' => $request->amount,
                    'currency' => 'usd'
                ));

                $updated_event=Event::where('id','=',$request->event_id)->first();
                $updated_event->status='completed';
                $updated_event->completed_at=Carbon::now();
                $updated_event->save();


                $profile=Profile::where('user_id','=',Auth()->user()->id)->first();
                $user=User::where('id','=',Auth()->user()->id)->first();
                $employee=User::where('id','=',$updated_event->employee_id)->first();


                $employee_profile=Profile::where('user_id','=',$updated_event->employee_id)->first();

                if($profile->notifications==1){


                    Mail::to($user->email)->send(new AppointmentPaidUser('Appointment created successfully',$updated_event,$user,$employee));

                }

                if($employee_profile->notifications==1){

                    Mail::to($employee->email)->send(new AppointmentPaidEmployee('Appointment created successfully',$updated_event,$user,$employee));
                }



                if($profile->gcalendar && $profile->access_token!=''){

                    $google=new GoogleCustomClient();
                    $client = $google->client();
                    $token=Profile::where('user_id','=',Auth()->user()->id)->pluck('access_token')->first();
                    $calendar_id=GoogleCalendar::where('user_id','=',Auth()->user()->id)->pluck('calendar_id')->first();
                    $client->setAccessToken($token);

                    $cal = new \Google_Service_Calendar($client);

                    $event = new \Google_Service_Calendar_Event();
                    $event->setSummary('Timeslots Appointment');
                    $event->setDescription('Appointment with Employee:'.$employee->first_name.' '.$employee->last_name);
                    $event->setColorId(1);



                    $numbers=explode(':',$updated_event->timeslot);
                    $hour=intval($numbers[0]);
                    $minutes=intval($numbers[1]);
                    $total=($hour*60)+$minutes+intval($updated_event->duration);
                    $total_hours=floor($total/60);
                    $total_minutes=($total%60)==0 ? '00' : $total%60;

                    $endtime=$total_hours.':'.$total_minutes;



                    $start_datetime=Carbon::createFromTimestamp(strtotime($updated_event->day .$updated_event->timeslot . ":00"));
                    $end_datetime=Carbon::createFromTimestamp(strtotime($updated_event->day . $endtime . ":00"));

                    $start = new \Google_Service_Calendar_EventDateTime();
                    $start->setDateTime($start_datetime->toAtomString());
                    $event->setStart($start);
                    $end = new \Google_Service_Calendar_EventDateTime();
                    $end->setDateTime($end_datetime->toAtomString());
                    $event->setEnd($end);


                    $created_event = $cal->events->insert($calendar_id, $event);


                }

                if($employee_profile->gcalendar && $employee_profile->access_token!=''){

                    $google=new GoogleCustomClient();
                    $client = $google->client();
                    $token=Profile::where('user_id','=',$updated_event->employee_id)->pluck('access_token')->first();
                    $calendar_id=GoogleCalendar::where('user_id','=',$updated_event->employee_id)->pluck('calendar_id')->first();
                    $client->setAccessToken($token);

                    $cal = new \Google_Service_Calendar($client);

                    $event = new \Google_Service_Calendar_Event();
                    $event->setSummary('Timeslots Appointment');
                    $event->setDescription('Appointment with User:'.$user->first_name.' '.$user->last_name);
                    $event->setColorId(1);



                    $numbers=explode(':',$updated_event->timeslot);
                    $hour=intval($numbers[0]);
                    $minutes=intval($numbers[1]);
                    $total=($hour*60)+$minutes+intval($updated_event->duration);
                    $total_hours=floor($total/60);
                    $total_minutes=($total%60)==0 ? '00' : $total%60;

                    $endtime=$total_hours.':'.$total_minutes;

                    $start_datetime=Carbon::createFromTimestamp(strtotime($updated_event->day .$updated_event->timeslot . ":00"));
                    $end_datetime=Carbon::createFromTimestamp(strtotime($updated_event->day . $endtime . ":00"));

                    $start = new \Google_Service_Calendar_EventDateTime();
                    $start->setDateTime($start_datetime->toAtomString());
                    $event->setStart($start);
                    $end = new \Google_Service_Calendar_EventDateTime();
                    $end->setDateTime($end_datetime->toAtomString());
                    $event->setEnd($end);


                    $created_event = $cal->events->insert($calendar_id, $event);


                }


                return response()->json(array('success'=>true,'message'=>'Appointment booking completed!'));
            } catch (\Exception $ex) {
                return response()->json(array('success'=>false,'message'=>$ex->getMessage()));
            }
        }






    }


    /**
     *
     * User transactions view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transactionsView(){

        return view('layouts.user.transactions');

    }


    /**
     *
     * Helper function for getting end time based on start time and duration.
     *
     * @param $timeslot
     * @param $duration
     * @return false|string
     */
    public function getTimeAfterDuration($timeslot,$duration){

        $numbers=explode(':',$timeslot);
        $minutes=intval($numbers[0])*60+intval($numbers[1]);


        return date('G:i', mktime(0,$minutes+$duration));

    }

    /**
     *
     * Datatables data for transactions.
     *
     * @return mixed
     */
    public function getTransactions(){



        $transactions=Event::select('events.id as id','day as event_date',DB::raw('CONCAT(users.first_name," ",users.last_name) as employee'),'timeslot as event_time','duration as duration','price as price','status as status','events.created_at as date_created','events.completed_at as completed_at')
            ->leftJoin('users','users.id','=','events.employee_id')
            ->where('user_id','=',Auth()->user()->id)
            ->get();


        return DataTables::of($transactions)
            ->addColumn('event_type', function ($item) {

                return 'Appointment';
            })
            ->editColumn('event_time',function($item){

                return $item->event_time.'-'.$this->getTimeAfterDuration($item->event_time,$item->duration);
            })
            ->editColumn('price',function($item){

                return '$'.$item->price;
            })
            ->editColumn('status',function($item){

                return '<span class="status '.$item->status.'">'.$item->status.'</span> ';
            })
            ->editColumn('action',function($item){

                if($item->status=='pending'){


                    $price=intval($item->price)*100;


                    $form='<form action="/user/complete-event" class="stripe-form" method="POST">
<input type="hidden"  name="_token" value="'.csrf_token().'">
<input type="hidden" name="id" class="event_id" value="'.$item->id.'">
<input type="hidden" class="amount" name="amount" value="'.$price.'">
           
         
    <button type="submit" class="btn btn-info">Pay Now</button>
   
        </form>';

                    return $form;
                }elseif($item->status=='completed'){

                    return 'Paid';
                }else{

                    return 'Failed to pay';
                }




            })
            ->rawColumns(['status','action'])
            ->make(true);

    }

    /**
     *
     * Check if user is verified.
     *
     * @return mixed
     */
    public function isVerified(){


        $verified=User::where('id','=',Auth::user()->id)->pluck('verified')->first();

        return $verified;

    }


    /**
     *
     * Get appointments.
     *
     * @return mixed
     */
    public function getEvents(){


        $events=Event::select(DB::raw("(CASE WHEN status='pending' THEN 'rgb(255, 178, 43)' WHEN status='completed' THEN 'green' ELSE 'red' END) as backgroundColor"),'day as date',DB::raw('COUNT("day") as count'))->where('user_id','=',Auth::user()->id)->distinct('date')->groupBy('day','backgroundColor')->get()->toArray();

        return $events;

    }

    /**
     *
     * Get pending events.
     *
     * @return mixed
     */
    public function getPendingEvents(){


        $events=Event::where('user_id','=',Auth::user()->id)->where('status','=','pending')->with('employee')->get()->toArray();

        return $events;

    }

    /**
     *
     * Get events on a specific day.
     *
     * @param Request $request
     * @return mixed
     */
    public function getEventsDay(Request $request){


        $events=Event::where('user_id','=',Auth::user()->id)->whereDate('day','=',$request['day'])->with('employee')->get()->toArray();

        return $events;

    }


}
