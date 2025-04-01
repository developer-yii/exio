@php
    use Illuminate\Support\Str;
@endphp
@component('mail::message')

# You have received a forum notification.

## **Forum Notification**

**Type:** {{ $validatedData['type'] }}  
**Submitted By:** {{ $validatedData['user_name'] }}  
**Content:** {{ Str::limit($validatedData['content'], 300, '...') }}  

@if($validatedData['type'] == 'New Question')
    @component('mail::button', ['url' => route('forum'), 'color' => 'primary'])
        View Forum
    @endcomponent
@else
    @component('mail::button', ['url' => route('admin.forum.answer', ['id' => $validatedData['forum_id']]), 'color' => 'primary'])
        View Answer
    @endcomponent
@endif

Thanks,  
{{ config('app.name') }}

@endcomponent
