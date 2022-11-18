@extends('restaurant.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('restaurant.driver.index') }}">{{t('Drivers')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($driver) ? t('Edit Driver') : t('Add Driver') }}
        </li>
    @endpush
    @php
        $name = isset($driver) ? $driver->getTranslations()['name'] : null;
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($driver) ? t('Edit Driver') : t('Add Driver') }}</h3>
                    </div>
                </div>

                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('restaurant.driver.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($driver))
                        <input type="hidden" name="driver_id" value="{{$driver->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('image') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-brand">{{ t('upload file') }}</button>
                                            <input name="image" class="imgInp" id="imgInp" type="file"/>
                                        </div>
                                        <img id="blah"
                                             @if(!isset($driver) || is_null($driver->getOriginal('image'))) style="display:none"
                                             @endif src="{{ isset($driver) && !is_null($driver->getOriginal('image'))  ? url($driver->image):'' }}"
                                             width="150" alt="No file chosen"/>
                                    </div>
                                </div>

                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="name[{{$local}}]" type="text"
                                                   value="{{ isset($name) ? $name[$local] : old("name[$local]") }}">
                                        </div>
                                    </div>
                                @endforeach


                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Mobile') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="+966XXXXXXXXX" name="phone"
                                               type="text" value="{{ isset($driver) ? $driver->phone :old('phone') }}">
                                    </div>
                                </div>

{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Password') }}</label>--}}
{{--                                    <div class="col-lg-9 col-xl-6">--}}
{{--                                        <input class="form-control" name="password" type="password">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">{{t('Active')}}</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"
                                                   {{ isset($driver) && $driver->status == \App\Models\Merchant::ACTIVE ? 'checked' :'' }} name="active">
                                            <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">{{t('Draft')}}</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"
                                                   {{ isset($driver) && $driver->draft == 1 ? 'checked' :'' }} name="draft">
                                            <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <hr/>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('City') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="city_id">
                                            <option value="" selected disabled>{{t('Select City')}}</option>
                                            @foreach($cities as $city)
                                                <option
                                                    value="{{$city->id}}" {{isset($driver) && $driver->city_id == $city->id ? 'selected':''}}>{{$city->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Bank') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="bank_id">
                                            <option value="" selected disabled>{{t('Select Bank')}}</option>
                                            @foreach($banks as $bank)
                                                <option
                                                    value="{{$bank->id}}" {{isset($driver) && $driver->bank_id == $bank->id ? 'selected':''}}>{{$bank->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Nationality') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="nationality_id">
                                            <option value="" selected disabled>{{t('Select Nationality')}}</option>
                                            @foreach($nationalities as $nationality)
                                                <option
                                                    value="{{$nationality->id}}" {{isset($driver) && $driver->nationality_id == $nationality->id ? 'selected':''}}>{{$nationality->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row" id="branch">

                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Branch') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control branches"
                                                name="branch_id">
                                            <option value="" selected
                                                    disabled>{{t('Select Branch')}}</option>
                                            @isset($branches)
                                                @foreach($branches as $branch)
                                                    <option
                                                        value="{{$branch->id}}" {{isset($driver) && $driver->branch_id == $branch->id ? 'selected':''}}>{{$branch->name}}</option>
                                                @endforeach
                                            @endisset
                                        </select>

                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Transporter') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="transporter_id">
                                            <option value="" selected disabled>{{t('Select Transporter')}}</option>
                                            @foreach($transporters as $transporter)
                                                <option
                                                    value="{{$transporter->id}}" {{isset($driver) && $driver->transporter_type_id == $transporter->id ? 'selected':''}}>{{$transporter->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Driving License') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="driving_license" type="file"
                                               value="{{ isset($driver->driving_license) ? asset($driver->driving_license) :'' }}">
                                        @if(isset($driver) && !is_null($driver->driving_license))
                                            <input type="hidden" name="driving_license_old"
                                                   value="{{$driver->driving_license}}">
                                            <a href="{{asset($driver->driving_license)}}"
                                               target="_blank">{{t('Browse')}}</a>
                                        @endisset
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('ID File') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="id_card" type="file"
                                               value="{{ isset($driver->id_card) ? asset($driver->id_card) :'' }}">
                                        @if(isset($driver) && !is_null($driver->id_card))
                                            <input type="hidden" name="id_card_old" value="{{$driver->id_card}}">
                                            <a href="{{asset($driver->id_card)}}" target="_blank">{{t('Browse')}}</a>
                                        @endisset
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($driver) ? t('Update'):t('Create') }}</button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
            type="text/javascript"></script>
    <script type="text/javascript">
        $('.date').datepicker({
            autoclose: true,
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            format: 'yyyy-mm-dd'
        });
    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
