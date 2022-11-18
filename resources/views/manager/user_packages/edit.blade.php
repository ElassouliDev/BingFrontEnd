@extends('manager.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.package.index') }}">{{t('Packages')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($package) ? t('Edit Package') : t('Add Package') }}
        </li>
    @endpush

    @php
        $name = isset($package) ? $package->getTranslations()['name'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($package) ? t('Edit Package') : t('Add Package') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.package.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($package))
                        <input type="hidden" name="package_id" value="{{$package->id}}">
                    @endif


                    <div class="kt-portlet__body">
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
                                                             @if(!isset($package) || is_null($package->getOriginal('image'))) style="display:none"
                                                             @endif src="{{ isset($package) && !is_null($package->getOriginal('image'))  ? url($package->image):'' }}"
                                                             width="150" alt="No file chosen"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Name') }} <small>({{ $local }})</small></label>
                                                        <input name="name[{{$local}}]" type="text"
                                                               class="form-control" placeholder=""
                                                               value="{{  isset($name) && is_array($name) && array_key_exists($local,$name)? $name[$local]: old("name[$local]")}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Months Number') }}</label>
                                                        <input class="form-control"  placeholder=""
                                                               name="months_number" type="number"
                                                               value="{{ isset($package) ? $package->months :old('months_number') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Price') }}</label>
                                                        <input class="form-control"  placeholder=""
                                                               name="price" type="number"
                                                               value="{{ isset($package) ? $package->price :old('price') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Deliveries Number') }}</label>
                                                        <input class="form-control"  placeholder=""
                                                               name="deliveries_number" type="number"
                                                               value="{{ isset($package) ? $package->delivery_number :old('deliveries_number') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('KM Limit') }}</label>
                                                        <input class="form-control"  placeholder=""
                                                               name="km_limit" type="number"
                                                               value="{{ isset($package) ? $package->km_limit :old('km_limit') }}">
                                                    </div>
                                                </div>

                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>{{ t('Draft') }} </label>
                                                    <div class="w-100">
                                                <span class="kt-switch">
                                                    <label>
                                                    <input type="checkbox" value="1"
                                                           {{ isset($package) && $package->draft == 1 ? 'checked' :'' }} name="draft">
                                                    <span></span>
                                                    </label>
                                                </span>
                                                    </div>
                                                </div>
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
                                            class="btn btn-danger">{{ isset($package) ? t('Update'):t('Create') }}</button>&nbsp;
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
                        "<input name=\"option[" + y + "][name:{{$local}}]\" type=\"text\" class=\"form-control\" placeholder=\"\">\n" +
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
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var id = $(this).attr('data-id');
                var url = '{{route('manager.price.delete', ':id')}}';
                url = url.replace(':id', id);
                ele.attr('disabled', true)
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': csrf,
                        '_method': 'delete',
                    },
                    success: function (data) {
                        if (data.success) {
                            ele.parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
                            x--; //Decrement field counter
                            //btn.attr('disabled', false)
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function (errMsg) {
                        toastr.error(errMsg.responseJSON.message);
                        btn.attr('disabled', false)
                    }
                });
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
                        "<input name=\"addon[" + addon_y + "][name:{{$local}}]\" type=\"text\" class=\"form-control\" placeholder=\"\">\n" +
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
                let csrf = $('meta[name="csrf-token"]').attr('content');
                var id = $(this).attr('data-id');
                var url = '{{route('manager.addon.delete', ':id')}}';
                url = url.replace(':id', id);
                ele.attr('disabled', true)
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': csrf,
                        '_method': 'delete',
                    },
                    success: function (data) {
                        if (data.success) {
                            ele.parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
                            addon_x--; //Decrement field counter
                            //btn.attr('disabled', false)
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function (errMsg) {
                        toastr.error(errMsg.responseJSON.message);
                        btn.attr('disabled', false)
                    }
                });
            });
        });

    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
