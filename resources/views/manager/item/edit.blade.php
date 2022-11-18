@extends('manager.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.meal.index') }}">{{t('Meals')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($meal) ? t('Edit Meal') : t('Add Meal') }}
        </li>
    @endpush

    @php
        $name = isset($meal) ? $meal->getTranslations()['name'] : null;
        $description = isset($meal) ? $meal->getTranslations()['description'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($meal) ? t('Edit Meal') : t('Add Meal') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.meal.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($meal))
                        <input type="hidden" name="item_id" value="{{$meal->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab"
                                   href="#kt_tabs_1_1">{{t('Main Infromation')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_2">{{t('Addons')}}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                <div class="kt-section kt-section--first">
                                    <div class="kt-section__body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label
                                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('Image') }}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="upload-btn-wrapper">
                                                            <button
                                                                class="btn btn-brand">{{ t('upload file') }}</button>
                                                            <input name="image" class="imgInp" id="imgInp" type="file"/>
                                                        </div>
                                                        <img id="blah"
                                                             @if(!isset($meal) || is_null($meal->getOriginal('image'))) style="display:none"
                                                             @endif src="{{ isset($meal) && !is_null($meal->getOriginal('image'))  ? url($meal->image):'' }}"
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
                                                        <input name="name[{{$local}}]" type="text"
                                                               class="form-control" placeholder=""
                                                               value="{{  isset($name) && is_array($name) && array_key_exists($local,$name)? $name[$local]: old("name[$local]")}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Restaurant') }} </label>
                                                    <select class="form-control restaurants" name="restaurant_id">
                                                        <option value="" selected
                                                                disabled>{{t('Select Restaurant')}}</option>
                                                        @foreach($restaurants as $restaurant)
                                                            <option
                                                                value="{{$restaurant->id}}" {{isset($meal) && $meal->merchant_id == $restaurant->id ? 'selected':''}}>{{$restaurant->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Branch') }} </label>
                                                    <select class="form-control branches" name="branch_id">
                                                        <option value="" selected
                                                                disabled>{{t('Select Branch')}}</option>

                                                        @isset($branches)
                                                            @foreach($branches as $branch)
                                                                <option
                                                                    value="{{$branch->id}}" {{isset($meal) && $meal->branch_id == $branch->id  ? 'selected':''}}>{{$branch->name}}</option>
                                                            @endforeach
                                                        @endisset


                                                    </select>
                                                </div>
                                            </div>
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>{{ t('Description') }} <small>({{ $local }}
                                                                )</small></label>
                                                        <textarea class="form-control"
                                                                  name="description[{{$local}}]">{{  isset($description) && is_array($description) && array_key_exists($local,$description)? $description[$local]: old("description[$local]")}}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Classification') }} </label>
                                                    <select class="form-control classifications"
                                                            name="classification_id">
                                                        <option value="" selected
                                                                disabled>{{t('Select Classification')}}</option>
                                                        @isset($classifications)
                                                            @foreach($classifications as $classification)
                                                                <option
                                                                    value="{{$classification->id}}" {{isset($meal) && $meal->classification_id == $classification->id ? 'selected':''}}>{{$classification->name}}</option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>{{ t('Calories') }} </label>
                                                    <input name="calories" type="number"
                                                           value="{{ isset($meal) ? $meal->calories : old("calories") }}"
                                                           class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>{{ t('Draft') }} </label>
                                                    <div class="w-100">
                                                <span class="kt-switch">
                                                    <label>
                                                    <input type="checkbox" value="1"
                                                           {{ isset($meal) && $meal->draft == 1 ? 'checked' :'' }} name="draft">
                                                    <span></span>
                                                    </label>
                                                </span>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{ t('Has Discount') }} </label>
                                                        <div class="w-100">
                                                <span class="kt-switch">
                                                    <label>
                                                    <input type="checkbox" value="1"
                                                           {{ isset($meal) && $meal->has_discount == true ? 'checked' :'' }} name="has_discount"
                                                           id="has_discount">
                                                    <span></span>
                                                    </label>
                                                </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2" id="discount" style="display: {{isset($meal) && $meal->has_discount == true ? '':'none' }}">
                                                    <div class="form-group">
                                                        <label>{{ t('Discount') }} </label>
                                                        <input name="discount" type="number"
                                                               value="{{ isset($meal) ? $meal->discount : old("discount") }}"
                                                               class="form-control" placeholder="discount">
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ t('Options Category') }} </label>
                                                    <select class="form-control" name="option_category">
                                                        <option value="" selected
                                                                disabled>{{t('Select Category')}}</option>
                                                        @foreach($options_categories as $option)
                                                            <option
                                                                value="{{$option->id}}" {{isset($option_category) && $option_category->option_category_id == $option->id ? 'selected':''}}>{{$option->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>{{t('Add New')}}</label>
                                                    <button type="button" class="btn btn-danger w-100 add_button"><i
                                                            class="fa fa-plus-circle"></i> {{t('Add Option')}}</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row_container">
                                            @if(!isset($meal))
                                                <div class="row">
                                                    @foreach(config('translatable.locales') as $local)
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>{{ t('Name') }} <small>({{ $local }}
                                                                        )</small></label>
                                                                <input name="option[1][name][{{$local}}]" type="text"
                                                                       class="form-control" placeholder="">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>{{ t('Price') }}</label>
                                                            <input name="option[1][price]" type="number" step="0.9"
                                                                   class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>{{ t('Ordered') }}</label>
                                                            <input name="option[1][ordered]" value="1" type="number"
                                                                   class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label>{{ t('Draft') }} </label>
                                                            <div class="w-100">
                                                                <span class="kt-switch">
                                                                    <label>
                                                                    <input type="checkbox" value="1"
                                                                           name="option[1][draft]">
                                                                    <span></span>
                                                                    </label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @isset($meal)
                                                @foreach($meal->prices as $price)
                                                    @php
                                                        $name_price = isset($price) ? $price->getTranslations()['name'] : null;
                                                    @endphp


                                                    <div class="row">
                                                        @foreach(config('translatable.locales') as $local)
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>{{ t('Name') }} <small>({{ $local }})</small></label>
                                                                    <input
                                                                        name="old_option[{{$price->id}}][name][{{$local}}]"
                                                                        type="text" class="form-control"

                                                                        value="{{  isset($name_price) &&
is_array($name_price) && array_key_exists($local,$name_price)? $name_price[$local]: old("old_addon[$price->id][name[$local]]")}}"


                                                                    >
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>{{ t('Price') }}</label>
                                                                <input name="old_option[{{$price->id}}][price]"
                                                                       type="number" step="0.9" class="form-control"
                                                                value="{{$price->getAttributes()['price']}}">

                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>{{ t('Ordered') }}</label>
                                                                <input name="old_option[{{$price->id}}][ordered]"
                                                                       type="number" class="form-control"
                                                                       value="{{$price->ordered}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>{{ t('Draft') }} </label>
                                                                <div class="w-100">
                                                                    <span class="kt-switch">
                                                                        <label>
                                                                        <input type="checkbox" value="1"
                                                                               name="old_option[{{$price->id}}][draft]" {{$price->draft ? 'checked':''}}>
                                                                        <span></span>
                                                                        </label>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label>{{ t('Delete') }} </label>
                                                                <div class="w-100">
                                                                    <button
                                                                        class="btn btn-danger btn-icon removed_button"
                                                                        data-id="{{$price->id}}"><i
                                                                            class="fa fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endisset
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{t('Add New')}}</label>
                                            <button type="button" class="btn btn-danger w-100 add_addon"><i
                                                    class="fa fa-plus-circle"></i> {{t('Add Addon')}}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="container_addon">
                                    @isset($meal)
                                        @foreach($meal->addons as $addon)
                                            @php

                                                $name_addon = isset($addon) ? $addon->getTranslations()['name'] : null;
                                            @endphp

                                            <div class="row">
                                                @foreach(config('translatable.locales') as $local)
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>{{ t('Name') }} <small>({{ $local }})</small></label>
                                                            <input name="old_addon[{{$addon->id}}][name][{{$local}}]"
                                                                   type="text" class="form-control"
                                                                   value="{{  isset($name_addon) &&
