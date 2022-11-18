@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    @if(app()->getLocale() == "ar")
    <style>
        .filter-option-inner-inner{
            text-align: right;
        }
    </style>
    @endif

@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.coupon.index') }}">{{t('Coupons')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($coupon) ? t('Edit Coupon') : t('Add Coupon') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($coupon) ? t('Edit Coupon') : t('Add Coupon') }}</h3>
                    </div>
                </div>


                    <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                          action="{{route('manager.coupon.store') }}" method="post">
                        {{ csrf_field() }}
                        @if(isset($coupon))
                            <input type="hidden" name="coupon_id" value="{{$coupon->id}}">
                        @endif


                        <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Code') }} </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="code" type="text" value="{{ isset($coupon->code) ? $coupon->code : old("code") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Amount') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="amount" type="amount" value="{{ isset($coupon->amount) ? $coupon->amount :old('amount') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Type') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="kt-radio-list">
                                            <label class="kt-radio">
                                                <input type="radio" name="type" value="1" {{isset($coupon) && $coupon->type == '1' ? 'checked':''}}> {{t('Ratio')}}

                                                <span></span>
                                            </label>
                                            <label class="kt-radio">
                                                <input type="radio" name="type" value="2" {{isset($coupon) && $coupon->type == '2' ? 'checked':''}}> {{t('Amount')}}

                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Number Users') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="number_users" type="number" value="{{ isset($coupon->number_users) ? $coupon->number_users : old('number_users') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Number Usage') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="number_usage" type="number" value="{{ isset($coupon->number_usage) ? $coupon->number_usage : old('number_usage') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Expire At') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control expire" dir="ltr" name="expire_at" type="text" value="{{ isset($coupon->expire_at) ? $coupon->expire_at : old('expire_at') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Branches') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select multiple name="branches[]" class="form-control selectpicker">
                                            @foreach($restaurants as $restaurant)
                                                <optgroup label="{{$restaurant->name}}">
                                                    @foreach($restaurant->branches as $branch)
                                                        <option value="{{$branch->id}}" {{isset($branches) && is_array($branches) && in_array($branch->id, $branches) ? 'selected':''}}>{{$branch->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">{{t('Draft')}}</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"  {{ isset($coupon) && $coupon->draft == 1 ? 'checked' :'' }} name="draft">
                                            <span></span>
                                            </label>
                                        </span>
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
                                            class="btn btn-danger">{{ isset($coupon) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-select.min.js')}}"></script>


    <script>
        $(function () {
            $('.selectpicker').selectpicker({
                title:"{{t('Select Branches')}}",
                liveSearch:true,
                noneResultsText:"{{t('No results matched')}}"
            });
        });
        $(document).ready(function() {
            $('.expire').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd hh:ii:ss'
            });
        });
    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
