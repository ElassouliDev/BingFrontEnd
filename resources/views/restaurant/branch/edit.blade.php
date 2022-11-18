@extends('restaurant.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}"
          rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('restaurant.branch.index') }}">{{t('Branches')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($branch) ? t('Edit Branch') : t('Add Branch') }}
        </li>
    @endpush

    @php
        $name = isset($branch) ? $branch->getTranslations()['name'] : null;
        $address = isset($branch) ? $branch->getTranslations()['address'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($branch) ? t('Edit Branch') : t('Add Branch') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right" action="{{route('restaurant.branch.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($branch))
                        <input type="hidden" name="branch_id" value="{{$branch->id}}">
                    @endif


                    <input class="form-control" name="lat" type="hidden"
                           step="0.0000000000000001"
                           value="{{ isset($branch) ? $branch->lat : old('lat') }}">
                    <input class="form-control" name="lng" type="hidden"
                           step="0.0000000000000001"
                           value="{{ isset($branch) ? $branch->lng : old('lng') }}">

                    <div class="kt-portlet__body">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab"
                                   href="#kt_tabs_1_1">{{t('Main Infromation')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_2">{{t('Branch Hours')}}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                <div class="kt-section kt-section--first">
                                    <div class="kt-section__body">
                                        <div class="row">
                                            {{--                                        <div class="col-md-6">--}}
                                            {{--                                            <div class="form-group">--}}
                                            {{--                                                <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Image') }}</label>--}}
                                            {{--                                                <div class="col-lg-9 col-xl-6">--}}
                                            {{--                                                    <div class="upload-btn-wrapper">--}}
                                            {{--                                                        <button class="btn btn-brand">{{ t('upload file') }}</button>--}}
                                            {{--                                                        <input name="image" class="imgInp" id="imgInp" type="file" />--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                    <img id="blah" @if(!isset($branch) || is_null($branch->getOriginal('image'))) style="display:none" @endif src="{{ isset($branch) && !is_null($branch->getOriginal('image'))  ? url($branch->image):'' }}" width="150" alt="No file chosen" />--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                            {{--                                        </div>--}}
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label
                                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('Cover') }}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="upload-btn-wrapper">
                                                            <button
                                                                class="btn btn-brand">{{ t('upload file') }}</button>
                                                            <input name="cover" class="imgInp" id="imgInp" type="file"/>
                                                        </div>
                                                        <img id="blah"
                                                             @if(!isset($branch) || is_null($branch->getOriginal('cover'))) style="display:none"
                                                             @endif src="{{ isset($branch) && !is_null($branch->getOriginal('cover'))  ? url($branch->cover):'' }}"
                                                             width="150" alt="No file chosen"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>{{ t('Name') }} <small>({{ $local }})</small></label>
                                                        <input class="form-control" name="name[{{$local}}]" type="text"
                                                               value="{{  isset($name) && is_array($name) && array_key_exists($local,$name)? $name[$local]: old("name[$local]")}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Mobile') }}</label>
                                                    <input class="form-control" dir="ltr" placeholder="+966XXXXXXXXX"
                                                           name="phone" type="text"
                                                           value="{{ isset($branch) ? $branch->phone :old('phone') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Email') }}</label>
                                                    <input class="form-control" name="email" type="email"
                                                           value="{{ isset($branch) ? $branch->email :old('email') }}"
                                                           autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Password') }}</label>
                                                    <input class="form-control" name="password" type="password"
                                                           autocomplete="off">
                                                </div>
                                            </div>
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>{{ t('Address') }} <small>({{ $local }})</small></label>
                                                        <input class="form-control" name="address[{{$local}}]"
                                                               type="text"
                                                               value="{{  isset($address) && is_array($address) && array_key_exists($local,$address)? $address[$local]: old("address[$local]")}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Lat') }}</label>
                                                    <input class="form-control" name="lat" type="number"
                                                           step="0.0000000000000001"
                                                           disabled
                                                           value="{{ isset($branch) ? $branch->lat : old('lat') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Lng') }}</label>
                                                    <input class="form-control" name="lng" type="number"
                                                           step="0.0000000000000001"
                                                           disabled
                                                           value="{{ isset($branch) ? $branch->lng : old('lng') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Max Orders') }}</label>
                                                    <input class="form-control" name="max_orders" type="number" min="0"
                                                           value="{{ isset($branch) ? $branch->max_orders : old('max_orders') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>{{t('Active')}} </label>
                                                            <div class="w-100">
                                                                <span class="kt-switch">
                                                                    <label>
                                                                    <input type="checkbox" value="1"
                                                                           {{ isset($branch) && $branch->status == \App\Models\Branch::ACTIVE ? 'checked' :'' }} name="active">
                                                                    <span></span>
                                                                    </label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>{{t('Busy')}} </label>
                                                            <div class="w-100">
                                                                <span class="kt-switch">
                                                                    <label>
                                                                    <input type="checkbox" value="1"
                                                                           {{ isset($branch) && $branch->busy == 1 ? 'checked' :'' }} name="busy">
                                                                    <span></span>
                                                                    </label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>{{t('Draft')}} </label>
                                                            <div class="w-100">
                                                                <span class="kt-switch">
                                                                    <label>
                                                                    <input type="checkbox" value="1"
                                                                           {{ isset($branch) && $branch->draft == 1 ? 'checked' :'' }} name="draft">
                                                                    <span></span>
                                                                    </label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>{{t('Branch Accepted')}} </label>
                                                            <div class="w-100">
                                                                <span class="kt-switch">
                                                                    <label>
                                                                    <input type="checkbox" value="1"
                                                                           {{ isset($branch) && $branch->accepted == 1 ? 'checked' :'' }} name="accepted">
                                                                    <span></span>
                                                                    </label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{t('Location On Map')}}
                                            </label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div id="map" style="width: 100%; min-height: 400px"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <div class="kt-section__body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <th>#</th>
                                                    <th>{{ t('Day') }}</th>
                                                    <th>{{ t('From') }}</th>
                                                    <th>{{ t('To') }}</th>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($days as $day)
                                                        <tr>
                                                            <td><input type="checkbox" name="day[]"
                                                                       value="{{ $day['num'] }}"
                                                                       class="name" {{ $day['selected'] ? 'checked':false }}>
                                                            </td>
                                                            <td>{{ t($day['day']) }}</td>
                                                            <td><input type="text" value="{{ $day['from'] }}"
                                                                       name="from[{{ $day['num'] }}]"
                                                                       class="form-control kt_timepicker_2"
                                                                       placeholder="{{ t('From') }}"></td>
                                                            <td><input type="text" value="{{ $day['to'] }}"
                                                                       name="to[{{ $day['num'] }}]"
                                                                       class="form-control kt_timepicker_2"
                                                                       placeholder="{{ t('To') }}"></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
                                            class="btn btn-danger">{{ isset($branch) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script
        src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAZlL0Ua_lbDzTQK28QTqyu1GwZEqleC-0"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"
            type="text/javascript"></script>


    <script type="text/javascript">
        $('.kt_timepicker_2').timepicker({
            minuteStep: 1,
            defaultTime: '',
            showSeconds: true,
            showMeridian: false,
            snapToStep: true,
            isRtl: true,
            icons: {
                up: 'la la-angle-up',
                down: 'la la-angle-down'
            }
        });
        $('.kt_timepicker_2').change(function () {
            var ele = $(this).parent().parent().find('input[type="checkbox"]');
            ele.attr('checked', true)
        });
        var map;
        var marker;
        var myLatLng = {
            lat: {{ isset($branch->lat) ? $branch->lat : 0 }},
            lng: {{ isset($branch->lng) ? $branch->lng : 0 }} };
        initMap();

        function initMap() {
            map = new google.maps.Map(
                document.getElementById('map'),
                {
                    center: new google.maps.LatLng({{ isset($branch->lat) ? $branch->lat : 0 }}, {{ isset($branch->lng) ? $branch->lng : 0 }}),
                    zoom: 9,
                });


            // Create markers.
            marker = new google.maps.Marker({
                position: myLatLng,
                map: map
            });
        }

        google.maps.event.addListener(map, 'click', function (event) {
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
