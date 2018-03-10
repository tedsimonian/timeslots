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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Stripe\Error\Permission;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
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




        return view('home_admin')->with('vue',true);;
    }


    /**
     *
     * Acl view
     *
     * @return view
     */
    public function acl(){

        return view('layouts.admin.acl');
    }


    /**
     *
     * Datatables data for acl.
     *
     * @return mixed
     */
    public function getAcl(){


        $roles = Role::select(['roles.id as id', 'roles.name as role'])->get();


        return DataTables::of($roles)
            ->addColumn('action', function ($role) {

                if($role->role!='admin'){

                    return '<a href="/admin/edit-role/'.$role->id.'"  id="'.$role->id.'" class="text-inverse tooltipsy p-r-10 edit" data-toggle="tooltip" title="" data-original-title="Edit"><i class="ti-marker-alt"></i></a>';

                }
                //return '<a href="/edit-role/'.$role->id.'"  id="'.$role->id.'" class="text-inverse tooltipsy p-r-10 edit" data-toggle="tooltip" title="" data-original-title="Edit"><i class="ti-marker-alt"></i></a> <a href="/delete-role/'.$role->id.'" class="text-inverse tooltipsy delete" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a>';

            })
            ->rawColumns(['action'])
            ->make(true);


    }


    /**
     *
     * Update role permissions.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePermissions($id,Request $request){

        $role=Role::where('id','=',$id)->first();

        $synced_permissions=[];
        foreach($request['permissions'] as $permission){

            if($permission['checked']){

                $synced_permissions[]=$permission['name'];

            }

        }

        $role->syncPermissions($synced_permissions);

        return response()->json(array('success'=>true,'message'=>'Permissions updated!'));

    }


    /**
     *
     * Get role permissions.
     *
     * @param $id
     * @return mixed
     */
    public function getRolePermissions($id){

        $role=Role::where('id','=',$id)->first();

        $checked_permissions=$role->permissions;

        $permissions=\Spatie\Permission\Models\Permission::where('role_name','=',$role->name)->get();


        foreach($permissions as $permission){

            $permission->checked=false;
            foreach($checked_permissions as $permission2){

                if($permission->id==$permission2->id){

                    $permission->checked=true;
                }
            }

        }

        return $permissions;


    }

    /**
     *
     * Edit role view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function editRoleView($id){


        return view('layouts.admin.edit_role')->with('id',$id)->with('vue',true);
    }


    /**
     *
     * Users view.
     *
     * @return view
     */
    public function users(){


        return view('layouts.admin.users');
    }


    /**
     *
     * Add user view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function addUserView(){




        return view('layouts.admin.add_user')->with('vue',true);
    }

    /**
     *
     * Create new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request){


        $this->validate($request,[

            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed',
            'password_confirmation'=>'required',
            'role'=>'required'

        ]);

        $user=User::create([

            'first_name'=>$request['first_name'],
            'last_name'=>$request['last_name'],
            'email'=>$request['email'],
            'password'=>bcrypt($request['password']),
            'verified'=>1

        ]);

        $user->assignRole($request['role']);


        return response()->json(array('success'=>true,'message'=>'You have successfully created a new user!','id'=>$user->id));
    }

    /**
     *
     * Datatables data for users.
     *
     * @return mixed
     */
    public function getUsers(){


        $users = User::select(['users.id as id', 'users.first_name as first_name', 'users.last_name as last_name', 'users.email as email','roles.name as role'])
            ->join('model_has_roles','users.id','=','model_has_roles.model_id')
            ->join('roles','roles.id','=','model_has_roles.role_id')
            ->get();


        return DataTables::of($users)
            ->addColumn('action', function ($user) {

                if($user->role=='employee'){

                    return '<a href="/admin/edit-user/'.$user->id.'"  id="'.$user->id.'" class="text-inverse tooltipsy p-r-10 edit" data-toggle="tooltip" title="" data-original-title="Edit"><i class="ti-marker-alt"></i></a><a href="/admin/rules/'.$user->id.'"  id="'.$user->id.'" style="padding-right: 5px;" class="text-inverse tooltipsy  rules" data-toggle="tooltip" title="" data-original-title="Business Rules"><i class="ti-calendar"></i></a> <a href="/admin/delete-user/'.$user->id.'" class="text-inverse tooltipsy delete" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a>';

                }else{

                    return '<a href="/admin/edit-user/'.$user->id.'"  id="'.$user->id.'" class="text-inverse tooltipsy p-r-10 edit" data-toggle="tooltip" title="" data-original-title="Edit"><i class="ti-marker-alt"></i></a> <a href="/admin/delete-user/'.$user->id.'" class="text-inverse tooltipsy delete" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a>';

                }

            })
            ->rawColumns(['action'])
            ->make(true);


    }

    /**
     *
     * Edit user view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function editUserView($id){


        return view('layouts.admin.edit_user')->with('id',$id)->with('vue',true);
    }


    /**
     *
     * Get user data.
     *
     * @param $id
     * @return mixed
     */
    public function getUser($id){

        $user=User::where('id','=',$id)->with('profile')->first();

        $roles=$user->roles()->get();


        $user->role=$roles[0]->name;


        return $user;
    }

    /**
     *
     * Update user data.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser($id,Request $request){

        $this->validate($request,[

            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:users,email,'.$id,
            'role'=>'required',

        ]);

        $user=User::find($id);
        $user->first_name=$request['first_name'];
        $user->last_name=$request['last_name'];
        $user->email=$request['email'];



        if($request['password']!=''){

            $user->password=bcrypt($request['password']);
        }
        $user->save();

        $user->syncRoles([$request['role']]);

        Profile::updateOrCreate(
            ['user_id'=>$id],
            ['address'=>$request['address'],'city'=>$request['city'],'state'=>$request['state'],
                'postal_code'=>$request['postal_code'],'notifications'=>$request['notifications'],'gcalendar'=>$request['gcalendar']]

        );

        return response()->json(array('success'=>true,'message'=>'User updated!'));


    }


    /**
     *
     * Delete user.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($id){


        if(User::where('id',$id)->exists()){

            $user=User::find($id);

            $roles=$user->roles()->get();

            foreach($roles as $role){

                $user->removeRole($role->name);

            }




            User::where('id',$id)->delete();



            return response()->json(array('success'=>true,'message'=>'User deleted!'));

        }


        return response()->json(array('success'=>false,'message'=>'User deletion failed!'));

    }

    /**
     *
     * Profile view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function profileView(){

        return view('layouts.admin.profile')->with('vue',true);
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


        return response()->json(array('success'=>true,'message'=>'Profile updated!'));


    }


    /**
     *
     * Events view.
     *
     * @return view
     */
    public function eventsView(){

        return view('layouts.admin.events');
    }

    /**
     *
     * Transactions view.
     *
     * @return view
     */
    public function transactionsView(){

        return view('layouts.admin.transactions');
    }

    /**
     *
     * Datatables data for events.
     *
     * @return mixed
     */
    public function getEvents(){



        $events=CustomEvent::select('custom_events.id as id',DB::raw('CONCAT(first_name," ",last_name) as employee'),'day as event_date',DB::raw('CONCAT(start,"-",end) as event_time'),'title as title','color as color','description as description')
            ->leftJoin('users','users.id','=','custom_events.employee_id')
            ->get();


        return DataTables::of($events)
            ->addColumn('action', function ($event) {

                return '<a href="/admin/delete-event/'.$event->id.'" class="text-inverse tooltipsy delete" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a>';


            })
            ->editColumn('color',function($item){

                return '<span style="color:white;background:'.$item->color.';  border-radius: 3px;padding: 3px;">'.$item->color.'</span>';
            })
            ->rawColumns(['color','action'])
            ->make(true);

    }


    /**
     *
     * Delete event.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEvent($id,Request $request){

        if($request['delete_text']=='DELETE'){

            if(CustomEvent::where('id',$id)->exists()){

                CustomEvent::where('id',$id)->delete();

                return response()->json(array('success'=>true,'message'=>'Event deleted!'));

            }

        }



        return response()->json(array('success'=>false,'message'=>'Event deletion failed!'));


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



        $transactions=Event::select('events.id as id','day as event_date',DB::raw('CONCAT(employees.first_name," ",employees.last_name) as employee'),DB::raw('CONCAT(users.first_name," ",users.last_name) as user'),'timeslot as event_time','duration as duration','price as price','status as status','events.created_at as date_created','events.completed_at as completed_at')
            ->leftJoin('users as users','users.id','=','events.user_id')
            ->leftJoin('users as employees','employees.id','=','events.employee_id')
            ->get();


        return DataTables::of($transactions)
            ->addColumn('action', function ($event) {

                if($event->status=='pending'){

                    return '<a href="/admin/delete-transaction/'.$event->id.'" class="text-inverse tooltipsy delete" title="" data-toggle="tooltip" data-original-title="Delete"><i class="ti-trash"></i></a>';


                }



            })
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
            ->rawColumns(['status','action'])
            ->make(true);

    }


    /**
     *
     * Delete transaction.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTransaction($id,Request $request){

        if($request['delete_text']=='DELETE'){

            if(Event::where('id',$id)->exists()){

                Event::where('id',$id)->delete();

                return response()->json(array('success'=>true,'message'=>'Transaction deleted!'));

            }

        }



        return response()->json(array('success'=>false,'message'=>'Transaction deletion failed!'));
    }

    /**
     *
     * Employee bussines rules view.(vue parameter sent so it loads vueJs library)
     *
     * @return view
     */
    public function employeeRulesView($id){


        return view('layouts.admin.rules')->with('id',$id)->with('vue',true);
    }

    /**
     *
     * Get employee calendar data.
     *
     * @param $id
     * @return mixed
     */
    public function getEmployeeCalendar($id){


        $calendar=Calendar::where('user_id','=',$id)->first();

        return $calendar;
    }

    /**
     *
     * Get employee calendar markers.
     *
     * @param $id
     * @param Request $request
     * @return array
     *
     */
    public function getEmployeeDaysMarkers($id,Request $request){


        $schedule=SpecialSchedule::where('user_id','=',$id)->with('specialTimeslots')->get();

        $days_markers=[];

        foreach($schedule as $item){

            if(!$item->available){

                $days_markers[]=array('id'=>$item->id,'date'=>$item->day,'backgroundColor'=>'yellow','borderColor'=>'yellow');
            }else{

                $days_markers[]=array('id'=>$item->id,'date'=>$item->day,'backgroundColor'=>'#ffb22b','borderColor'=>'#ffb22b');
            }



        }



        if(intval($request['rules'])==0){



            $events=Event::select(DB::raw('"event" as type'),'day as date',DB::raw('COUNT("day") as count'))->where('employee_id','=',$id)->distinct('date')->groupBy('day')->get()->toArray();
            $custom_events=CustomEvent::select(DB::raw('"custom_event" as type'),'day as date',DB::raw('COUNT("day") as count'))->where('employee_id','=',$id)->distinct('date')->groupBy('day')->get()->toArray();


            $events=array_merge($events,$custom_events);



            $empty_request=new Request();

            $counter=0;
            foreach($events as $event){

                $counter++;
                $customRequest=array('day'=>$event['date'],'increment'=>$request['increment'],'event_duration'=>$request['event_duration'],'weekday'=>date('N',strtotime($event['date'])),'today'=>$request['today']);

                $availability=$this->getHourAvailability($id,$empty_request,$customRequest);


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
     * Get employee calendar hours availability.
     *
     * @param $id
     * @param Request $request
     * @param null $customRequest
     * @return array
     *
     */
    public function getHourAvailability($id,Request $request,$customRequest=null){

        if($customRequest!=null){

            $request['day']=$customRequest['day'];
            $request['increment']=$customRequest['increment'];
            $request['event_duration']=$customRequest['event_duration'];
            $request['weekday']=intval($customRequest['weekday']);
            $request['today']=$customRequest['today'];

        }



        $schedule=SpecialSchedule::where('user_id','=',$id)->whereDate('day','=',$request['day'])->first();

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

            $schedule=Schedule::where('user_id','=',$id)->first();

            if($request['day']==Carbon::parse($request['today'])->format('Y-m-d')){



                $timeslots=Timeslot::where('schedule_id','=',$schedule->id)->where('day','=',$weekday)->where(DB::raw('CAST(timeslot as time)'),'>',Carbon::parse($request['today'])->format('G:i'))->pluck('timeslot')->toArray();
            }else{


                $timeslots=Timeslot::where('schedule_id','=',$schedule->id)->where('day','=',$weekday)->pluck('timeslot')->toArray();
            }





            $available_timeslots=[];

            $increment=$request['increment'];
            $event_duration=$request['event_duration'];

            $slots=$event_duration/15;


            $events=Event::where('employee_id','=',$id)->whereDate('day','=',$request['day'])->get();
            $custom_events=CustomEvent::where('employee_id','=',$id)->whereDate('day','=',$request['day'])->get();

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


        $events=Event::where('employee_id','=',$id)->whereDate('day','=',$request['day'])->get();
        $custom_events=CustomEvent::where('employee_id','=',$id)->whereDate('day','=',$request['day'])->get();


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
     * Get employee working days schedule.
     *
     * @param $id
     * @return mixed
     */
    public function getEmployeeSchedule($id){


        $schedule=Schedule::where('user_id','=',$id)->with('timeslots')->first();

        return $schedule;
    }


    /**
     *
     * Get employee special schedule.
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function getEmployeeSpecialSchedule($id,Request $request){


        $schedule=SpecialSchedule::where('user_id','=',$id)->whereDate('day','=',$request['day'])->with('specialTimeslots')->first();


        return $schedule;
    }

    /**
     *
     * Update business rules.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function employeeCalendarSettings($id,Request $request){


        $this->validate($request,[

            'increment'=>'required',
            'event_duration'=>'required',
            'price'=>'required',

        ]);

        $calendar=Calendar::updateOrCreate(
            ['user_id'=>$id],
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
     * Save working days shedule.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveEmployeeSchedule($id,Request $request){


        $calendar=Calendar::where('user_id','=',$id)->first();

        $schedule=Schedule::updateOrCreate(
            ['user_id'=>$id],
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
     * Save employee special schedule.
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveEmployeeSpecialSchedule($id,Request $request){

        $calendar=Calendar::where('user_id','=',$id)->first();

        $schedule=SpecialSchedule::updateOrCreate(
            ['day'=>Carbon::parse($request['day'])->format('Y-m-d')],
            ['user_id'=>$id,'calendar_id'=>$calendar->id,'available'=>$request['available']]

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
     * Get employees.
     *
     * @return mixed
     */
    public function getEmployees(){


        $employees=User::role('employee')->get();

        return $employees;

    }

    /**
     *
     * Get employee calendar availability.
     *
     * @param $id
     * @return array
     */
    public function getEmployeeCalendarAvailability($id){



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
     * Get users.
     *
     * @return array
     */
    public function employeeGetUsers(){

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
     * Get employees events.
     *
     * @param $id
     * @param Request $request
     * @return array
     */
    public function getEmployeeEvents($id,Request $request){

        $appointments=Event::where('employee_id','=',$id)->whereDate('day','=',$request['day'])->with('user')->get();
        $events=CustomEvent::where('employee_id','=',$id)->whereDate('day','=',$request['day'])->get();


        return array('appointments'=>$appointments,'events'=>$events);
    }


    /**
     *
     * Create an appointment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAppointment(Request $request){


        $event=Event::create([

            'user_id'=>$request['user']['id'],
            'employee_id'=>$request['employee_id'],
            'day'=>Carbon::parse($request['day'])->format('Y-m-d'),
            'timeslot'=>$request['timeslot'],
            'duration'=>$request['duration'],
            'price'=>$request['price'],
            'status'=>'pending',
        ]);

        $user=User::where('id','=',$request['user']['id'])->with('profile')->first();



        if($user->profile && $user->profile->notifications==1){

            $employee=User::where('id','=',$request['employee_id'])->first();
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
     * Create custom event.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createEvent(Request $request){

        $color_id=$this->getColorId($request['color']);

        CustomEvent::create([

            'employee_id'=>$request['employee_id'],
            'day'=>Carbon::parse($request['day'])->format('Y-m-d'),
            'start'=>$request['start'],
            'end'=>$request['end'],
            'title'=>$request['title'],
            'color'=>$request['color'],
            'color_id'=>$color_id,
            'description'=>$request['description']
        ]);

        $profile=Profile::where('user_id','=',$request['employee_id'])->first();


        if($profile->gcalendar && $profile->access_token!=''){

            $google=new GoogleCustomClient();
            $client = $google->client();
            $token=Profile::where('user_id','=',$request['employee_id'])->pluck('access_token')->first();
            $calendar_id=GoogleCalendar::where('user_id','=',$request['employee_id'])->pluck('calendar_id')->first();
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



}
