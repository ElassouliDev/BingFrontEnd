@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.address.index') }}">{{t('Addresses')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($address) ? t('Edit Address') : t('Add Address') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($address) ? t('Edit Address') : t('Add Address') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ isset($address) ? route('manager.address.update', $address->id): route('manager.address.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($address))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('User') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="user_id">
                                            <option value="" selected disabled>{{t('Select User')}}</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}" {{isset($address) && $address->user_id == $user->id ? 'selected':''}}>{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Address Name') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text" value="{{ isset($address->name) ? $address->name : old("name") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Address') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="address" type="text" value="{{ isset($address->address) ? $address->address : old("address") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Address Type') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="type">
                                            <option value="" selected disabled>{{t('Select Type')}}</option>
                                            <option value="home" {{isset($address) && $address->type == 'home' ? 'selected':''}}>{{t('home')}}</option>
                                            <option value="work" {{isset($address) && $address->type == 'work' ? 'selected':''}}>{{t('work')}}</option>
                                            <option value="other" {{isset($address) && $address->type == 'other' ? 'selected':''}}>{{t('other')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" style="display: none">
                                    <div class="form-group">
                                        <label>{{ t('Lat') }}</label>
                                        <input class="form-control" name="lat" type="number" step="0.0000000000000001" value="{{ isset($address) ? $address->lat : old('lat') }}">
                                    </div>
                                </div>
                                <div class="col-md-3" style="display: none">
                                    <div class="form-group">
                                        <label>{{ t('Lng') }}</label>
                                        <input class="form-control" name="lng" type="number" step="0.0000000000000001" value="{{ isset($address) ? $address->lng : old('lng') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{t('Location On Map')}}
                                    </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div id="map" style="width: 100%; min-height: 400px"></div>
                                    </div>
                                </div>
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-3 col-form-label font-weight-bold">{{t('Default')}}</label>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <span class="kt-switch">--}}
{{--                                            <label>--}}
{{--                                            <input type="checkbox" value="1"  {{ isset($address) && $address->default == 1 ? 'checked' :'' }} name="draft">--}}
{{--                                            <span></span>--}}
{{--                                            </label>--}}
{{--                                        </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-danger">{{ isset($address) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAZlL0Ua_lbDzTQK28QTqyu1GwZEqleC-0"></script>
    <script type="text/javascript">
        var map;
        var marker;
        var myLatLng = {lat: {{ isset($address->lat) ? $address->lat : 0 }}, lng: {{ isset($address->lng) ? $address->lng : 0 }} };
        initMap();
        function initMap() {
            map = new google.maps.Map(
                document.getElementById('map'),
                {
                    center: new google.maps.LatLng({{ isset($address->lat) ? $address->lat : 0 }}, {{ isset($address->lng) ? $address->lng : 0 }}),
                    zoom: 9,
                });


            // Create markers.
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map
            });
        }
        google.maps.event.addListener(map, 'click', function( event ){
            marker.setMap(null);
            var myLatLng = {lat: event.latLng.lat(), lng: event.latLng.lng()};
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map
            });
            $('input[name="lat"]').val(event.latLng.lat());
            $('input[name="lng"]').val(event.latLng.lng());
        });
    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
