{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/summernote/dist/summernote.rtl.css') }}" rel="stylesheet" />
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.page.index') }}">{{ t('Pages') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($page) ? t('Edit Page') : t('Add Page') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($page) ? t('Edit Page') : t('Add Page') }} {{$page->name}}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right" action="{{ isset($page) ? route('manager.page.update', $page->id): route('manager.page.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($page))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Image') }}</label>--}}
{{--                                    <div class="col-lg-9 col-xl-6">--}}
{{--                                        <div class="upload-btn-wrapper">--}}
{{--                                            <button class="btn btn-brand">{{ t('upload file') }}</button>--}}
{{--                                            <input name="image" class="imgInp" id="imgInp" type="file" />--}}
{{--                                        </div>--}}
{{--                                        <img id="blah" @if(!isset($page) || is_null($page->getOriginal('image'))) style="display:none" @endif src="{{ isset($page) && !is_null($page->getOriginal('image'))  ? url($page->image):'' }}" width="150" alt="No file chosen" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-lg-6">
                                            <label>{{ t('Page Name') }} <small>({{ $local }})</small></label>
                                            <input class="form-control" name="name:{{$local}}" type="text" value="{{ isset($page->name) ? optional($page->translate($local))->name : old("name:$local") }}">
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-lg-6">
                                            <label>{{ t('Page Content') }} <small>({{ $local }})</small></label>
                                            <textarea class="form-control summernote" name="content:{{$local}}">{{ isset($page->content) ? optional($page->translate($local))->content : old("content:$local") }}</textarea>
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
                                    <button type="submit" class="btn btn-danger">{{ isset($page) ? t('update'):t('create') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/summernote/dist/summernote.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height:'300px',
            });
        });
    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
