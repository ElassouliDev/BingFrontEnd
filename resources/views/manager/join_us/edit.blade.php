@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.join_us.index') }}">{{t('Join Us')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Add Restaurant') }}
        </li>
    @endpush
    @php
        $name = isset($merchant) ? $merchant->getTranslations()['merchant_name'] : null;
    @endphp
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ t('Add Restaurant') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ route('manager.join_us.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="join_us_id" value="{{$merchant->id}}">

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Logo') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-brand">{{ t('upload file') }}</button>
                                            <input name="image" class="imgInp" id="imgInp" type="file" />
                                        </div>
                                        <img id="blah" @if(!isset($merchant) || is_null($merchant->getOriginal('image'))) style="display:none" @endif src="{{ isset($merchant) && !is_null($merchant->getOriginal('image'))  ? url($merchant->image):'' }}" width="150" alt="No file chosen" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Owner Name') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="owner_name" type="text" value="{{ isset($merchant->owner_name) ? $merchant->owner_name : old("owner_name") }}">
                                    </div>
                                </div>
                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }} <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="merchant_name[{{$local}}]" type="text" value="{{ isset($name) ? $name[$local] : old("merchant_name[$local]") }}">
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Merchant Type') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="merchant_type_id">
                                            <option value="" selected disabled>{{t('Select Merchant Type')}}</option>
                                            @foreach($merchant_types as $merchant_type)
                                                <option value="{{$merchant_type->id}}" {{isset($merchant) && $merchant->merchant_type_id == $merchant_type->id ? 'selected':''}}>{{$merchant_type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('City') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="city_id">
                                            <option value="" selected disabled>{{t('Select City')}}</option>
                                            @foreach($citites as $city)
                                                <option value="{{$city->id}}" {{isset($merchant) && $merchant->city_id == $city->id ? 'selected':''}}>{{$city->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Mobile') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="+966XXXXXXXXX" name="phone" type="text" value="{{ isset($merchant) ? $merchant->phone :old('phone') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Email') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="text" value="{{ isset($merchant) ? $merchant->email :old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Password') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="password" type="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">{{t('Active')}}</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"  {{ isset($merchant) && $merchant->status == \App\Models\Merchant::ACTIVE ? 'checked' :'' }} name="active">
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
                                            <input type="checkbox" value="1"  {{ isset($merchant) && $merchant->draft == 1 ? 'checked' :'' }} name="draft">
                                            <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('ID No') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="id_no" type="text" value="{{ isset($merchant) ? $merchant->id_no :old('id_no') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Bank') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="bank_id">
                                            <option value="" selected disabled>{{t('Select Bank')}}</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}" {{isset($merchant) && $merchant->bank_id == $bank->id ? 'selected':''}}>{{$bank->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('IBAN') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="i_ban" type="text" value="{{ isset($merchant) ? $merchant->i_ban :old('i_ban') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Swift Code') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="swift_code" type="text" value="{{ isset($merchant) ? $merchant->swift_code :old('swift_code') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Commercial Registration No') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="comm_registration_no" type="text" value="{{ isset($merchant) ? $merchant->comm_registration_no :old('comm_registration_no') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('ID File') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="id_file" type="file" value="{{ isset($merchant->id_file) ? asset($merchant->id_file) :'' }}">
                                        @if(isset($merchant) && !is_null($merchant->id_file))
                                            <input type="hidden" name="id_file_old" value="{{$merchant->id_file}}">
                                            <a href="{{asset($merchant->id_file)}}" target="_blank">{{t('Browse')}}</a>
                                        @endisset
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Commercial Registration File') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="comm_registration_file" type="file" value="{{ isset($merchant->comm_registration_file) ? asset($merchant->comm_registration_file) :'' }}">
                                        @if(isset($merchant) && !is_null($merchant->comm_registration_file))
                                            <input type="hidden" name="comm_registration_file_old" value="{{$merchant->comm_registration_file}}">
                                            <a href="{{asset($merchant->comm_registration_file)}}" target="_blank">{{t('Browse')}}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"class="btn btn-danger">{{t('Create') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $('.date').datepicker({
            autoclose : true,
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            format: 'yyyy-mm-dd'
        });
    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
