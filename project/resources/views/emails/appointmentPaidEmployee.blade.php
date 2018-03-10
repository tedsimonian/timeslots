@component('mail::message')
# Hi {{$employee->first_name}} {{$employee->last_name}},

User {{$user->first_name}} {{$user->last_name}} has successfully created and paid for an appointment at {{$event['day']}} {{$event['timeslot']}}.

Thanks,<br>

{{ config('app.name') }}
@endcomponent
