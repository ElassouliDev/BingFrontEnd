@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.nationality.index') }}">{{t('Nationality')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($nationality) ? t('Edit Nationality') : t('Add Nationality') }}
        </li>
    @endpush

    @php
        $name = isset($nationality) ? $nationality->getTranslations()['name'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($nationality) ? t('Edit Nationality') : t('Add Nationality') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.nationality.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($nationality))
                        <input type="hidden" name="nationality_id" value="{{$nationality->id}}">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @foreach(config('translatable.locales') as $local)
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }} <small>({{ $local }})</small></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name[{{$local}}]" type="text"
                                               value="{{  isset($name) && is_array($name) && array_key_exists($local,$name)? $name[$local]: old("name[$local]")}}"
                                        >
                                    </div>
                                </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Ordered') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="ordered" type="number" value="{{ isset($nationality->ordered) ? $nationality->ordered : 1 }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">{{t('Draft')}}</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"  {{ isset($nationality) && $nationality->draft == 1 ? 'checked' :'' }} name="draft">
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
                                            class="btn btn-danger">{{ isset($nationality) ? t('Update'):t('Create') }}</button>&nbsp;
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
    {!! $validator->selector('#form_information') !!}
@endsection
