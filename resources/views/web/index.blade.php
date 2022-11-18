@extends('web.layout')
@section('title',w('Home'))
@section('content')

    <!-- banner-section -->
    <section class="banner-style-five">
        <div class="pattern-layer" style="background-image: url({{asset_site('images/icons/Bg_Shape.svg')}});"></div>
        <div class="anim-icons">
            <div class="icon icon-1"><img src="{{asset_site('images/icons/circle.svg')}}" alt=""></div>
            <div class="icon icon-2 rotate-me"><img src="{{asset_site('images/icons/xIocn.svg')}}" alt=""></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 content-column">
                    <div class="content-box">
                        <span>
                            We’r Awesome
                        </span>
                        <h1>Ping App<br>
                            That Help You to
                            Get Rewards</h1>
                        <div class="btn-box">
                            <a href="#" class="theme-btn-two">
                                Download App
                            </a>
                            <a href="#" class="more">
                                More About
                                <i class="fas fa-arrow-right"></i></a>

                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-6 col-md-12 col-sm-12 image-column">
                    <div class="image-box clearfix">
                        <div class="icon-box">
                            <div class="icon icon-1 rotate-me"><img src="{{asset_site('images/icons/xIocn.svg')}}" alt=""></div>
                            <div class="icon icon-2"><img src="{{asset_site('images/icons/ellips.svg')}}" alt=""></div>
                            <div class="icon icon-4"><img src="{{asset_site('images/icons/iconAnim.svg')}}" alt=""></div>
                            <div class="icon icon-44 rotate-me"><img src="{{asset_site('images/icons/square.png')}}" alt=""></div>
                            <div class="icon icon-5"><img src="{{asset_site('images/icons/tri.svg')}}" alt=""></div>
                        </div>
                        <figure class="image image-1 wow slideInLeft" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <img src="{{asset_site('images/background/hero.png')}}" alt="">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner-section end -->


    <!-- download-apps -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                    <div id="image_block_16">
                        <div class="image-box">
                            <figure class="image image-1 wow slideInLeft" data-wow-delay="300ms" data-wow-duration="1500ms">
                                <img src="{{asset_site('images/screen/screen1.png')}}" alt=""></figure>
                            <figure class="image image-2 wow slideInLeft" data-wow-delay="600ms" data-wow-duration="1500ms">
                                <img src="{{asset_site('images/screen/screen2.png')}}" alt=""></figure>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                    <div id="content_block_16">
                        <div class="content-box wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <div class="sec-title">
                                <span>
                                    More About <b>Ping</b>
                                </span>
                                <h2>
                                    ping is the best choice for your online ordering
                                </h2>
                            </div>
                            <div class="text">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                                invidunt ut labore et dolore magna aliquyam erat,
                                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd
                                gubergren, no sea takimata sanctus..
                            </div>
                            <div class="download-btn">
                                <a href="#" class="app-store-btn">
                                    <i class="fab fa-apple"></i>
                                    <span>Download on the</span>
                                    App Store
                                </a>
                                <a href="#" class="google-play-btn">
                                    <i class="fab fa-android"></i>
                                    <span>Get on it</span>
                                    Google Play
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <section class="achievements">
                <div class="container">
                    <div class="row align-items-center  wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                        <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                            <div id="content_block_13">
                                <div class="content-box">
                                    <div class="sec-title">
                                        <h2>Our achievements
                                            & success</h2>
                                    </div>
                                    <div class="text">
                                        Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed
                                        diam voluptua. At vero .
                                    </div>
                                    <div class="btn-box">
                                        <a href="#" class="theme-btn-two">
                                            Download App
                                        </a>
                                        <a href="#" class="more">
                                            More About
                                            <i class="fas fa-arrow-right"></i></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 inner-column">
                            <div id="content_block_14">
                                <div class="inner-content">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                                            <div class="feature-block-one js-tilt">
                                                <div class="inner-box blue-box ">
                                                    <div class="hover-content"></div>
                                                    <div class="icon-box">
                                                        <img src="{{asset_site('images/icons/categories.svg')}}" alt="icon">
                                                    </div>
                                                    <h2 class="count-box">
                                                        <span class="count-text" data-speed="1500" data-stop="100">0</span>
                                                    </h2>
                                                    <h5>Categories</h5>
                                                </div>
                                            </div>
                                            <div class="feature-block-one js-tilt">
                                                <div class="inner-box blue-dark">
                                                    <div class="hover-content"></div>
                                                    <div class="icon-box">
                                                        <img src="{{asset_site('images/icons/to-do-list.svg')}}" alt="icon">
                                                    </div>
                                                    <h2 class="count-box">
                                                        <span class="count-text" data-speed="1500" data-stop="100">0</span>
                                                    </h2>
                                                    <h5>points</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                                            <div class="feature-block-one js-tilt">
                                                <div class="inner-box blue-dark">
                                                    <div class="hover-content"></div>
                                                    <div class="icon-box">
                                                        <img src="{{asset_site('images/icons/shopping-bag.svg')}}" alt="icon">
                                                    </div>
                                                    <h2 class="count-box">
                                                        <span class="count-text" data-speed="1500" data-stop="100">0</span>
                                                    </h2>
                                                    <h5>items</h5>
                                                </div>
                                            </div>
                                            <div class="feature-block-one js-tilt">
                                                <div class="inner-box blue-box ">
                                                    <div class="hover-content"></div>
                                                    <div class="icon-box">
                                                        <img src="{{asset_site('images/icons/man.svg')}}" alt="icon">

                                                    </div>
                                                    <h2 class="count-box">
                                                        <span class="count-text" data-speed="1500" data-stop="100">0</span>
                                                    </h2>
                                                    <h5>active customers</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </section>
    <!-- download-apps end -->

    <section class="downloadApp-section" id="download">

        <div class="bg-column" style="background-image: url({{asset_site('images/background/download.png')}});"></div>
        <div class="about-sticky wow fadeInUp animated">
            <h5>
                Learn More
            </h5>
            <h4>
                <a href="#">
                    More about us <i class="fa fa-arrow-right"></i>
                </a>
            </h4>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 video-column"></div>
                <div class="col-lg-8 col-md-12 col-sm-12 content-column">
                    <div id="content_block_04">
                        <div class="content-box wow fadeInUp animated">
                            <div class="sec-title">
                                <h2>Ping build for any platform.
                                    iOS Or Android</h2>
                            </div>
                            <div class="text">
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                                invidunt.
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                                    <div class="single-item">
                                        <div class="icon-box">
                                            <i class="fas fa-sync"></i>
                                        </div>
                                        <h5>
                                            Always in Sync
                                        </h5>
                                        <div class="text">
                                            Don’t worry about the data,
                                            always be synchronized
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                                    <div class="single-item wow fadeInUp animated" data-wow-delay="300ms" data-wow-duration="1500ms">
                                        <div class="icon-box">
                                            <i class="fas fa-sync"></i>
                                        </div>
                                        <h5>
                                            Always in Sync
                                        </h5>
                                        <div class="text">
                                            Don’t worry about the data,
                                            always be synchronized
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="download-btn">
                                <a href="#" class="app-store-btn">
                                    <i class="fab fa-apple"></i>
                                    <span>Download on the</span>
                                    App Store
                                </a>
                                <a href="#" class="google-play-btn">
                                    <i class="fab fa-android"></i>
                                    <span>Get on it</span>
                                    Google Play
                                </a>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- testimonial-style-seven -->
    <section class="testimonial" id="testimonials">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6 col-md-12 user-column">
                    <div class="content-box wow fadeInUp animated">
                        <div class="sec-title"><h2>Our customers<br>
                                feedback!</h2></div>
                        <div class="text">
                            Business is the activity of making one living or making
                            money producingor buying and selling products. Simply put it is any activity or enterprise
                            entered into for profit.
                        </div>

                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 carousel-column">
                    <div class="testimonial-inner">
                        <div class="testimonial-carousel-2 owl-carousel owl-theme">
                            <div class="testimonial-content">
                                <div class="inner-box">
                                    <div class="author-info">
                                        <figure class="image-box"><img src="{{asset_site('images/icons/05.svg')}}" alt=""></figure>
                                        <h5 class="name">Mohammed Rami</h5>
                                        <span class="designation">Riyadh, Twitter</span>
                                    </div>
                                    <ul class="rating clearfix">
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star-half-alt"></i></li>
                                    </ul>
                                    <div class="text">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam
                                        nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.
                                    </div>

                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="inner-box">
                                    <div class="author-info">
                                        <figure class="image-box"><img src="{{asset_site('images/icons/05.svg')}}" alt=""></figure>
                                        <h5 class="name">Mohammed Rami</h5>
                                        <span class="designation">Riyadh, Twitter</span>

                                    </div>
                                    <ul class="rating clearfix">
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                    </ul>
                                    <div class="text">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam
                                        nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.
                                    </div>

                                </div>
                            </div>
                            <div class="testimonial-content">
                                <div class="inner-box">
                                    <div class="author-info">
                                        <figure class="image-box"><img src="{{asset_site('images/icons/05.svg')}}" alt=""></figure>
                                        <h5 class="name">Mohammed Rami</h5>
                                        <span class="designation">Riyadh, Twitter</span>

                                    </div>
                                    <ul class="rating clearfix">
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                    </ul>
                                    <div class="text">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam
                                        nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- testimonial-style-seven end -->
@endsection
