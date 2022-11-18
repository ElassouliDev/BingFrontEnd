@extends('manager.layout.container')
@section('style')
@endsection


@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.payment.index') }}">{{t('Payments')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($payment) ? t('Edit Payment') : t('Add Payment') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($payment) ? t('Edit Payment') : t('Add Payment') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ isset($payment) ? route('manager.payment.update', $payment->id): route('manager.payment.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($payment))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Amount') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="amount" type="number" value="{{ isset($payment) ? $payment->amount : old("amount") }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Restaurants') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control restaurants" name="merchant_id">
                                            <option value="" selected>{{t('Select Restaurant')}}</option>
                                            @foreach($merchants as $merchant)
                                                <option value="{{$merchant->id}}" {{isset($payment) && $payment->merchant_id == $merchant->id ? 'selected':''}}>{{$merchant->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Note') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <textarea name="note" rows="7" class="form-control">{{ isset($payment) ? $payment->note :old('note') }}</textarea>
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
                                            class="btn btn-danger">{{ isset($payment) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! $validator->selector('#form_information') !!}
@endsection
