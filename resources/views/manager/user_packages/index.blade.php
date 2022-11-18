@extends('manager.layout.container')


@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}"
          rel="stylesheet" type="text/css"/>

@endsection


@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{t('User Packages')}}
        </li>
    @endpush

    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{t('User Packages')}}
                        </h3>
                    </div>
                    {{--                    <div class="kt-portlet__head-toolbar">--}}
                    {{--                        <div class="kt-portlet__head-wrapper">--}}
                    {{--                            <div class="kt-portlet__head-actions">--}}
                    {{--                                <a href="{{ route('manager.user_packages.create') }}"--}}
                    {{--                                   class="btn btn-danger btn-elevate btn-icon-sm">--}}
                    {{--                                    <i class="la la-plus"></i>--}}
                    {{--                                    {{t('Add Package')}}--}}
                    {{--                                </a>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20">
                        <div class="row kt-margin-b-20">


                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('User Name') }}:</label>
                                <input type="text" name="user" id="user" class="form-control kt-input"
                                       placeholder="{{t('User Name')}}">
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Package Name') }}:</label>
                                <input type="text" name="package" id="package" class="form-control kt-input"
                                       placeholder="{{t('Package Name')}}">
                            </div>


                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Order Date From') }}:</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control date_time" name="date_start" id="date_start"
                                           readonly="" placeholder="{{t('Select date')}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                class="la la-calendar-o glyphicon-th"></i></span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Order Date To') }}:</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control date_time" name="date_end" readonly=""
                                           placeholder="{{t('Select date')}}" id="date_end">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                class="la la-calendar-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Status') }}:</label>
                                <select class="form-control" name="status" id="status">
                                    <option selected value="">{{t('Select Status')}}</option>
                                    <option value="1">{{t('Expired')}}</option>
                                    <option value="0">{{t('Active')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Action') }}:</label>
                                <br/>
                                <button type="submit" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    {{t('Search')}}
                                </button>
                                &nbsp;&nbsp
                            </div>
                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>{{t('Name')}}</th>
                        <th>{{t('Package')}}</th>
                        <th>{{t('Expire Date')}}</th>
                        <th>{{t('Status')}}</th>
                        <th>{{t('Created At')}}</th>
                        <th>{{t('Actions')}}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reactiveModel" tabindex="-1" role="dialog" aria-labelledby="reactiveModel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{w('Confirm Reactive')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="reactive_form">
                    {{--                    <input type="hidden" name="_method" value="delete">--}}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>{{t('Are You Sure To Reactive The Selected Row')}}</p>
                        {{--                        <p>{{t('Deleting The Selected Row Results In Deleting All Records Related To It')}}.</p>--}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{w('Cancel')}}</button>
                        <button type="submit" class="btn btn-warning">{{w('Reactive')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="activeModel" tabindex="-1" role="dialog" aria-labelledby="activeModel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{w('Confirm Active')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="active_form">
                    {{--                    <input type="hidden" name="_method" value="delete">--}}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>{{t('Are You Sure To Active The Selected Row')}}</p>
                        {{--                        <p>{{t('Deleting The Selected Row Results In Deleting All Records Related To It')}}.</p>--}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{w('Cancel')}}</button>
                        <button type="submit" class="btn btn-warning">{{w('Active')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="expireModel" tabindex="-1" role="dialog" aria-labelledby="expireModel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{w('Confirm Expire')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="expire_form">
                    {{--                    <input type="hidden" name="_method" value="delete">--}}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>{{t('Are You Sure To Expire The Selected Row')}}</p>
                        {{--                        <p>{{t('Deleting The Selected Row Results In Deleting All Records Related To It')}}.</p>--}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{w('Cancel')}}</button>
                        <button type="submit" class="btn btn-warning">{{w('Expire')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="{{ asset('assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"
            type="text/javascript"></script>


    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function () {


            $('.date_time').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $(document).on('click', '.reactiveRecord', (function () {
                var id = $(this).data("id");
                var url = '{{ route("manager.user_packages.reactive", ":id") }}';
                url = url.replace(':id', id);
                $('#reactive_form').attr('action', url);
            }));


            $(document).on('click', '.activeRecord', (function () {
                var id = $(this).data("id");
                var url = '{{ route("manager.user_packages.active", ":id") }}';
                url = url.replace(':id', id);
                $('#active_form').attr('action', url);
            }));

            $(document).on('click', '.expireRecord', (function () {
                var id = $(this).data("id");
                var url = '{{ route("manager.user_packages.expire", ":id") }}';
                url = url.replace(':id', id);
                $('#expire_form').attr('action', url);
            }));
            $(function () {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    ajax: {
                        url: '{{ route('manager.user_packages.index') }}',
                        data: function (d) {
                            d.user = $("#user").val();
                            d.package = $("#package").val();
                            d.date_start = $("#date_start").val();
                            d.date_end = $("#date_end").val();
                            ;
                            d.status = $("#status").val();
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'package', name: 'package'},
                        {data: 'expire_date', name: 'expire_date'},
                        {data: 'status', name: 'status'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });
            $('#kt_search').click(function (e) {
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            });


        });
    </script>
@endsection
