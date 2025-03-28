@php
    $baseUrl = asset('frontend') . '/';
@endphp
<footer class="{{ $footer_display }}">
  <div class="footer-part">
    <div class="container">
      <div class="footer-box">
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="ft-logo">
              <a href="{{route('front.home')}}"><img src="{{$baseUrl}}assest/images/white-logo.png" alt="white-logo" /></a>
              <p>EXIO is a forward-thinking real estate firm dedicated to transforming spaces into dream homes. With a
                commitment to quality and innovation, we provide personalized services to help you find the perfect
                property that suits your lifestyle.</p>
            </div>
          </div>
          <div class="col-lg-2 col-md-6">
            <div class="quickLinks">
              <h5>Quick Links</h5>
              <ul>
                <li>
                  <a href="{{route('front.home')}}" class="active">Home</a>
                </li>
                <li>
                  <a href="{{ route('about-us') }}">About Us</a>
                </li>
                <li>
                  <a href="{{ route('news') }}">News</a>
                </li>
                <li>
                  <a href="{{route('contact-us')}}">Contact Us</a>
                </li>
                <li>
                  <a href="#">Sitemap</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-2 col-md-6">
            <div class="getInTouch">
              <h5>Get In Touch</h5>
              <ul>
                <li>
                  <p>Beside Sakar 25, Nr. Lorem Ipsum Road, Sarkhej Gandhinagar Highway, Ahmedabad, Gujarat – 380015</p>
                </li>
                <li>
                  <a href="tel:+91 79 87654321">+91 79 87654321</a>
                </li>
                <li>
                  <a href="mailto:sales@exio.com">sales@exio.com</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="ft-subscribe">
              <h5>Subscribe To us</h5>
              <p>EXIO is a forward-thinking real estate firm dedicated to transforming spaces into dream homes. With a
                commitment to quality and innovation, we provide personalized services to help you find the perfect
                property that suits your lifestyle.</p>
                <form id="subscribe">
                  @csrf
                  <div class="searcFt">
                    <input type="text" placeholder="Enter your email" name='email' id="email">
                    <button class="btn btnSend" type="submit"><i class="bi bi-send"></i></button>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
      <div class="copyRight">
        <div class="reservText">
          <p>© 2024 www.exio.com. All Rights Reserved.</p>
        </div>
        <div class="privacy">
          <ul>
            <li><a href="{{route('terms-condition')}}">Terms of Use</a></li>
            <li><a href="{{route('privacy-policy')}}">Privacy Policy</a></li>
            <li><a href="javascript:void(0)">Disclaimer</a></li>
          </ul>
        </div>
        <div class="iconBox">
          <ul>
            <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</footer>
