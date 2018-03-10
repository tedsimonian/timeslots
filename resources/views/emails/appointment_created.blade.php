@component('mail::message')
# Hi {{$user->first_name}} {{$user->last_name}},

Employee: {{$employee->first_name }} {{$employee->last_name}} has created an appointment for you at {{$event['day']}} {{$event['timeslot']}}.
Please pay for the appointment in the next 2 hours or it will be canceled.


Thanks,<br>

{{ config('app.name') }}
@endcomponent
