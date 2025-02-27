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

    <section class="aboutUs">
        <div class="container">
            <div class="aboutUsBox">
                <div class="contentAbout">
                    {!! html_entity_decode(htmlspecialchars_decode($page->content)) !!}

                    <div class="endtextBox">
                        <h3>Connect with us:</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard.</p>
                        <ul>
                            <li><a href="https://www.facebook.com/" target="_blank"><i class="fa-brands fa-facebook"></i></a></li>
                            <li><a href="https://x.com/" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                            <li><a href="https://www.instagram.com/" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                            <li><a href="https://www.linkedin.com/" target="_blank"><i class="fa-brands fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact-us section -->
@endsection
