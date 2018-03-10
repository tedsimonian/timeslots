@component('mail::message')
# Hi {{$employee->first_name }} {{$employee->last_name}},

User: {{$user->first_name }} {{$user->last_name}} has marked an appointment {{$event['day']}} {{$event['timeslot']}} for later payment at.


Thanks,<br>

{{ config('app.name') }}
@endcomponent
