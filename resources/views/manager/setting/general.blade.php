@php
    $logo = Setting('logo');
    $logo_min = Setting('logo_min');
    $name = Setting('name');
    $address = Setting('address');

    $email = Setting('email');
    $mobile = Setting('mobile');
    $whatsApp = Setting('whatsApp');
    $facebook = Setting('facebook');
    $twitter = Setting('twitter');
    $instagram = Setting('instagram');
    $youtube = Setting('youtube');
    $ios_url = Setting('ios_url');
    $android_url = Setting('android_url');
    $about_us = Setting('about_us');
    $services = Setting('services');
    $conditions = Setting('conditions');

//orders
    $tax = Setting('tax');
    $commission = Setting('commission');
    $providers_range = Setting('providers_range');
    $commission_delivery = Setting('commission_delivery');
    $commission_cancel_delivery = Setting('commission_cancel_delivery');
    $kilo_cost = Setting('kilo_cost');
@endphp

@extends('manager.layout.container')
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ t('General Settings') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            <i class="flaticon-responsive"></i> {{ t('General Settings') }}</h3>
                    </div>
                </div>
                <form class="kt-form kt-form--label-right" enctype="multipart/form-data"
                      action="{{ route('manager.settings.updateSettings') }}" method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Logo') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-brand">{{ t('upload file') }}</button>
                                                    <input name="logo" class="imgInp" id="imgInp" type="file"/>
                                                </div>
                                                <img id="blah" style="display:{{!isset($logo)? 'none' :'block'}}"
                                                     src="{{ isset($logo)  ? url($logo):'' }}" width="150"
                                                     alt="No file chosen"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Min Logo') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-brand">{{ t('upload file') }}</button>
                                                    <input name="logo_min" class="imgInp" id="imgInp_min" type="file"/>
                                                </div>
                                                <img id="blah_min" style="display:{{!isset($logo_min)?'none':'block'}}"
                                                     src="{{ isset($logo_min)  ? url($logo_min):'' }}" width="150"
                                                     alt="No file chosen"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">{{ t('Name') }} <small>({{ $local }}
                                                        )</small></label>

                                                <input name="name[{{$local}}]" type="text"
                                                       value="{{  isset($name) && is_array($name) && array_key_exists($local,$name)? $name[$local]:''}}"
                                                       class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">{{ t('Address') }}
                                                    <small>({{ $local }})</small></label>
                                                <input name="address[{{$local}}]" type="text"
                                                       value="{{  isset($address) && is_array($address) && array_key_exists($local,$address)? $address[$local]:''}}"
                                                       class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Email') }}</label>
                                            <input type="text" value="{{ isset($email) ? $email:old('email') }}"
                                                   name="email" class="form-control" placeholder="{{ t('Email') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Mobile') }}</label>
                                            <input type="text" dir="ltr"
                                                   value="{{ isset($mobile) ? $mobile:old('mobile') }}" name="mobile"
                                                   class="form-control" placeholder="{{ t('Mobile') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('WhatsApp') }}</label>
                                            <input type="text"
                                                   value="{{ isset($whatsApp) ? $whatsApp:old('whatsApp') }}"
                                                   name="whatsApp" class="form-control"
                                                   placeholder="{{ t('WhatsApp') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Facebook') }}</label>
                                            <input type="text"
                                                   value="{{ isset($facebook) ? $facebook:old('facebook') }}"
                                                   name="facebook" class="form-control"
                                                   placeholder="{{ t('Facebook') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Twitter') }}</label>
                                            <input type="text" value="{{ isset($twitter) ? $twitter:old('twitter') }}"
                                                   name="twitter" class="form-control" placeholder="{{ t('Twitter') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Instagram') }}</label>
                                            <input type="text"
                                                   value="{{ isset($instagram) ? $instagram:old('instagram') }}"
                                                   name="instagram" class="form-control"
                                                   placeholder="{{ t('Instagram') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Youtube') }}</label>
                                            <input type="text" value="{{ isset($youtube) ? $youtube:old('youtube') }}"
                                                   name="youtube" class="form-control" placeholder="{{ t('Youtube') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('iOS URL') }}</label>
                                            <input type="text" value="{{ isset($ios_url) ? $ios_url:old('ios_url') }}"
                                                   name="ios_url" class="form-control" placeholder="{{ t('iOS URL') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Android URL') }}</label>
                                            <input type="text"
                                                   value="{{ isset($android_url) ? $android_url:old('android_url') }}"
                                                   name="android_url" class="form-control"
                                                   placeholder="{{ t('Android URL') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Tax') }}</label>
                                            <input type="number" step="0.9" value="{{ isset($tax) ? $tax:old('tax') }}" name="tax" class="form-control" placeholder="{{ t('Tax') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('App Commission') }}</label>
                                            <input type="number" step="0.01" value="{{ isset($commission) ? $commission:old('commission') }}" name="commission" class="form-control" placeholder="{{ t('App Commission') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Branches Range') }} (K.M)</label>
                                            <input type="number" step="0.01" value="{{ isset($providers_range) ? $providers_range:old('providers_range') }}" name="providers_range" class="form-control" placeholder="{{ t('Branches Range') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Commission Delivery') }} (%)</label>
                                            <input type="number" step="0.01" max="100" value="{{ isset($commission_delivery) ? $commission_delivery:old('commission_delivery') }}" name="commission_delivery" class="form-control" placeholder="{{ t('Commission Delivery') }}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Commission Cancel Delivery') }} (SAR)</label>
                                            <input type="number" step="0.01" value="{{ isset($commission_cancel_delivery) ? $commission_cancel_delivery:old('commission_cancel_delivery') }}" name="commission_cancel_delivery" class="form-control" placeholder="{{ t('Commission Cancel Delivery') }}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Kilo Cost') }} (SAR)</label>
                                            <input type="number" step="0.01" value="{{ isset($kilo_cost) ? $kilo_cost:old('kilo_cost') }}" name="kilo_cost" class="form-control" placeholder="{{ t('Kilo Cost') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">{{ t('About Us') }}
                                                    <small>({{ $local }})</small></label>
                                                <textarea class="form-control" name="about_us[{{$local}}]" id="about_us"
                                                          cols="30"
                                                          rows="10">{{isset($about_us) && is_array($about_us) && array_key_exists($local,$about_us)? $about_us[$local]:'' }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach


                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">{{ t('services') }}
                                                    <small>({{ $local }})</small></label>
                                                <textarea class="form-control" name="services[{{$local}}]" id="services"
                                                          cols="30"
                                                          rows="10">{{ isset($services) && is_array($services) && array_key_exists($local,$services)? $services[$local]:''}}</textarea>
                                            </div>
                                        </div>
                                    @endforeach






                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">{{ t('conditions') }}
                                                    <small>({{ $local }})</small></label>
                                                <textarea class="form-control" name="conditions[{{$local}}]"
                                                          id="conditions" cols="30"
                                                          rows="10">{{  isset($conditions) && is_array($conditions) && array_key_exists($local,$conditions)? $conditions[$local]:''  }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach



                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-brand">{{ t('Save') }}</button>&nbsp;
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

@endsection
