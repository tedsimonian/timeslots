@component('mail::message')
# Hi {{$user->first_name }} {{$user->last_name}},

You have marked an appointment at {{$event['day']}} {{$event['timeslot']}} with {{$employee->first_name }} {{$employee->last_name}} for later payment . Please
pay in the next 2 hours or the appointment will be canceled.


Thanks,<br>

{{ config('app.name') }}
@endcomponent
