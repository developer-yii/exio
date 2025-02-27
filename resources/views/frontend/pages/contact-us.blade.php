@php
    $baseUrl = asset('frontend') . '/';
@endphp
@extends('frontend.layouts.app')

@section('title', 'Contact Us')
@section('content')
    <!-- contact-us section -->
    <section class="bannerImg">
        <div class="container">
            <div class="bannerImgtext text-center">
                <h3>Contact Us</h3>
            </div>
        </div>
    </section>

    <section class="contactUs">
        <div class="container">
            <div class="contactUsBox">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="contentBox">
                            <div class="toptextBox">
                                <p>Email, call or complete the form to learn how Exio can solve your problem.</p>
                                <p>info@exio.com</p>
                                <p>987-654-654</p>
                            </div>
                            <div class="centerTextbox">
                                <div class="box-box">
                                    <h6>Customer Support</h6>
                                    <p>Our support team is available around the clock to address any concerns or queries you
                                        may have.</p>
                                </div>
                                <div class="box-box">
                                    <h6>Feedback and Suggestions</h6>
                                    <p>We value your feedback and are continuously working to improve Exio. your input is
                                        crucial in shaping the future of Exio.</p>
                                </div>
                                <div class="box-box">
                                    <h6>Feedback and Suggestions</h6>
                                    <p>We value your feedback and are continuously working to improve Exio. your input is
                                        crucial in shaping the future of Exio.</p>
                                </div>
                            </div>
                            <div class="endtextBox">
                                <p><strong>Real Estate Professionals (REPs):</strong> "Real Estate Professionals," or "REPs"
                                    are users who have created accounts (free or paid) with us to sell or rent real estate,
                                    avail our advertising services, and avail our other Services that we direct towards the
                                    professional real estate community. REPs include landlords, agents, developers,
                                    institutional property consultants, mortgage professionals and other service providers.
                                </p>
                                <p>"Real Estate Professionals," or "REPs" are users who have created accounts (free or paid)
                                    with us to sell or rent real estate, avail our advertising services, and avail our other
                                    Services that we direct towards the professional real estate community. REPs include
                                    landlords, agents, developers, institutional property consultants, mortgage
                                    professionals and other service providers.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="mainWhiteBox">
                            <form id="contactform">
                                @csrf
                                <div class="title">
                                    <h5>Sign In</h5>
                                    <p>Welcome! Please enter your details.</p>
                                </div>
                                <div class="form-group">
                                    <label class="labelClass" for="">Name</label>
                                    <input class="inputClass" type="text" name="name" id="name" placeholder="Enter your name">
                                    <span class="error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="labelClass" for="">Email</label>
                                    <input class="inputClass" type="text" name="email" id="email" placeholder="Enter your Email">
                                    <span class="error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="labelClass" for="">Mobile Number</label>
                                    <input class="inputClass" type="text" name="mobile_number" id="mobile_number" placeholder="Enter your mobile number">
                                    <span class="error"></span>
                                </div>
                                <div class="form-group">
                                    <label class="labelClass" for="">Message</label>
                                    <textarea class="inputClass" name="message" id="message" placeholder="How can we help?"></textarea>
                                    <span class="error"></span>
                                </div>
                                <div class="form-group">
                                    <div class="g-recaptcha-response" id="recaptcha"></div>
                                    <span class="error"></span>
                                </div>
                                <div class="signIn">
                                    <input type="submit" class="btn btnsignIn" name="submit" value="Submit" />
                                </div>
                                <div class="terms">
                                    <p>Buy contacting us, you agree to out <a href="{{route('terms-condition')}}">
                                        Terms of services</a> and <a href="{{route('privacy-policy')}}">Privacy Policy</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact-us section -->

@endsection

@section('js')
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script type="text/javascript">
        var recaptcha_key = "{{ env('GOOGLE_RECAPTCHA_KEY') }}";
        var recaptcha = "";

        function onloadCallback() {
            if ($('#recaptcha').length) {
                recaptcha = grecaptcha.render('recaptcha', {
                    'sitekey': recaptcha_key
                });
            }
        }
    </script>

    <script type="text/javascript">
        var contactUrl = "{{ route('contact.submit') }}";
    </script>
    <script src="{{ frontendPageJsLink('contact.js') }}"></script>
@endsection
