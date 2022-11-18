
<!DOCTYPE html>
@if(!empty(\Session::get('lang')))
    @php
        \App::setLocale(Session::get('lang'));
    @endphp
@else
    @php
        \App::setLocale('ar');
    @endphp
@endif
<html lang="{{app()->getlocale()}}" dir="{{direction()}}">

<!-- begin::Head -->
<head>
    <!--end::Base Path -->
    <meta charset="utf-8" />
    <title>{{ t('Dashboard') }}</title>
    <meta name="description" content="Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->

    <link href="{{ asset("assets/css/demo6/pages/general/login/login-6.".direction('.')."css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("assets/css/demo6/style.bundle.".direction('.')."css") }}" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <!--end::Layout Skins -->
    @if(isset(cached()->logo_min))
        <link rel="shortcut icon" href="{{ asset(cached()->logo_min) }}"/>
    @endif
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v6 kt-login--signin" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
            <div class="kt-grid__item  kt-grid__item--order-tablet-and-mobile-2  kt-grid kt-grid--hor kt-login__aside">
                <div class="kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__body">
                            <div class="kt-login__logo">
                                <a href="{{ url('/') }}">
                                    @if(isset(cached()->logo))
                                        <img src="{{ asset(cached()->logo) }}" width="35%" alt="logo" class="img-fluid"/>
                                    @else
                                        <h3>App Logo</h3>
                                    @endif
                                </a>
                            </div>
                            <div class="kt-login__signin">
                                <div class="kt-login__form">
                                    <form class="kt-form" action="{{ url('branch/login') }}" method="post">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <input class="form-control {{ $errors->has('email') ? ' has-error' : '' }}" type="text" placeholder="{{ t('Email') }}" name="email" autocomplete="off">
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control form-control-last {{ $errors->has('password') ? ' has-error' : '' }}" type="password" placeholder="{{ t('Password') }}" name="password">
                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                        <div class="kt-login__extra">
                                            <label class="kt-checkbox">
                                                <input type="checkbox" name="remember"> {{ t('Remember me') }}
                                                <span></span>
                                            </label>
                                            <a href="/branch/password/reset">{{t('Forget Password ?')}}</a>
                                        </div>
                                        <div class="kt-login__actions">

                                            <input type="submit" id="" class="btn btn-danger btn-pill btn-elevate" value="{{ t('Login') }}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-login__account">
                                    <span class="kt-login__account-msg">
                                        {{ date('Y') }}&nbsp;&copy;&nbsp;<a href="https://manaratalharamain.gov.sa/" target="_blank" class="kt-link kt-font-danger"></a>
                                    </span>&nbsp;&nbsp;|
                        <span class="kt-login__account-msg">
                             @if(app()->getLocale() == "en")
                                <a href="{{ route('switch-language', 'ar') }}" class="kt-link kt-font-danger">

                                                العربية
                                            </a>
                            @else
                                <a href="{{ route('switch-language', 'en') }}" class="kt-link kt-font-danger">
                                            English
                                  </a>
                            @endif

                                    </span>&nbsp;&nbsp;
                    </div>
                </div>
            </div>
            <div class="kt-grid__item kt-grid__item--fluid kt-grid__item--center kt-grid kt-grid--ver kt-login__content" style="background: linear-gradient(to right,#db1515,#ec5252);">
                <div class="kt-login__section">
                    <div class="kt-login__block text-center">

                        <div class=" mb-5">
                            {{--                                <img src="{{ asset('assets/media/logos/white_logo.svg') }}" width="40%" alt="logo" class="img-fluid"/>--}}
                        </div>

                        <h3 class="kt-login__title" style="font-size: 2.2rem">{{ t('Control Panel for System Management') }}</h3>
                        <h4 class="kt-login__desc " style="font-size: 28px">
                            {{ t('Welcome') }}.
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end:: Page -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#000000"]
            }
        }
    };
</script>

<!-- end::Global Config -->

<!--begin:: Global Mandatory Vendors -->
<script src="{{ asset('assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/popper.js/dist/umd/popper.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/js-cookie/src/js.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/moment/min/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/sticky-js/dist/sticky.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/wnumb/wNumb.js') }}" type="text/javascript"></script>

<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->

<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle(used by all pages) -->

<!--end::Global Theme Bundle -->

<!--begin::Page Scripts(used by this page) -->
<script src="{{ asset('assets/js/demo1/pages/login/login-general.js') }}" type="text/javascript"></script>

<!--end::Page Scripts -->
</body>

<!-- end::Body -->
</html>
