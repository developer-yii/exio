@component('mail::message')

<h1>You have received a contact inquiry.</h1>

## Inquiry Details

<strong>Name:</strong> {{ $validatedData['name'] }}<br>
<strong>Email:</strong> {{ $validatedData['email'] }}<br>
<strong>Mobile Number:</strong> {{ $validatedData['mobile_number'] }}<br>
<strong>Message:</strong> {{ ($validatedData['message']) }}

Thanks,<br>
{{ config('app.name') }}

@endcomponent
