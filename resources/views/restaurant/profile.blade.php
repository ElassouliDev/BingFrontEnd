@extends('restaurant.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ t('Edit Profile') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ t('Edit Profile') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ route('restaurant.update_profile') }}"
                      method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Image') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-brand">{{ t('upload file') }}</button>
                                            <input name="image" class="imgInp" id="imgInp" type="file" />
                                        </div>
                                        <img id="blah" @if(!isset($merchant) || is_null($merchant->getOriginal('image'))) style="display:none" @endif src="{{ isset($merchant) && !is_null($merchant->getOriginal('image'))  ? url($merchant->image):'' }}" width="150" alt="No file chosen" />
                                    </div>
                                </div>
                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }} <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="name:{{$local}}" type="text" value="{{ isset($merchant->name) ? $merchant->translate($local)->name : old("name:$local") }}">
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
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Mobile') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="+966XXXXXXXXX" name="mobile" type="text" value="{{ isset($merchant) ? $merchant->user->mobile :old('mobile') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Email') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="text" value="{{ isset($merchant) ? $merchant->user->email :old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Password') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="password" type="password">
                                    </div>
                                </div>
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Date Of Birth') }}</label>--}}
{{--                                    <div class="col-lg-9 col-xl-6">--}}
{{--                                        <input class="form-control date" name="dob" type="text" value="{{ isset($merchant) ? $merchant->user->dob :old('dob') }}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Gender') }}</label>--}}
{{--                                    <div class="col-lg-9 col-xl-6">--}}
{{--                                        <div class="kt-radio-inline">--}}
{{--                                            <label class="kt-radio">--}}
{{--                                                <input type="radio" value="male" checked="checked" name="gender" {{isset($merchant)&&$merchant->user->gender == 'male' ? 'checked':''}}> {{t('Male')}}--}}
{{--                                                <span></span>--}}
{{--                                            </label>--}}
{{--                                            <label class="kt-radio">--}}
{{--                                                <input type="radio" value="female"  name="gender" {{isset($merchant)&&$merchant->user->gender == 'female' ? 'checked':''}}> {{t('Female')}}--}}
{{--                                                <span></span>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($merchant) ? t('Update'):t('Create') }}</button>&nbsp;
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
