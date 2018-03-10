@component('mail::message')
# Hi {{$user->first_name}} {{$user->last_name}}

You havent paid for the appointment at {{$event['day']}} {{$event['timeslot']}} in the agreed time and it is now been marked as failed.



Thanks,<br>

{{ config('app.name') }}
@endcomponent
