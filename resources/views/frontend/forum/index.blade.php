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
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#askModal">Ask Question?</a>
                    @else
                        <a href="javascript:void(0)" class="askQuestionBtn">Ask Question?</a>
                    @endif
                </div>

                {{-- <div class="askQuestion">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#askModal">Ask Question?</a>
                </div> --}}
            </div>
        </div>
    </section>

    <section class="mainFroum">
        <div class="container">
            <div class="forumContent">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="leftQueBox">
                            <form id="searchForm" method="GET" action="{{ route('forum') }}">
                                <div class="topSearch">
                                    <input type="search" name="search" id="search" placeholder="Search" value="{{ request('search') }}">
                                    <button type="submit" class="searchBtn">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                            <div class="forumBoxMain">
                                @if($forums->count() > 0)
                                    @foreach($forums as $forum)
                                        <div class="comQuebox">
                                            <span class="mb-1">{{ $forum->user->name }}</span>
                                            <h5>
                                                <a href="{{ route('forum-details', ['id' => $forum->id ])}}">
                                                    Q: {{ $forum->question }}
                                                </a>
                                            </h5>
                                            @if($forum->answers->count() > 0)
                                                @php
                                                    $forumAnswer = $forum->answers->first();
                                                @endphp
                                                <p class="four-line-text"><span>{{ $forumAnswer->user->name }} : </span> {{ $forumAnswer->answer }}
                                                </p>
                                            @else
                                                <p>No answers yet</p>
                                            @endif
                                            @if($forum->answers->count() > 1)
                                                <a href="j{{ route('forum-details', ['id' => $forum->id ])}}" class="answerMore">+{{$forum->answers->count()-1}} Answer</a>
                                            @endif
                                            @if(auth()->check())
                                                <button class="btn btnAnsAdd" data-bs-toggle="modal" data-bs-target="#answerModal" data-id="{{ $forum->id }}">Add Answer</button>
                                            @else
                                                <button class="btn btnAnsAdd askQuestionBtn">Add Answer</button>
                                            @endif

                                        </div>
                                    @endforeach
                                @else
                                    <p>No discussion found</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="rightQueBox">
                            <div class="topText">
                                <h4>Top Questions</h4>
                            </div>
                            <div class="forumBoxMain">
                                @if($topForums->count() > 0)
                                    @foreach($topForums as $topForum)
                                        <div class="comQuebox">
                                            <span class="mb-1">{{ $topForum->user->name }}</span>
                                            <h5><a href="{{ route('forum-details', ['id' => $topForum->id ]) }}">Q: {{ $topForum->question }}</a></h5>
                                            @php
                                                $topForumAnswer = $topForum->answers->first();
                                            @endphp
                                            @if($topForumAnswer)
                                                <p class="four-line-text">
                                                    <span>{{ $topForumAnswer->user->name }} : </span> {{ $topForumAnswer->answer }}
                                                </p>
                                            @else
                                                <p>No answers yet.</p>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pagination Section start--}}
        @if ($forums->hasPages())
            @include('frontend.include.pagination', ['propertyPages' => $forums])
        @endif
        {{-- Pagination Section end--}}

    </section>
    <!-- forum sec end -->
@endsection
@section('modal')

{{-- question modal --}}
<div class="modal fade askModal" id="askModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="questionForm">
                @csrf
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5>Ask Question</h5>
                    <div class="enterQue form-group mb-1">
                        <textarea name="question" id="question" placeholder="Add your question here" rows="5" cols="50"></textarea>
                        <p style="margin-bottom: 0px !important;">Characters remaining: <span id="questionCharCount">250/250</span></p>
                        <span class="error"></span>
                    </div>

                    <div class="form-group">
                        <div class="g-recaptcha-response" id="recaptcha"></div>
                        <span class="error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

