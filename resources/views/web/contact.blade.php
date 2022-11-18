@extends('web.layout')
@section('title',w('Contact') )
@section('content')

    <section class="page-title style-two">
        <div class="container">
            <div class="content-box clearfix">
                <div class="title-box text-center">
                    <h1>CONTACT </h1>
                    <ul class="bread-crumb">
                        <li><a href="index.html">Home</a></li>

                        <li>Contact us </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <section class="contact-section">
        <div class="contact-bg">
            <div class="container">
                <div class="sec-title text-center">
                    <h2>
                        Still have a questions?
                    </h2>
                    <div class="text">
                        If you cannot find answer to your question in our FAQ, you can always contact us. We wil answer to you shortly!
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 big-column">
                        <div class="info-content centred">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                                    <div class="single-info-box">
                                        <figure class="icon-box"><img src="images/icons/phone.svg" alt=""></figure>
                                        <div class="phone"><a href="tel:0665184575181">+1 (966) 5000-0000</a></div>
                                        <div class="text">We are always happy to help.</div>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                                    <div class="single-info-box">
                                        <figure class="icon-box"><img src="images/icons/email.svg" alt=""></figure>
                                        <div class="phone">
                                            <span>Sale | </span>
                                            <a href="mailto:info@example.com">sales@pingapp.co</a>
                                        </div>
                                        <div class="phone">
                                            <span>Support | </span>
                                            <a href="mailto:info@example.com">info@pingap.co</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 info-column">
                                    <div class="single-info-box">
                                        <figure class="icon-box"><img src="images/icons/maps-and-location.svg" alt=""></figure>
                                        <div class="phone"><a href="#">2Riyadh, Saudi Arabia </a></div>
                                        <div class="text">Abdulaziz king st. , Building 0201</div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="contact-form">
            <div class="container">
                <div class="contact-form-bg">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="contact-form-area">
                                <div class="form-inner">
                                    <form method="post" action=""
                                          id="contact-form" class="default-form" novalidate="novalidate">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <label> Full Name * </label>
                                                    <input type="text" name="username" placeholder="Full Name " required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <label> Email * </label>
                                                    <input type="email" name="email" placeholder="Email" required=""
                                                           aria-required="true">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <label>Mobile</label>
                                                    <div class="input_phone text-center">
                                                        <input type="text" name="mobile" value="" class="form-control text-center" placeholder="Mobile">
                                                        <span class="key_phone">+966</span>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <label> City * </label>
                                                    <input type="text" name="subject" placeholder="Subject" required=""
                                                           aria-required="true">
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 column">
                                                <div class="form-group">
                                                    <label>
                                                        Your message*
                                                    </label>
                                                    <textarea name="message" placeholder="Type your message…."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 column">
                                                <p>
                                                    By submitting this form you agree to our terms and conditions and
                                                    our Privacy Policy which explains how we may collect, use and disclose your personal information including to third parties.
                                                </p>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 column">
                                                <div class="form-group message-btn">
                                                    <button type="submit" class="theme-btn-two" name="submit-form">
                                                        SEND MESSAGE
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
        <div class="faq-section">
            <div class="container">

                <div class="faq-content">
                    <div class="sec-title text-center">
                        <h2>
                            Still have a questions?
                        </h2>
                        <div class="text">
                            If you cannot find answer to your question in our FAQ, you can always contact us. We wil answer to you shortly!
                        </div>
                    </div>
                    <div class="accordion-box">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="">
                                    <li class="accordion block active-block">
                                        <div class="acc-btn active">
                                            <h4>How do I pay for the Essentials or Premium plan?</h4>
                                        </div>
                                        <div class="acc-content" style="display: block;">
                                            <div class="content">
                                                <div class="text">
                                                    You can pay with a credit card or via net banking (if you’re in United States).
                                                    We will renew your subscription automatically at the end of every billing cycle.
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion block">
                                        <div class="acc-btn">
                                            <h4>Can I cancel my Essentials or Premium plan subscription at any time?</h4>
                                        </div>
                                        <div class="acc-content current" style="display: none;">
                                            <div class="content">
                                                <div class="text">Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion block">
                                        <div class="acc-btn">
                                            <h4>We need to add new users to our team. How will that be billed?</h4>
                                        </div>
                                        <div class="acc-content" style="">
                                            <div class="content">
                                                <div class="text">Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!</div>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="">
                                    <li class="accordion block">
                                        <div class="acc-btn">
                                            <h4>Can I cancel my Essentials or Premium plan subscription at any time?</h4>
                                        </div>
                                        <div class="acc-content">
                                            <div class="content">
                                                <div class="text">Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion block">
                                        <div class="acc-btn">
                                            <h4>
                                                We need to add new users to our team. How will that be billed?
                                            </h4>
                                        </div>
                                        <div class="acc-content" style="">
                                            <div class="content">
                                                <div class="text">Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion block">
                                        <div class="acc-btn">
                                            <h4>My team wants to cancel its subscription. How do we do that? Can we get a refund?</h4>
                                        </div>
                                        <div class="acc-content current" style="display: none;">
                                            <div class="content">
                                                <div class="text">Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!</div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="accordion block">
                                        <div class="acc-btn">
                                            <h4>Do you offer discounts for non-profit organizations or educational institutions?</h4>
                                        </div>
                                        <div class="acc-content" style="">
                                            <div class="content">
                                                <div class="text">Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!</div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>

    </section>


@endsection
