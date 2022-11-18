@extends('web.layout')
@section('title',w('Join us') )
@section('content')

    <section class="page-title style-two">
        <div class="container">
            <div class="content-box clearfix">
                <div class="title-box text-center wow fadeInUp animated">
                    <h1>JOIN Us </h1>

                </div>

            </div>
        </div>
    </section>

    <section class="contact-section">
        <div class="contact-bg">
            <div class="container">
                <div class="sec-title text-center wow fadeInUp animated">
                    <h2>
                        Join us as Service Provider
                    </h2>
                    <div class="text">
                        If you cannot find answer to your question in our FAQ, you can always contact us. We wil answer to
                        you shortly!
                    </div>
                </div>

            </div>
        </div>
        <div class="contact-form ">
            <div class="container">
                <!--- joinus for join page only --->
                <div class="contact-form-bg joinus wow fadeInUp animated">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="contact-form-area">
                                <div class="form-inner">
                                    <form method="post" action=""
                                          id="contact-form" class="default-form" novalidate="novalidate">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <input type="text" name="username" placeholder="Full Name " required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <div class="input_phone text-center">
                                                        <input type="text" name="mobile" value="" class="form-control text-center" placeholder="Mobile">
                                                        <span class="key_phone">+966</span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <input type="email" name="email" placeholder="Email" required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <input type="password" name="password" placeholder="Password" required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <select class="form-control type text-center js-example-basic-single" name="city">
                                                        <option value="1">Jeddah</option>
                                                        <option value="2">Abha</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <select class="form-control type text-center js-example-basic-single" name="bank">
                                                        <option value="1">Bank1 </option>
                                                        <option value="2">Bank2 </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <div class="input_phone text-center">
                                                        <input type="text" name="iban" placeholder="IBAN" required=""
                                                               aria-required="true">
                                                        <span class="key_iban">SA</span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <input type="text" name="swift" placeholder="SWIFT CODE" required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <input type="text" name="ID" placeholder="ID No. ( must be 10 digit ) "
                                                           required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <input type="text" name="Commercial"
                                                           placeholder="Commercial Registration No" required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="google-content">
                                                    <p class="mb-1">Pin your location on map </p>
                                                    <div id="googleMap" style="width:100%;height:204px;"></div>

                                                </div>

                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group mb-20px text-center">
                                                    <input class="form-control" name="id_no" placeholder="Type your location ( Building, Street, … etc )" value="">
                                                </div>

                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group mb-20px text-center">
                                                    <div class="fileUpload ">
                                                        <div class="position-relative mb-0">
                                                            <input type="file" name="file-7[]" id="file-7" placeholder="Upload File" class="inputfile" data-multiple-caption="{count}" multiple="">
                                                            <label for="file-7"><span class="archive-name"></span>
                                                                <span class="btn-inputfile">Comm. Registration</span>
                                                            </label>
                                                            <img src="images/surface1.svg" alt="">

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group mb-20px text-center">
                                                    <div class="fileUpload ">
                                                        <div class="position-relative mb-0">
                                                            <input type="file" name="file-8[]" id="file-8" placeholder="Upload File" class="inputfile" data-multiple-caption="{count}" multiple="">
                                                            <label for="file-8"><span class="archive-name"></span>
                                                                <span class="btn-inputfile">Upload ID </span>
                                                            </label>
                                                            <img src="images/surface1.svg" alt="">

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group message-btn text-center  w-100">
                                                    <button type="submit" class="theme-btn-two  w-100" name="submit-form">
                                                        Send Order
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>

@endsection
