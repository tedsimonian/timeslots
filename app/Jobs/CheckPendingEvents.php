<?php

namespace App\Jobs;

use App\Event;
use App\Mail\NotifyEmployeeFailed;
use App\Mail\NotifyUserFailed;
use App\Profile;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class CheckPendingEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pending_events=Event::where('status','=','pending')->whereRaw('TIMESTAMPDIFF(MINUTE,created_at,NOW()) > 120')->whereRaw('NOW()>created_at')->get();

        foreach($pending_events as $event){

            //$event->status='failed';
            //$event->save();

            $user=Profile::where('user_id','=',$event->user_id)->where('notifications','=',1)->first();
            $employee=Profile::where('user_id','=',$event->employee_id)->where('notifications','=',1)->first();

            if($user){

                $user_info=User::where('id','=',$event->user_id)->first();

                Mail::to($user_info->email)->send(new NotifyUserFailed('Event Failed',$event,$user_info));


            }

            if($employee){


                $employee_info=User::where('id','=',$event->employee_id)->first();
                Mail::to($employee_info->email)->send(new NotifyEmployeeFailed('Event Failed',$event,$employee_info));

            }


        }
    }
}
