@component('mail::message')
# Hi {{$user->first_name}} {{$user->last_name}}

The appointment at {{$event['day']}} {{$event['timeslot']}} has been marked as failed.


Thanks,<br>

{{ config('app.name') }}
@endcomponent
