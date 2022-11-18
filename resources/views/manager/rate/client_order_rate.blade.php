@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{t('Branches Rates')}}
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{t('Branches Rates')}}
                        </h3>
                    </div>

                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20">
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('User Name') }}:</label>
                                <input type="text" name="username" id="username" class="form-control kt-input" placeholder="{{t('User Name')}}">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('User Mobile') }}:</label>
                                <input type="text" name="user_mobile" id="user_mobile" class="form-control kt-input" placeholder="{{ t('User Mobile') }}">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Restaurants') }}:</label>
                                <select class="form-control restaurants" name="restaurant" id="restaurant">
                                    <option selected value="">{{t('Select Restaurant')}}</option>
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Branch') }}:</label>
                                <select class="form-control branches" name="branch" id="branch">
                                    <option selected value="">{{t('Select Branch')}}</option>
                                </select>
                            </div>


                        </div>

                        <div class="row kt-margin-b-20">

                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Order UUID') }}:</label>
                                <input type="text" name="uuid" id="uuid" class="form-control kt-input" placeholder="{{t('Order UUID')}}">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Date From') }}:</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control date_time" name="date_start" readonly="" placeholder="{{t('Select date')}}" id="date_start">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-calendar-o glyphicon-th"></i></span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Order Date To') }}:</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control date_time" name="date_end" readonly="" placeholder="{{t('Select date')}}" id="date_end">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-calendar-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Action') }}:</label>
                                <br />
                                <button type="submit" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    {{t('Search')}}
                                </button>
                            </div>

                        </div>
                    </form>
                    <table class="table text-center" id="stores-table">
                        <thead>
                        <th>{{t('Branch Name')}}</th>
                        <th>{{t('User Name')}}</th>
                        <th>{{t('Order UUID')}}</th>
                        <th>{{t('Rate')}}</th>
                        <th>{{t('Comment')}}</th>
                        <th>{{t('Rate Date')}}</th>
                        <th>{{t('Actions')}}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{w('Confirm Delete')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="delete_form">
                    <input type="hidden" name="_method" value="delete">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>{{t('Are You Sure To Delete The Selected Row')}}</p>
                        <p>{{t('Deleting The Selected Row Results In Deleting All Records Related To It')}}.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{w('Cancel')}}</button>
                        <button type="submit" class="btn btn-warning">{{w('Delete')}}</button>
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
    <script src="{{ asset('assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $('.date_time').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd hh:ii'
            });
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.client_order_rate.destroy", ":id") }}';
                url = url.replace(':id', id );
                $('#delete_form').attr('action',url);
            }));
            $(function() {
                $('#stores-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching: false,
                    @if(app()->getLocale() == 'ar')
                    language: {
                        url: "http://cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json"
                    },
                    @endif
                    ajax: {
                        url : '{{ route('manager.client_order_rate.index') }}',
                        data: function (d) {
                            d.uuid = $('#uuid').val();
                            d.username =  $('#username').val();
                            d.user_mobile =  $('#user_mobile').val();
                            d.restaurant =  $('#restaurant').val();
                            d.branch =  $('#branch').val();
                            d.date_start =  $('#date_start').val();
                            d.date_end =  $('#date_end').val();
                        }
                    },
                    columns: [
                        {data: 'branch', name: 'branch'},
                        {data: 'user', name: 'user'},
                        {data: 'uuid', name: 'uuid'},
                        {data: 'rate', name: 'rate'},
                        {data: 'comment', name: 'comment'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });
            $('#kt_search').click(function(e){
                e.preventDefault();
                $('#stores-table').DataTable().draw(true);
            });

            $('.restaurants').change(function () {
                var id = $(this).val();
                var url = '{{ route("manager.orderBranches") }}';
                $.ajax({
                    type: "get",
                    url: url,
                    data: {
                        'id': id,
                    }
                }).done(function (data) {
                    $('.branches').html(data.html);
                });
            });
        });
    </script>
@endsection
