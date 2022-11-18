<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>{{config('app.name')}}  - @yield('title')</title>

    <!-- Fav Icon -->
    <link rel="icon" href="{{asset_site('images/fav.ico')}}" type="image/x-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">
    <!-- Stylesheets -->
    <link href="{{asset_site('css/font-awesome-all.css')}}" rel="stylesheet">
    <link href="{{asset_site('css/flaticon.css')}}" rel="stylesheet">
    <link href="{{asset_site('css/owl.css')}}" rel="stylesheet">
    <link href="{{asset_site('css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset_site('css/animate.css')}}" rel="stylesheet">
    <link href="{{asset_site('css/style.css')}}" rel="stylesheet">
    @if(app()->getLocale()=="ar")
    <link href="{{asset_site('css/rtl.css')}}" rel="stylesheet">

    @endif
    <link href="{{asset_site('css/responsive.css')}}" rel="stylesheet">

</head>

<!-- page wrapper -->
<body class="boxed_wrapper">

<!-- preloader -->
<div class="preloader"></div>
<!-- preloader -->

<!-- main header -->
<header class="main-header home-5">
    <div class="outer-container {{!request()->routeIs('welcome')?"internalHeader":""}}">
        <div class="container">
            <div class="main-box clearfix">
                <div class="logo-box pull-left">
                    <figure class="logo"><a href="javascript:;"><img src="{{asset_site('images/logo.svg')}}" alt=""></a></figure>
                </div>
                <div class="menu-area pull-right clearfix">
                    <!--Mobile Navigation Toggler-->
                    <div class="mobile-nav-toggler">
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                    </div>
                    <nav class="main-menu navbar-expand-md navbar-light">
                        <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                            <ul class="navigation clearfix">
                                <li class="{{request()->routeIs('welcome')?"active":""}}"><a href="{{route('welcome')}}">{{w('Home')}}</a></li>
                                <li > <a href="{{route('welcome')}}">{{w('About')}}</a></li>
                                <li class="{{request()->routeIs('contact_us')?"active":""}}"><a href="{{route('contact_us')}}">{{w('Contact')}}</a></li>
                                <li class="{{request()->routeIs('join_us')?"active":""}}"><a href="{{route('join_us')}}">{{w('Join us')}} </a></li>
                                @if(app()->getLocale()=="ar")
                                <li><a href="{{route('switch-language',"en")}}"><img src="{{asset_site('images/eng.png')}}">En </a></li>
                                @else
                                <li><a href="{{route('switch-language',"ar")}}"><img src="{{asset_site('images/su.svg')}}">Ar </a></li>
                                    @endif
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!--sticky Header-->
    <div class="sticky-header">
        <div class="container clearfix">
            <figure class="logo-box"><a href="{{url('/')}}"><img src="{{asset_site('images/logo2.svg')}}" alt=""></a></figure>
            <div class="menu-area">
                <nav class="main-menu clearfix">
                    <!--Keep This Empty / Menu will come through Javascript-->
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- main-header end -->

<!-- Mobile Menu  -->
<div class="mobile-menu">
    <div class="menu-backdrop"></div>
    <div class="close-btn"><i class="fas fa-times"></i></div>

    <nav class="menu-box">
        <div class="nav-logo"><a href="index.html">
                <img src="{{w('images/logo.svg')}}" alt="" title=""></a></div>
        <div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
        <div class="contact-info">
            <h4>Contact Info</h4>
            <ul>
                <li>Chicago 12, Melborne City, USA</li>
                <li><a href="tel:+8801682648101">+88 01682648101</a></li>
                <li><a href="mailto:info@example.com">info@example.com</a></li>
            </ul>
        </div>
        <div class="social-links">
            <ul class="clearfix">
                <li><a href="#"><span class="fab fa-twitter"></span></a></li>
                <li><a href="#"><span class="fab fa-facebook-square"></span></a></li>
                <li><a href="#"><span class="fab fa-pinterest-p"></span></a></li>
                <li><a href="#"><span class="fab fa-instagram"></span></a></li>
                <li><a href="#"><span class="fab fa-youtube"></span></a></li>
            </ul>
        </div>
    </nav>
</div><!-- End Mobile Menu -->



 @yield('content')
<!-- testimonial-style-seven end -->
<footer class="main-footer style-five style-six">
    <div class="container">
        <div class="footer_bg" style="background: url({{asset_site('images/background/footer.png')}}) no-repeat ">
            <div class="footer-top">
                <div class="widget-section">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 footer-column">
                            <div class="about-widget footer-widget">
                                <figure class="footer-logo">
                                    <a href="index.html"><img src="{{asset_site('images/logo.svg')}}" alt=""></a>
                                </figure>
                                <div class="contact-widget footer-widget">

                                    <div class="widget-content">
                                        <ul class="contact-info clearfix">
                                            <li><a href="mailto:info@example.com">support@ping.sa</a></li>

                                            <li><a href="tel:+96650000000">+966.5000.0000</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 footer-column">
                            <div class="links-widget footer-widget">
                                <h4 class="widget-title">Quick Links</h4>
                                <div class="widget-content">
                                    <ul class="list clearfix">
                                        <li><a href="#">Home </a></li>
                                        <li><a href="#">App Features </a></li>
                                        <li><a href="#testimonials">Testimonials</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 footer-column">
                            <div class="links-widget footer-widget">
                                <h4 class="widget-title">About us</h4>
                                <div class="widget-content">
                                    <ul class="list clearfix">
                                        <li><a href="contact.html">Contact us </a></li>
                                        <li><a href="#download">Download App </a></li>
                                        <li><a href="#">Login</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-6 footer-column">
                            <div class="contact-widget footer-widget">
                                <h4 class="widget-title">Subscribe Us</h4>
                                <div class="mail-box">
                                    <form action="#" method="post">
                                        <div class="form-group">
                                            <input type="email" name="email" placeholder="Enter Your Email" required="">
                                            <button type="submit"><i class="fas fa-arrow-right"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom clearfix">
                <div class="row">

                    <div class="col-lg-6 col-md-12 order-lg-12">
                        <ul class="footer-nav ">
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Conditions</a></li>
                            <li><a href="#">FAQs</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-12 order-lg-1">
                        <div class="copyright">
                            <p>
                                © 2021 <a href="#">Ping App.</a> All rights reserved | Powered by <a
                                    href="#">ShareTech</a>
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</footer>


<!--Scroll to top-->
<button class="scroll-top scroll-to-target" data-target="html">
    <span class="fa fa-arrow-up"></span>
</button>


<!-- jequery plugins -->
<script src="{{asset_site('js/jquery.js')}}"></script>
<script src="{{asset_site('js/popper.min.js')}}"></script>
<script src="{{asset_site('js/bootstrap.min.js')}}"></script>
<script src="{{asset_site('js/owl.js')}}"></script>
<script src="{{asset_site('js/wow.js')}}"></script>
<script src="{{asset_site('js/validation.js')}}"></script>
<script src="{{asset_site('js/appear.js')}}"></script>
<script src="{{asset_site('js/scrollbar.js')}}"></script>
<script src="{{asset_site('js/tilt.jquery.js')}}"></script>

<!-- main-js -->
<script src="{{asset_site('js/script.js')}}"></script>

</body><!-- End of .page_wrapper -->

</html>
