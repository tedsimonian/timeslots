@component('mail::message')
# Welcome to the site {{$user['first_name'].' '.$user['last_name']}}

Your registered email-id is {{$user['email']}} , Please click on the below link to verify your email account

@component('mail::button', ['url' => url('user/verify', $user->verifyUser->token)])
    Verify Email
@endcomponent

Thanks,<br>

{{ config('app.name') }}
@endcomponent
