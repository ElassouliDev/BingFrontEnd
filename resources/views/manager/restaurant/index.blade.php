@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{t('Restaurants')}}
        </li>
    @endpush
    @push('search')
        <div class="kt-subheader-search" style="background: linear-gradient(to right,#db1515,#ec5252)">
            <h3 class="kt-subheader-search__title">
                {{t('Search')}}
            </h3>
            <form class="kt-form">
                <div class="kt-grid kt-grid--desktop kt-grid--ver-desktop">
                    <div class="row" style="width: 100%">
                        <div class="col-lg-6">
                            <div class="kt-input-icon kt-input-icon--pill kt-input-icon--right">
                                <input style="background: white" type="text" id="search" class="form-control form-control-pill" placeholder="{{t('Keywords')}}">
                                <span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="la la-search"></i></span></span>
                            </div>

                        </div>
                        <div class="col-lg-2">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{t('Restaurants')}}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                <a href="{{ route('manager.restaurant.create') }}" class="btn btn-danger btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    {{t('Add Restaurant')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">

                    <form class="kt-form kt-form--fit kt-margin-b-20">
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Name') }}:</label>
                                <input type="text" name="name" value="{{request()->name}}" id="name"
                                       class="form-control kt-input"
                                       placeholder="{{t('Name')}}">
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Merchant Type') }}:</label>
                                <select class="form-control" name="merchant_type" id="merchant_type">
                                    <option selected value="">{{t('Select Merchant Type')}}</option>
                                    @foreach($merchant_types as $index=>$item)
                                        <option
                                            value="{{$item->id}}" {{request()->merchant_type == $item->id? 'selected' : ''}}>{{$item->name}}</option>
                                    @endforeach
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
                        <th>{{t('Mobile')}}</th>
                        <th>{{t('Email')}}</th>
                        <th>{{t('Merchant Type')}}</th>
                        <th>{{t('Active')}}</th>
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
    <div class="modal fade" id="activateModel" tabindex="-1" role="dialog" aria-labelledby="activateModel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{w('Confirm Activate Payments')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="activate_form">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>{{t('Are You Sure To Activate Payment To Selected Restaurant')}}</p>
                        <p>{{t('Activating the payment will allow the restaurant and its branches to start receiving orders')}}.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{w('Cancel')}}</button>
                        <button type="submit" class="btn btn-warning" id="activate_btn">{{w('Activate')}}</button>
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
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.restaurant.destroy", ":id") }}';
                url = url.replace(':id', id );
                $('#delete_form').attr('action',url);
            }));
            $(document).on('click','#activate_btn',(function(){
               $('#activate_btn').attr('disabled', true);
                $('#activate_form').submit();
            }));

            $(document).on('click','.activateRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.restaurant.activate", ":id") }}';
                url = url.replace(':id', id );
                $('#activate_form').attr('action',url);
            }));
            $(function() {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching: false,
                    dom: 'lBfrtip',
                    buttons: [
                        'excel', 'print'
                    ],
                    ajax: {
                        url : '{{ route('manager.restaurant.index') }}',
                        data: function (d) {
                            d.search = $("#search").val();
                            d.merchant_type = $("#merchant_type").val();
                            d.name = $("#name").val();
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'mobile', name: 'mobile'},
                        {data: 'email', name: 'email'},
                        {data: 'merchant_type', name: 'merchant_type'},
                        {data: 'active', name: 'active'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });
            $('#search').keyup(function(){
                $('#users-table').DataTable().draw(true);
            });
        });
    </script>
@endsection
