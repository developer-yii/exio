@php
    $baseUrl = asset('frontend') . '/';
@endphp
@extends('frontend.layouts.app')

@section('title', $page->page_label)
@section('content')
    <!-- contact-us section -->
    <section class="bannerImg">
        <div class="container">
            <div class="bannerImgtext text-center">
                <h3>{{$page->title}}</h3>
            </div>
        </div>
    </section>

    <section class="contactUs termsOfpolicy">
        <div class="container">
            <div class="termsTextBox">
                {!! html_entity_decode(htmlspecialchars_decode($page->content)) !!}
            </div>
        </div>
    </section>
    <!-- contact-us section -->

@endsection