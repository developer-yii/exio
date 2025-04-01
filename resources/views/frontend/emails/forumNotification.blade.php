@component('mail::message')

<h1>You have received a forum notification.</h1>

## Forum Notification

<strong>Type:</strong> {{ $validatedData['type'] }}<br>
<strong>Content:</strong> {{ $validatedData['content'] }}<br>
<strong>Submitted By:</strong> {{ $validatedData['user_name'] }}<br>


@component('mail::button', ['url' => $validatedData['url'], 'color' => 'primary'])
View
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@endcomponent
