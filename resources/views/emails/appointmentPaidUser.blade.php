@component('mail::message')
# Hi {{$user->first_name}} {{$user->last_name}},

You have successfully created and paid an appointment at {{$event['day']}} {{$event['timeslot']}} with {{$employee->first_name }} {{$employee->last_name}}.


Thanks,<br>

{{ config('app.name') }}
@endcomponent
