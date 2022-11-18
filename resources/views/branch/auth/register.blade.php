@extends('layouts.container')

@section('css')
    <link href="{{website('js/intl-tel-input-master/build/css/intlTelInput.min.css')}}" rel="stylesheet">
    <link href="{{website('css/bootstrap-select.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ website('js/bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .hide{
            display: none;
        }
    </style>
@endsection
@section('content')
    <section class="second-color-bg text-center text-white py-5"><h1 class="h2">{{w('sign up')}}</h1></section>
    <section class="py-5 sine-up-form">
        <div class="container">
            <div class="row no-gutters text-center sine-up-form-tap">
                <div class="col-12">
                    @if (count($errors) > 0)
                        <div class="alert alert-warning">
                            <ul style="width: 100%;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row no-gutters text-center sine-up-form-tap">
                <div class="col-6 active">
                    <a href="" class="d-block py-3">{{w('Teacher Account')}}</a>
                </div>
                <div class="col-6">
                    <a href="{{route('trainee.register')}}" class="d-block py-3">{{w('Student Account')}}</a>
                </div>
            </div>
            <div class="bg-white p-5">
                <form action="{{route('trainer.register')}}" id="form_information" method="post">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('First name')}}<span class="main-color-text">*</span></label>
                                <input type="text" class="form-control rounded-0" name="first_name" placeholder="{{w('Enter First name')}}" value="{{old('first_name')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('last name')}} <span class="main-color-text">*</span></label>
                                <input type="text" class="form-control rounded-0" name="last_name" placeholder="{{w('Enter last name')}}" value="{{old('last_name')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('user name')}} <span class="main-color-text">*</span></label>
                                <input type="text" class="form-control rounded-0" name="username" placeholder="{{w('Enter user name')}}" value="{{old('first_name')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('Email address')}}<span class="main-color-text">*</span></label>
                                <input type="text" class="form-control rounded-0" name="email" placeholder="{{w('Email address')}}" value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('Password')}} <span class="main-color-text">*</span></label>
                                <input type="password" class="form-control rounded-0" name="password" placeholder="{{w('Password')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('Password (again)')}} <span class="main-color-text">*</span></label>
                                <input type="password" class="form-control rounded-0" name="password_confirmation" placeholder="{{w('Password (again)')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('Phone')}} <span class="main-color-text">*</span></label>
                                <div><input type="tel" id="phone" name="mobile" class="form-control rounded-0" value="{{old('mobile')}}"></div>
                                <input type="hidden" id="country-code" name="country_code" value="{{old('country_code')}}">
                                <input type="hidden" id="short-country" name="short_country" value="{{old('short_country')}}">
                                <span id="valid-msg" class="hide">✓ {{ t('Valid') }}</span>
                                <span id="error-msg" class="hide"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('Date Of Birth')}} <span class="main-color-text">*</span></label>
                                <div><input type="text" name="date_of_birth" placeholder="{{w('Date Of Birth')}}" value="{{old('date_of_birth')}}" class="form-control rounded-0 date" autocomplete="disabled"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country <span class="main-color-text">*</span></label>
                                <select id="country" name="country" class="form-control rounded-0">
                                    <option selected disabled>{{w('Choose from list')}}</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City <span class="main-color-text">*</span></label>
                                <select id="city" name="city" class="form-control rounded-0">
                                    <option selected disabled>{{w('Choose from list')}}</option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}">{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('Gender')}} <span class="main-color-text">*</span></label>

                                <select class="form-control rounded-0" name="gender">
                                    <option selected disabled>{{w('Choose from list')}}</option>
                                    <option value="male">{{w('male')}}</option>
                                    <option value="female">{{w('female')}}</option>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('Native language')}} <span class="main-color-text">*</span></label>
                                <select name="native_language" class="form-control rounded-0" data-live-search="true">
                                    <option selected disabled>{{w('Choose from list')}}</option>
                                    @foreach($languages as $language)
                                        <option value="{{$language->id}}">{{$language->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('Dialects')}} <span class="main-color-text">*</span></label>
                                <select name="languages_of_education[]" class="form-control rounded-0" multiple data-live-search="true">
                                    <option selected disabled>{{w('Choose Dialects')}}</option>
                                    @foreach($languages as $language)
                                        <option value="{{$language->id}}">{{$language->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{w('Bio')}} <span class="main-color-text">*</span></label>
                                <textarea name="bio" class="form-control rounded-0" rows="3" placeholder="Enter Bio">{{old('bio')}}</textarea>
                            </div>
                        </div>

                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="rounded-0 btn main-color-bg text-white btn-new-lg font-weight-bold">{{w('Create my new account')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('pre_js')
    <script src="{{website('js/intl-tel-input-master/build/js/intlTelInput.min.js')}}"></script>
    <script src="{{website('js/bootstrap-select.min.js')}}"></script>
    <script src="{{ website('js/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.min.js') }}" type="application/javascript"></script>
    <script type="text/javascript">
        $('input.date').datepicker({
            autoclose : true,
            todayHighlight: true,
            orientation: "bottom left",
            format: 'yyyy-mm-dd'
        });
    </script>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
    <script type="text/javascript">
        $('select').selectpicker();
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            formatOnDisplay:false
        });
        errorMsg = document.querySelector("#error-msg"),
        validMsg = document.querySelector("#valid-msg");
        countryCode = document.querySelector("#country-code");
        shortCountry = document.querySelector("#short-country");

        // here, the index maps to the error code returned from getValidationError - see readme
        var errorMap = ["{{ t('Invalid number') }}", "{{ t('Invalid country code') }}", "{{ t('Too short') }}", "{{ t('Too long') }}", "{{ t('Invalid number') }}"];

        // initialise plugin
        var iti = window.intlTelInput(input, {
            utilsScript: "{{ website('js/intl-tel-input-master/build/js/utils.js?1562189064761')}}"
        });

        var reset = function() {
            input.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
            validMsg.classList.add("hide");
            if (input.value.trim()) {
                if (iti.isValidNumber()) {
                    countryCode.value = iti.getSelectedCountryData().dialCode;
                    shortCountry.value = iti.getSelectedCountryData().iso2;
                    validMsg.classList.remove("hidden");
                } else {
                    input.classList.add("error");
                    var errorCode = iti.getValidationError();
                    errorMsg.innerHTML = errorMap[errorCode];
                    errorMsg.classList.remove("hidden");
                }
            }
        };

        // on blur: validate
        input.addEventListener('blur', reset);

        // on keyup / change flag: reset
        input.addEventListener('change', reset);
        input.addEventListener('keyup', reset);


    </script>
@endsection
