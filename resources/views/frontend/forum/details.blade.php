@php
    $baseUrl = asset('frontend') . '/';
@endphp
@extends('frontend.layouts.app')

@section('title', 'Start Discuss')
@section('content')
      <!-- forum sec end -->
      <section class="bannerSky">
        <div class="container">
            <div class="forumBoxSky">
                <div class="forumText">
                    <h4>Exio Forum</h4>
                </div>
                <div class="askQuestion">
                    @if(auth()->check())
                        <a href="javascript:void(0)" class="btnAnsAdd" data-bs-toggle="modal" data-bs-target="#answerModal" data-id="{{ $forum->id }}">Add Answer</a>
                    @else
                        <a href="javascript:void(0)" class="askQuestionBtn">Add Answer</a>
                    @endif

                </div>
            </div>
        </div>
    </section>

    <section class="mainFroum">
        <div class="container">
            <div class="forumContent">
                <div class="row">
                    <div class="col-12">
                        <div class="leftQueBox">
                            <div class="forumBoxMain">
                                <div class="comQuebox">
                                    <span class="mb-1">{{ $forum->user->name }}</span>
                                    <h5>
                                        Q:  {{ $forum->question }}
                                    </h5>
                                    @if($forum->answers->count() == 0)
                                        <p>No answers yet</p>
                                    @endif
                                    @foreach($forum->answers as $answer)
                                        <p>
                                            <span>{{ $answer->user->name }} :</span>
                                            {{ $answer->answer }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('forum') }}" class="btn btn-primary mt-2">Back</a>
            </div>
        </div>
    </section>
     <!-- forum sec end -->
@endsection
@section('modal')
    {{-- ans modal --}}
    @include('frontend.include.forum_answer_modal')
@endsection
@section('js')
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script type="text/javascript">

        function onloadCallback() {
            var recaptcha_key = "{{ env('GOOGLE_RECAPTCHA_KEY') }}";

            // Render reCAPTCHA for the question modal
            if (document.getElementById('recaptcha')) {
                grecaptcha.render('recaptcha', {
                    'sitekey': recaptcha_key
                });
            }

            // Render reCAPTCHA for the answer modal
            if (document.getElementById('ans-recaptcha')) {
                grecaptcha.render('ans-recaptcha', {
                    'sitekey': recaptcha_key
                });
            }
        }

        var qtySubmitUrl = "{{ route('question.submit') }}";
        var ansSubmitUrl = "{{ route('answer.submit') }}";

    </script>
    <script src="{{ frontendPageJsLink('forum.js') }}"></script>
@endsection
