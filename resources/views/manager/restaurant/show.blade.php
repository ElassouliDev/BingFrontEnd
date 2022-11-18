@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @push('breadcrumb')

        <li class="breadcrumb-item">
            <a href="{{ route('manager.restaurant.index') }}">{{ t('Restaurants') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Show Restaurant') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <!--begin:: Widgets/Applications/User/Profile3-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--user-profile-3">
                        <div class="kt-widget__top">
                            <div class="kt-widget__media kt-hidden-">
                                <img src="{{ isset($merchant->image)? url($merchant->image):'' }}" alt="image">
                            </div>
                            <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <label href="#" class="kt-widget__username">
                                        {{ $merchant->name }}
                                        @if($merchant->status == \App\Models\Merchant::ACTIVE)
                                            <i class="flaticon2-correct"></i>
                                        @endif
                                    </label>
                                </div>
                                <div class="kt-widget__subhead">
                                    <a href="mailTo:{{ $merchant->email }}">{{ $merchant->email }}</a>
                                </div>
                                <div class="kt-widget__info">
                                    <div class="kt-widget__desc">
                                        <label href="#" class="mr-2">{{ t('Member Since : ') .' '. $merchant->created_at->format('d-m-Y') }}</label>
                                        <br />
{{--                                        <label href="#" class="mr-2">{{t('Gender')}} : {{ $merchant->gender }} </label>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__media kt-hidden-">
                            </div>
                            <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <label href="#" class="kt-widget__username">
                                    </label>
                                    <div class="kt-widget__action">
                                        <button data-toggle="modal" data-target="#notificationModel" type="button" class="btn btn-brand btn-sm btn-upper"><i class="fa fa-newspaper"></i>{{ t('send notification') }}</button>
                                        <a href="{{ route('manager.go_restaurant', $merchant->id) }}" class="btn btn-brand btn-sm btn-upper">{{t('Go To Restaurant')}}</a>
                                    </div>
                                </div>
                                <div class="kt-widget__subhead">
                                    <label >{{ t('Merchant Type') .' :'. optional($merchant->merchantType)->name }}</label>
                                    <br>
                                    <label ><span>{{t('Mobile') }}</span>: {{ $merchant->phone }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget__bottom mt-0">
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-coins"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Balance') }}</span>
                                    <span class="kt-widget__value"><span> </span> {{$data['balance']}} </span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-coins"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Total Orders') }}</span>
                                    <span class="kt-widget__value"><span> </span> {{$data['total_orders']}} </span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-coins"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Total Profits') }}</span>
                                    <span class="kt-widget__value"><span> </span> {{$data['total_profits']}} </span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-coins"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Total App Commission') }}</span>
                                    <span class="kt-widget__value"><span> </span> {{$data['total_commission']}} </span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon2-shopping-cart-1"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Orders') }}</span>
                                    <span class="kt-widget__value">{{$data['orders']}}</span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-chat"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Restaurant Rate') }}</span>
                                    <span class="kt-widget__value">5 / {{$data['rate']}}</span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon2-open-box"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Branches') }}</span>
                                    <span class="kt-widget__value">{{$data['branches']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="notificationModel" tabindex="-1" role="dialog" aria-labelledby="notificationModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ t('Send Notification') }} #{{ $merchant->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="{{route('manager.restaurant_notification')}}" id="form_information">
                    <input type="hidden" name="user_id" value="{{$merchant->id}}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="container">
                            <div class="row form-group">
                                <label>{{ t('Notification Title') }}:</label>
                                <textarea name="title" class="form-control"></textarea>
                            </div>
                            <div class="row form-group">
                                <label>{{ t('Notification Content') }}:</label>
                                <textarea name="content" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                        <button type="submit" class="btn btn-warning">{{ t('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ t('Restaurant Details') }}
                        </h3>
                    </div>

                </div>
                <div class="kt-portlet__body">
                    <ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-warning" role="tablist">

                        <li class="nav-item active">
                            <a class="nav-link" data-toggle="tab" href="#branches" role="tab">{{ t('Branches') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#payments" role="tab">{{ t('Payments') }}</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" data-toggle="tab" href="#orders" role="tab">{{ t('Orders') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#notifications" role="tab">{{ t('Notifications') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#rates" role="tab">{{ t('Rates') }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="rates" role="tabpanel">
                            <table class="table text-center" id="rates-table">
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
                        <div class="tab-pane " id="orders" role="tabpanel">
                            <table class="table text-center" id="branches-orders-table">
                                <thead>
                                <th>{{ t('UUID') }}</th>
                                <th>{{ t('User') }}</th>
                                <th>{{ t('Merchant') }}</th>
                                <th>{{ t('Branch') }}</th>
                                <th>{{ t('Total') }}</th>
                                <th>{{ t('Paid Type') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('Ordered At') }}</th>
                                <th>{{ t('Pick Up Time') }}</th>
                                <th>{{ t('Actions') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane " id="notifications" role="tabpanel">
                            <table class="table text-center" id="notification-table">
                                <thead>
                                <th>{{ t('Title') }}</th>
                                <th>{{ t('Content') }}</th>
                                <th>{{ t('Received at') }}</th>
                                <th>{{ t('Actions') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="payments" role="tabpanel">
                            <table class="table text-center" id="payment-table">
                                <thead>
                                <th>{{ t('Amount') }}</th>
                                <th>{{ t('Note') }}</th>
                                <th>{{ t('Received at') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane active" id="branches" role="tabpanel">
                            <table class="table text-center" id="branches-table">
                                <thead>
                                <th>{{ t('Name') }}</th>
                                <th>{{ t('Email') }}</th>
                                <th>{{ t('Mobile') }}</th>
                                <th>{{ t('Active') }}</th>
                                <th>{{ t('Created At') }}</th>
                                <th>{{ t('Actions') }}</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="notifyDeleteModel" tabindex="-1" role="dialog" aria-labelledby="notifyDeleteModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{w('Confirm Delete')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="notify_delete_form">
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
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/demo1/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/demo6/scripts.bundle.js') }}" type="text/javascript"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $('.datepicker_3').datepicker({
                format:'dd-mm-yyyy',
            });
            $(document).on('click','.notifyDeleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.notification.destroy", ":id") }}';
                url = url.replace(':id', id );
                $('#notify_delete_form').attr('action',url);
            }));
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.branch.destroy", ":id") }}';
                url = url.replace(':id', id );
                $('#delete_form').attr('action',url);
            }));
            $(function() {
                var table = $('#notification-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching:false,
                    dom: 'lBfrtip',
                    buttons: [
                        'csv', 'excel', 'print'
                    ],
                    ajax: {
                        url : '{{ route('manager.restaurant.notifications') }}',
                        data: function (d) {
                            d.user = "{{$merchant->id}}"
                        }
                    },
                    columns: [
                        {data: 'title', name: 'title'},
                        {data: 'content', name: 'content'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
                table.buttons().container()
                    .appendTo( '#example_wrapper .col-md-6:eq(0)' );
            });

            $(function() {
                var table = $('#payment-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching:false,
                    dom: 'lBfrtip',
                    buttons: [
                        'csv', 'excel', 'print'
                    ],
                    ajax: {
                        url : '{{ route('manager.payment.index') }}',
                        data: function (d) {
                            d.restaurant = "{{$merchant->id}}"
                        }
                    },
                    columns: [
                        {data: 'amount', name: 'amount'},
                        {data: 'note', name: 'note'},
                        {data: 'created_at', name: 'created_at'},
                    ],
                });
                table.buttons().container()
                    .appendTo( '#example_wrapper .col-md-6:eq(0)' );
            });

            $(function() {
                $('#branches-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching: false,
                    dom: 'lBfrtip',
                    buttons: [
                        'csv', 'excel', 'print'
                    ],
                    ajax: {
                        url : '{{ route('manager.branch.index') }}',
                        data: function (d) {
                            d.merchant = "{{$merchant->id}}";
                            d.search = $("#search").val();
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'mobile', name: 'mobile'},
                        {data: 'active', name: 'active'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'},
                    ],
                });
            });

            $(function() {
                var table = $('#branches-orders-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching:false,
                    dom: 'lBfrtip',
                    buttons: [
                        'csv', 'excel', 'print'
                    ],
                    ajax: {
                        url : '{{ route('manager.order.index') }}',
                        data: function (d) {
                            d.restaurant = "{{$merchant->id}}"
                        }
                    },
                    columns: [
                        {data: 'uuid', name: 'uuid'},
                        {data: 'user', name: 'user'},
                        {data: 'merchant', name: 'mercahnt'},
                        {data: 'branch', name: 'branch'},
                        {data: 'total', name: 'total'},
                        {data: 'paid_type', name: 'paid_type'},
                        {data: 'status_name', name: 'status_name'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'pick_up_time', name: 'pick_up_time'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
                table.buttons().container()
                    .appendTo( '#example_wrapper .col-md-6:eq(0)' );
            });

            $(function() {
                $('#rates-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching: false,
                    dom: 'lBfrtip',
                    buttons: [
                        'csv', 'excel', 'print'
                    ],
                    ajax: {
                        url : '{{ route('manager.client_order_rate.index') }}',
                        data: function (d) {
                            d.restaurant = "{{$merchant->id}}";
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

        });
    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
@endsection
