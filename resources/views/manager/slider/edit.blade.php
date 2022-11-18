@extends('manager.layout.container')
@section('style')
@endsection


@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.slider.index') }}">{{t('Sliders')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($slider) ? t('Edit Slider') : t('Add Slider') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($slider) ? t('Edit Slider') : t('Add Slider') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.slider.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($slider))
                        <input type="hidden" name="slider_id" value="{{$slider->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Image') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-brand">{{ t('upload file') }}</button>
                                            <input name="image" class="imgInp" id="imgInp" type="file"/>
                                        </div>
                                        <img id="blah"
                                             @if(!isset($slider) || is_null($slider->getOriginal('image'))) style="display:none"
                                             @endif src="{{ isset($slider) && !is_null($slider->getOriginal('image'))  ? url($slider->image):'' }}"
                                             width="150" alt="No file chosen"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Type') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="type" id="type" class="form-control">
                                            <option
                                                {{isset($slider) && $slider->type == \App\Models\SliderImages::URL_EXTERNAL? 'selected':''}}
                                                value="{{\App\Models\SliderImages::URL_EXTERNAL}}">{{t('External url')}}</option>
                                            <option
                                                {{isset($slider) && $slider->type == \App\Models\SliderImages::MERCHANT? 'selected':''}}
                                                value="{{\App\Models\SliderImages::MERCHANT}}">{{t('MERCHANT')}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" id="MERCHANT"
                                     style="display: {{!isset($slider) || $slider->type != \App\Models\SliderImages::MERCHANT? 'none':''}}"
                                >
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('MERCHANT') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="merchant_id">
                                            <option value="" selected disabled>{{t('Select MERCHANT')}}</option>
                                            @foreach($branches as $branch)
                                                <option value="{{$branch->id}}" {{isset($slider) && $slider->merchant_id == $branch->id
 ? 'selected':''}}>{{$branch->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="external_url"
                                     style="display: {{!isset($slider) || $slider->type != \App\Models\SliderImages::URL_EXTERNAL? 'none':''}}"
                                >
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('EXTERNAL Url') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="url" class="form-control" name="EXTERNAL_URL"
                                               value="{{isset($slider)?$slider->url:''}}">
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
                                            class="btn btn-danger">{{ isset($slider) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"
            type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("form").keypress(function (e) {
                //Enter key
                if (e.which == 13) {
                    return false;
                }
            });
        });

        $("#type").change(function () {
            var selectedType = $(this).children("option:selected").val();
            {{--console.log(selectedType,selectedType == {{\App\Models\SliderImages::MERCHANT}});--}}

            if (selectedType == {{\App\Models\SliderImages::MERCHANT}}) {
                $("#MERCHANT").show();
                $("#external_url").hide();
            } else {
                $("#MERCHANT").hide();
                $("#external_url").show();
            }
        });

    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
