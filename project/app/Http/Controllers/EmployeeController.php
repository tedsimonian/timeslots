<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\CustomEvent;
use App\Event;
use App\GoogleCalendar;
use App\GoogleCustomClient;
use App\Mail\AppointmentCreated;
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
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     * Employee home view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function home(){




        return view('home_employee')->with('vue',true);
    }

    /**
     *
     * Employee profile view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function profileView(){

        return view('layouts.employee.profile')->with('vue',true);
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
     * Business Rules view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function settingsView(){



        return view('layouts.employee.settings')->with('vue',true);
    }


    /**
     *
     * Update Business Rules.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calendarSettings(Request $request){

        $this->validate($request,[

            'increment'=>'required',
            'event_duration'=>'required',
            'price'=>'required',

        ]);

        $calendar=Calendar::updateOrCreate(
            ['user_id'=>Auth()->user()->id],
            ['increment'=>$request['increment'],'event_duration'=>$request['event_duration'],'price'=>$request['price']]

        );

        /*
        $schedule=Schedule::where('user_id','=',Auth()->user()->id)->where('calendar_id','=',$calendar->id)->first();
        $special_schedules=SpecialSchedule::where('user_id','=',Auth()->user()->id)->where('calendar_id','=',$calendar->id)->where('available','=',1)->get();

        Timeslot::where('schedule_id','=',$schedule->id)->delete();

        foreach($special_schedules as $item){

            $item->delete();

            SpecialTimeslot::where('special_schedule_id','=',$item->id)->delete();
        }
        */


        return response()->json(array('success'=>true,'message'=>'Settings updated!'));

    }


    /**
     *
     * Get Business Rules data.(price,duration,increment)
     *
     * @return mixed
     */
    public function getCalendar(){

        $calendar=Calendar::where('user_id','=',Auth::user()->id)->first();

        return $calendar;

    }


    /**
     *
     * Save special schedule.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSpecialSchedule(Request $request){

        $calendar=Calendar::where('user_id','=',Auth()->user()->id)->first();

        $schedule=SpecialSchedule::updateOrCreate(
            ['day'=>Carbon::parse($request['day'])->format('Y-m-d')],
            ['user_id'=>Auth()->user()->id,'calendar_id'=>$calendar->id,'available'=>$request['available']]

        );

        if($request['available']){

            if(!empty($request['availability'])){

                SpecialTimeslot::where('special_schedule_id','=',$schedule->id)->delete();

                foreach($request['availability'] as $slot){

                    SpecialTimeslot::create([

                        'special_schedule_id'=>$schedule->id,
                        'timeslot'=>$slot

                    ]);
                }

            }else{

                SpecialTimeslot::where('special_schedule_id','=',$schedule->id)->delete();
            }

        }

        return response()->json(array('success'=>true,'message'=>'Schedule updated!'));

    }


    /**
     *
     * Save regular working week schedule.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSchedule(Request $request){


        $calendar=Calendar::where('user_id','=',Auth()->user()->id)->first();

        $schedule=Schedule::updateOrCreate(
            ['user_id'=>Auth()->user()->id],
            ['calendar_id'=>$calendar->id,
                'monday_available'=>($request['monday_available']) ? $request['monday_available'] : false,
                'tuesday_available'=>($request['tuesday_available']) ? $request['tuesday_available'] : false,
                'wednesday_available'=>($request['wednesday_available']) ? $request['wednesday_available'] : false,
                'thursday_available'=>($request['thursday_available']) ? $request['thursday_available'] : false,
                'friday_available'=>($request['friday_available']) ? $request['friday_available'] : false,
                'saturday_available'=>($request['saturday_available']) ? $request['saturday_available'] : false,
                'sunday_available'=>($request['sunday_available']) ? $request['sunday_available'] : false
            ]

        );

        if($request['monday_available']){

            if(!empty($request['monday_availability'])){

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','monday')->delete();

                foreach($request['monday_availability'] as $slot){

                    Timeslot::create([

                        'schedule_id'=>$schedule->id,
                        'day'=>'monday',
                        'timeslot'=>$slot

                    ]);

                }
            }else{

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','monday')->delete();

            }
        }



        if($request['tuesday_available']){

            if(!empty($request['tuesday_availability'])){

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','tuesday')->delete();

                foreach($request['tuesday_availability'] as $slot){

                    Timeslot::create([

                        'schedule_id'=>$schedule->id,
                        'day'=>'tuesday',
                        'timeslot'=>$slot

                    ]);

                }
            }else{

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','tuesday')->delete();
            }
        }

        if($request['wednesday_available']){

            if(!empty($request['wednesday_availability'])){

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','wednesday')->delete();

                foreach($request['wednesday_availability'] as $slot){

                    Timeslot::create([

                        'schedule_id'=>$schedule->id,
                        'day'=>'wednesday',
                        'timeslot'=>$slot

                    ]);

                }
            }else{

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','wednesday')->delete();
            }
        }

        if($request['thursday_available']){

            if(!empty($request['thursday_availability'])){

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','thursday')->delete();

                foreach($request['thursday_availability'] as $slot){

                    Timeslot::create([

                        'schedule_id'=>$schedule->id,
                        'day'=>'thursday',
                        'timeslot'=>$slot

                    ]);

                }
            }else{

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','thursday')->delete();
            }
        }

        if($request['friday_available']){

            if(!empty($request['friday_availability'])){

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','friday')->delete();

                foreach($request['friday_availability'] as $slot){

                    Timeslot::create([

                        'schedule_id'=>$schedule->id,
                        'day'=>'friday',
                        'timeslot'=>$slot

                    ]);

                }
            }else{

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','friday')->delete();
            }
        }

        if($request['saturday_available']){

            if(!empty($request['saturday_availability'])){

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','saturday')->delete();

                foreach($request['saturday_availability'] as $slot){

                    Timeslot::create([

                        'schedule_id'=>$schedule->id,
                        'day'=>'saturday',
                        'timeslot'=>$slot

                    ]);

                }
            }else{

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','saturday')->delete();
            }
        }

        if($request['sunday_available']){

            if(!empty($request['sunday_availability'])){

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','sunday')->delete();

                foreach($request['sunday_availability'] as $slot){

                    Timeslot::create([

                        'schedule_id'=>$schedule->id,
                        'day'=>'sunday',
                        'timeslot'=>$slot

                    ]);

                }
            }else{

                Timeslot::where('schedule_id','=',$schedule->id)->where('day','=','sunday')->delete();
            }
        }




        return response()->json(array('success'=>true,'message'=>'Schedule updated!'));

    }


    /**
     *
     * Get regular schedule data.
     *
     * @return mixed
     */
    public function getSchedule(){


        $schedule=Schedule::where('user_id','=',Auth()->user()->id)->with('timeslots')->first();

        return $schedule;
    }

    /**
     *
     * Get special schedule data.
     *
     * @return mixed
     */
    public function getSpecialSchedule(Request $request){



        $schedule=SpecialSchedule::where('user_id','=',Auth()->user()->id)->whereDate('day','=',$request['day'])->with('specialTimeslots')->first();


        return $schedule;
    }


    /**
     *
     * Get calendar markers.
     *
     * @param Request $request
     * @return array
     */
    public function getDaysMarkers(Request $request){


        $schedule=SpecialSchedule::where('user_id','=',Auth()->user()->id)->with('specialTimeslots')->get();

        $days_markers=[];

        foreach($schedule as $item){

            if(!$item->available){

                $days_markers[]=array('id'=>$item->id,'date'=>$item->day,'backgroundColor'=>'yellow','borderColor'=>'yellow');
            }else{

                $days_markers[]=array('id'=>$item->id,'date'=>$item->day,'backgroundColor'=>'#ffb22b','borderColor'=>'#ffb22b');
            }



        }



        if(intval($request['rules'])==0){



            $events=Event::select(DB::raw('"event" as type'),'day as date',DB::raw('COUNT("day") as count'))->where('employee_id','=',Auth()->user()->id)->distinct('date')->groupBy('day')->get()->toArray();
            $custom_events=CustomEvent::select(DB::raw('"custom_event" as type'),'day as date',DB::raw('COUNT("day") as count'))->where('employee_id','=',Auth()->user()->id)->distinct('date')->groupBy('day')->get()->toArray();


            $events=array_merge($events,$custom_events);



            $empty_request=new Request();

            $counter=0;
            foreach($events as $event){

                $counter++;
                $customRequest=array('day'=>$event['date'],'increment'=>$request['increment'],'event_duration'=>$request['event_duration'],'weekday'=>date('N',strtotime($event['date'])),'today'=>$request['today']);

                $availability=$this->getHourAvailability($empty_request,$customRequest);


                if(empty($availability['availability'])){

                    $days_markers[]=array('id'=>$counter,'date'=>$event['date'],'backgroundColor'=>'red','borderColor'=>'red','color'=>'green');
                }



            }

            return array('days_markers'=>$days_markers,'events_markers'=>$events);
        }





        return array('days_markers'=>$days_markers);


    }


    /**
     *
     * Get calendar days availability.
     *
     * @return array
     */
    public function getCalendarAvailability(){


        $schedule=Schedule::where('user_id','=',Auth()->user()->id)->with('timeslots')->first();


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


        $special_schedule=SpecialSchedule::where('user_id','=',Auth()->user()->id)->get();

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
     * Get calendar hours availablity.
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

        }



        $schedule=SpecialSchedule::where('user_id','=',Auth()->user()->id)->whereDate('day','=',$request['day'])->first();

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

            $schedule=Schedule::where('user_id','=',Auth()->user()->id)->first();

            if($request['day']==Carbon::parse($request['today'])->format('Y-m-d')){



                $timeslots=Timeslot::where('schedule_id','=',$schedule->id)->where('day','=',$weekday)->where(DB::raw('CAST(timeslot as time)'),'>',Carbon::parse($request['today'])->format('G:i'))->pluck('timeslot')->toArray();
            }else{


                $timeslots=Timeslot::where('schedule_id','=',$schedule->id)->where('day','=',$weekday)->pluck('timeslot')->toArray();
            }





            $available_timeslots=[];

            $increment=$request['increment'];
            $event_duration=$request['event_duration'];

            $slots=$event_duration/15;


            $events=Event::where('employee_id','=',Auth()->user()->id)->whereDate('day','=',$request['day'])->get();
            $custom_events=CustomEvent::where('employee_id','=',Auth()->user()->id)->whereDate('day','=',$request['day'])->get();

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


        $events=Event::where('employee_id','=',Auth()->user()->id)->whereDate('day','=',$request['day'])->get();
        $custom_events=CustomEvent::where('employee_id','=',Auth()->user()->id)->whereDate('day','=',$request['day'])->get();


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
     * Get users.
     *
     * @return array
     */
    public function getUsers(){


        $users=User::role('user')->get();

        $verified=[];
        foreach ($users as $user){

            if($user->verified){

                $verified[]=array('id'=>$user->id,'name'=>$user->first_name.' '.$user->last_name);

            }


        }


        return $verified;

    }


    /**
     *
     * Create new appointment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAppointment(Request $request){


        $event=Event::create([

            'user_id'=>$request['user']['id'],
            'employee_id'=>Auth()->user()->id,
            'day'=>Carbon::parse($request['day'])->format('Y-m-d'),
            'timeslot'=>$request['timeslot'],
            'duration'=>$request['duration'],
            'price'=>$request['price'],
            'status'=>'pending',
        ]);

        $user=User::where('id','=',$request['user']['id'])->with('profile')->first();



        if($user->profile && $user->profile->notifications==1){

            $employee=User::where('id','=',Auth()->user()->id)->first();
            Mail::to($user->email)->send(new AppointmentCreated('Appointment Created',$event,$user,$employee));
        }



        return response()->json(array('success'=>true,'message'=>'Appointment created!'));

    }


    /**
     *
     * Helper function for getting colors for google calendar.
     *
     * @param $hex
     * @return int
     */
    public function getColorId($hex){


        switch ($hex) {
            case "#A4BDFC":
                return 1;
                break;
            case "#7AE7BF":
                return  2;
                break;
            case '#DBADFF':
                return 3;
                break;
            case '#FF887C':
                return 4;
                break;
            case '#FBD75B':
                return 5;
                break;
            case '#FFB878':
                return 6;
                break;
            case '#46D6DB':
                return 7;
                break;
            case '#E1E1E1':
                return 8;
                break;
            case '#5484ED':
                return 9;
                break;
            case '#51B749':
                return 10;
                break;
            case '#DC2127':
                return 11;
                break;
            default:
                return 11;
        }
    }


    /**
     *
     * Check if employee has permission to view and book on calendar.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPermissions(){

        return response()->json(array('success'=>true,'can_calendar'=>Auth::user()->can('view employee calendar'),'can_book'=>Auth::user()->can('book employee event')));

    }


    /**
     *
     * Create new custom event.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createEvent(Request $request){

        $color_id=$this->getColorId($request['color']);

        CustomEvent::create([

            'employee_id'=>Auth()->user()->id,
            'day'=>Carbon::parse($request['day'])->format('Y-m-d'),
            'start'=>$request['start'],
            'end'=>$request['end'],
            'title'=>$request['title'],
            'color'=>$request['color'],
            'color_id'=>$color_id,
            'description'=>$request['description']
        ]);

        $profile=Profile::where('user_id','=',Auth()->user()->id)->first();


        if($profile->gcalendar && $profile->access_token!=''){

            $google=new GoogleCustomClient();
            $client = $google->client();
            $token=Profile::where('user_id','=',Auth()->user()->id)->pluck('access_token')->first();
            $calendar_id=GoogleCalendar::where('user_id','=',Auth()->user()->id)->pluck('calendar_id')->first();
            $client->setAccessToken($token);

            $cal = new \Google_Service_Calendar($client);

            $event = new \Google_Service_Calendar_Event();
            $event->setSummary($request['title']);
            $event->setDescription($request['description']);
            $event->setColorId($color_id);



            $start_datetime=Carbon::createFromTimestamp(strtotime($request['day'] . $request['start'] . ":00"));
            $end_datetime=Carbon::createFromTimestamp(strtotime($request['day'] . $request['end'] . ":00"));

            $start = new \Google_Service_Calendar_EventDateTime();
            $start->setDateTime($start_datetime->toAtomString());
            $event->setStart($start);
            $end = new \Google_Service_Calendar_EventDateTime();
            $end->setDateTime($end_datetime->toAtomString());
            $event->setEnd($end);


            $created_event = $cal->events->insert($calendar_id, $event);




        }


        return response()->json(array('success'=>true,'message'=>'Appointment created!'));

    }

    /**
     *
     * Get all events.
     *
     * @param Request $request
     * @return array
     */
    public function getEvents(Request $request){


        $appointments=Event::where('employee_id','=',Auth()->user()->id)->whereDate('day','=',$request['day'])->with('user')->get();
        $events=CustomEvent::where('employee_id','=',Auth()->user()->id)->whereDate('day','=',$request['day'])->get();


        return array('appointments'=>$appointments,'events'=>$events);

    }


    /**
     *
     * Transactions view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transactionsView(){

        return view('layouts.employee.transactions');

    }

    /**
     *
     * Events view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function customEventsView(){

        return view('layouts.employee.events');
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



        $transactions=Event::select('day as event_date',DB::raw('CONCAT(users.first_name," ",users.last_name) as user'),'timeslot as event_time','duration as duration','price as price','status as status','events.created_at as date_created','events.completed_at as completed_at')
            ->leftJoin('users','users.id','=','events.user_id')
            ->where('employee_id','=',Auth()->user()->id)
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
            ->rawColumns(['status'])
            ->make(true);

    }

    /**
     *
     * Datatables data for events.
     *
     * @return mixed
     */
    public function getCustomEvents(){



        $events=CustomEvent::select('day as event_date',DB::raw('CONCAT(start,"-",end) as event_time'),'title as title','color as color','description as description')
            ->where('employee_id','=',Auth()->user()->id)
            ->get();


        return DataTables::of($events)
            ->editColumn('color',function($item){

                return '<span style="color:white;background:'.$item->color.';  border-radius: 3px;padding: 3px;">'.$item->color.'</span>';
            })
            ->rawColumns(['color'])
            ->make(true);

    }


    /**
     *
     * Authenticate user with Google Calendar.
     *
     * @param GoogleCustomClient $google
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authenticate(GoogleCustomClient $google,User $user,Request $request){


        $client = $google->client();
        if ($request->has('code')) {

            $client->authenticate($request->get('code'));
            $token = $client->getAccessToken();




            $profile=Profile::updateOrCreate(
                ['user_id'=>Auth::user()->id],
                ['access_token'=>json_encode($token)]

            );

            $timezone = env('APP_TIMEZONE');

            $cal = new \Google_Service_Calendar($client);

            $google_calendar = new \Google_Service_Calendar_Calendar($client);
            $google_calendar->setSummary('TimeSlots Calendar');
            $google_calendar->setTimeZone($timezone);

            $created_calendar = $cal->calendars->insert($google_calendar);

            $calendar_id = $created_calendar->getId();

            $calendar=new GoogleCalendar();
            $calendar->user_id=Auth::user()->id;
            $calendar->title='TimeSlots Calendar';
            $calendar->calendar_id=$calendar_id;
            $calendar->save();

            if(Auth::user()->hasRole('employee')){

                return redirect('/employee/profile');
            }

            if(Auth::user()->hasRole('user')){

                return redirect('/user/profile');
            }

        } else {
            $auth_url = $client->createAuthUrl();
            return redirect($auth_url);
        }
    }

}
