@component('mail::message')

<h1>You have received a forum notification.</h1>

## Forum Notification

<strong>Type:</strong> {{ $validatedData['type'] }}<br>
<strong>Content:</strong> {{ $validatedData['content'] }}<br>
<strong>Submitted By:</strong> {{ $validatedData['user_name'] }}<br>

@if($validatedData['type'] == 'New Question')
@component('mail::button', ['url' => route('admin.forum'), 'color' => 'primary'])
@else
@component('mail::button', ['url' => route('admin.forum.answer', $validatedData->forum_id), 'color' => 'primary'])
@endif

Thanks,<br>
{{ config('app.name') }}

@endcomponent