is_array($name_addon) && array_key_exists($local,$name_addon)? $name_addon[$local]: old("old_addon[$addon->id][name[$local]]")}}"
                                                            >
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{ t('Price') }}</label>
                                                        <input name="old_addon[{{$addon->id}}][price]" type="number"
                                                               step="0.9" class="form-control"
                                                               value="{{$addon->price}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>{{ t('Ordered') }}</label>
                                                        <input name="old_addon[{{$addon->id}}][ordered]" type="number"
                                                               class="form-control" value="{{$addon->ordered}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>{{ t('Draft') }} </label>
                                                        <div class="w-100">
                                                            <span class="kt-switch">
                                                                <label>
                                                                <input type="checkbox" value="1"
                                                                       name="old_addon[{{$addon->id}}][draft]" {{$addon->draft?'checked':''}}>
                                                                <span></span>
                                                                </label>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label>{{ t('Delete') }} </label>
                                                        <div class="w-100">
                                                            <button class="btn btn-danger btn-icon removed_addon"
                                                                    data-id="{{$addon->id}}"><i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endisset
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($meal) ? t('Update'):t('Create') }}</button>&nbsp;
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
            $('.restaurants').change(function () {
                var id = $(this).val();
                var url = '{{ route("manager.branches") }}';
                $.ajax({
                    type: "get",
                    url: url,
                    data: {
                        'id': id,
                    }
                }).done(function (data) {
                    $('.branches').html(data.html);
                    $('.classifications').html("<option value='' selected disabled>{{t('Select Classification')}}</option>");
                });
            });
            $('.branches').change(function () {
                var branch_id = $(this).val();
                var merchant_id = $('.restaurants').val();
                // console.log(branch_id,merchant_id);
                var url = '{{ route("manager.classifications") }}';
                $.ajax({
                    type: "get",
                    url: url,
                    data: {
                        'branch_id': branch_id,
                        'merchant_id': merchant_id,
                    }
                }).done(function (data) {
                    $('.classifications').html(data.html);
                });
            });
            var x = 1; //Initial field counter is 1
            var y = 1; //Initial field counter is 1

            var maxField = 5; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.row_container'); //Input field wrapper
            //Once add button is clicked
            $(addButton).click(function () {
                //Check maximum number of input fields
                if (x < maxField) {
                    x++; //Increment field counter
                    y++; //Increment field counter
                    $(wrapper).append("<div class=\"row\">\n" +
                        "@foreach(config('translatable.locales') as $local)\n" +
                        "<div class=\"col-md-3\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Name') }} <small>({{$local}})</small></label>\n" +
                        "<input name=\"option[" + y + "][name][{{$local}}]\" type=\"text\" class=\"form-control\" placeholder=\"\">\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "@endforeach\n" +
                        "<div class=\"col-md-2\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Price') }}</label>\n" +
                        "<input name=\"option[" + y + "][price]\" type=\"number\" step=\"0.99999999\" class=\"form-control\" placeholder=\"\">\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "<div class=\"col-md-2\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Ordered') }}</label>\n" +
                        "<input name=\"option[" + y + "][ordered]\" type=\"number\" value=\"1\" class=\"form-control\" placeholder=\"\">\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "<div class=\"col-md-1\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Draft') }} </label>\n" +
                        "<div class=\"w-100\">\n" +
                        "<span class=\"kt-switch\">\n" +
                        "<label>\n" +
                        "<input type=\"checkbox\" value=\"1\"  name=\"option[" + y + "][draft]\">\n" +
                        "<span></span>\n" +
                        "</label>\n" +
                        "</span>\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "<div class=\"col-md-1\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Delete') }} </label>\n" +
                        "<div class=\"w-100\">\n" +
                        "<button class=\"btn btn-danger btn-icon remove_button\"><i class=\"fa fa-times\"></i></button>\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "</div>"); //Add field html
                }
            });
            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function (e) {
                e.preventDefault();
                $(this).parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
                x--;
            });
            // Once remove button is clicked
            $(wrapper).on('click', '.removed_button', function (e) {
                e.preventDefault();
                let ele = $(this);
                ele.attr('disabled', true)
                ele.parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
                x--; //Decrement field counter


            });


            var addon_x = 0; //Initial field counter is 1
            var addon_y = 0; //Initial field counter is 1

            var addon_maxField = 5; //Input fields increment limitation
            var addon_addButton = $('.add_addon'); //Add button selector
            var addon_wrapper = $('.container_addon'); //Input field wrapper
            //Once add button is clicked
            $(addon_addButton).click(function () {
                //Check maximum number of input fields
                if (addon_x < addon_maxField) {
                    addon_x++; //Increment field counter
                    addon_y++; //Increment field counter
                    $(addon_wrapper).append("<div class=\"row\">\n" +
                        "@foreach(config('translatable.locales') as $local)\n" +
                        "<div class=\"col-md-3\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Name') }} <small>({{$local}})</small></label>\n" +
                        "<input name=\"addon[" + addon_y + "][name][{{$local}}]\" type=\"text\" class=\"form-control\" placeholder=\"\">\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "@endforeach\n" +
                        "<div class=\"col-md-2\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Price') }}</label>\n" +
                        "<input name=\"addon[" + addon_y + "][price]\" value=\"1\" type=\"number\" step=\"0.99999999\" class=\"form-control\" placeholder=\"\">\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "<div class=\"col-md-2\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Ordered') }}</label>\n" +
                        "<input name=\"addon[" + addon_y + "][ordered]\" type=\"number\" value=\"1\" class=\"form-control\" placeholder=\"\">\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "<div class=\"col-md-1\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Draft') }} </label>\n" +
                        "<div class=\"w-100\">\n" +
                        "<span class=\"kt-switch\">\n" +
                        "<label>\n" +
                        "<input type=\"checkbox\" value=\"1\"  name=\"addon[" + addon_y + "][draft]\">\n" +
                        "<span></span>\n" +
                        "</label>\n" +
                        "</span>\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "<div class=\"col-md-1\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label>{{ t('Delete') }} </label>\n" +
                        "<div class=\"w-100\">\n" +
                        "<button class=\"btn btn-danger btn-icon remove_addon\"><i class=\"fa fa-times\"></i></button>\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "</div>\n" +
                        "</div>"); //Add field html
                }
            });
            //Once remove button is clicked
            $(addon_wrapper).on('click', '.remove_addon', function (e) {
                e.preventDefault();
                $(this).parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
                addon_x--;
            });
            // Once remove button is clicked
            $(addon_wrapper).on('click', '.removed_addon', function (e) {
                e.preventDefault();

                let ele = $(this);
                ele.attr('disabled', true)
                ele.parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
                addon_x--; //Decrement field counter
                //btn.attr('disabled', false)

            });
        });

        $("#has_discount").change(function () {
            console.log(this.checked);
            if (this.checked) {
                $("#discount").show();
            }else{
                $("#discount").hide();
            }
        });
    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
