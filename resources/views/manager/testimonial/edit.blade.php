@extends('manager.layout.container')
@section('style')
@endsection


@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.testimonial.index') }}">{{t('Testimonials')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($testimonial) ? t('Edit Testimonial') : t('Add Testimonial') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($testimonial) ? t('Edit Testimonial') : t('Add Testimonial') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ isset($testimonial) ? route('manager.testimonial.update', $testimonial->id): route('manager.testimonial.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($testimonial))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @foreach(config('translatable.locales') as $local)
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }} <small>({{ $local }})</small></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name:{{$local}}" type="text" value="{{ isset($testimonial->name) ? $testimonial->translate($local)->name : old("name:$local") }}">
                                    </div>
                                </div>
                                @endforeach
                                @foreach(config('translatable.locales') as $local)
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Content') }} <small>({{ $local }})</small></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <textarea name="content:{{$local}}" class="form-control">{{ isset($testimonial->content) ? $testimonial->translate($local)->content : old("content:$local") }}</textarea>
                                    </div>
                                </div>
                                @endforeach
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Type') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="type">
                                            <option selected disabled value="">{{t('Select Type')}}</option>
                                            <option value="facebook" {{isset($testimonial) && $testimonial->type == 'facebook' ? 'selected':''}}>{{t('facebook')}}</option>
                                            <option value="twitter" {{isset($testimonial) && $testimonial->type == 'twitter' ? 'selected':''}}>{{t('twitter')}}</option>
                                            <option value="instagram" {{isset($testimonial) && $testimonial->type == 'instagram' ? 'selected':''}}>{{t('instagram')}}</option>
                                            <option value="linkedIn" {{isset($testimonial) && $testimonial->type == 'linkedIn' ? 'selected':''}}>{{t('linkedIn')}}</option>
                                            <option value="google" {{isset($testimonial) && $testimonial->type == 'google' ? 'selected':''}}>{{t('google')}}</option>
                                            <option value="apple" {{isset($testimonial) && $testimonial->type == 'apple' ? 'selected':''}}>{{t('apple')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Ordered') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="ordered" type="number" value="{{ isset($testimonial->ordered) ? $testimonial->ordered : 1 }}">
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
                                            class="btn btn-danger">{{ isset($testimonial) ? t('Update'):t('Create') }}</button>&nbsp;
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
