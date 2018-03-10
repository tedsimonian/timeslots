<?php

namespace App\Http\Controllers;

use App\User;
use App\VerifyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::user()->hasRole('admin')){

            return App::call('App\Http\Controllers\AdminController@home');
        }

        if(Auth::user()->hasRole('employee')){

            return App::call('App\Http\Controllers\EmployeeController@home');
        }

        if(Auth::user()->hasRole('user')){

            return App::call('App\Http\Controllers\UserController@home');
        }

    }

    /**
     *
     * Verify the user or show warning if the user is not verified.
     *
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyUser($token)
    {


        $verifyUser = VerifyUser::where('token', $token)->first();



        if(isset($verifyUser) ){
            $user = $verifyUser->user;
            if(!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                $status = "Your e-mail is verified. You can now login.";
            }else{
                $status = "Your e-mail is already verified. You can now login.";
            }
        }else{


            return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
        }


        return redirect('/login')->with('status', $status);
    }



    public function resendEmail($id){


        $user=User::where('id','=',$id)->first();
        do {

            $token = str_random(10);
        } while (VerifyUser::where("token", "=", $token)->first() instanceof VerifyUser);

        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        Mail::to($user->email)->send(new \App\Mail\VerifyUser($user));

        return redirect('/user/home')->with('resend',true);


    }

}
