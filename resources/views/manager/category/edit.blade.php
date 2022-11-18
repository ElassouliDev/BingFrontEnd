@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .label-info {
            background-color: #5bc0de;
        }
        .bootstrap-tagsinput .tag {
            margin-right: 2px;
            color: white;
        }
        .label {
            display: inline;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }
    </style>

@endsection


@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.category.index') }}">{{t('Categories')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($category) ? t('Edit Category') : t('Add Category') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($category) ? t('Edit Category') : t('Add Category') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ isset($category) ? route('manager.category.update', $category->id): route('manager.category.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($category))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @foreach(config('translatable.locales') as $local)
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }} <small>({{ $local }})</small></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name:{{$local}}" type="text" value="{{ isset($category->name) ? $category->translate($local)->name : old("name:$local") }}">
                                    </div>
                                </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Tags') }}</label>
                                    <div class="col-lg-9 col-xl-6"  style="width: 100%">
                                        <input class="form-control w-100" data-role="tagsinput" style="width: 100% !important;" name="tags" type="text" value="{{ isset($category->tags) ? $category->tags :old('tags') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Ordered') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="ordered" type="number" value="{{ isset($category->ordered) ? $category->ordered : 1 }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">{{t('Draft')}}</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"  {{ isset($category) && $category->draft == 1 ? 'checked' :'' }} name="draft">
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
                                            class="btn btn-danger">{{ isset($category) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script>
        $(document).ready(function() {
            $("form").keypress(function(e) {
                //Enter key
                if (e.which == 13) {
                    return false;
                }
            });
        });
    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
