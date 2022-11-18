{{--
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
 --}}
@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @push('breadcrumb')

        <li class="breadcrumb-item">
            {{ t('Join Us') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ t('Join Us') }}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20">
                        <div class="row kt-margin-b-20">

                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Source') }}:</label>
                                <select id="source" class="form-control kt-input">
                                    <option value="">Select</option>
                                    <option value="{{\App\Models\User::ANDROID}}">{{ t('Mobile App') }}</option>
                                    <option value="{{\App\Models\User::WEB}}">{{ t('Web App') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('City') }}:</label>
                                <select id="city_id"  class="form-control kt-input">
                                    <option value="">{{t('Select')}}</option>
                                    @foreach($cities as $city)
                                    <option value="{{$city->id}}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Merchant Type') }}:</label>
                                <select id="merchant_type_id"  class="form-control kt-input">
                                    <option value="">{{t('Select')}}</option>
                                    @foreach($merchant_types as $type)
                                    <option value="{{$type->id}}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Date') }}:</label>
                                <input type="text" id="date"  autocomplete="off" class="form-control kt-input date" placeholder="{{t('Date')}}">
                            </div>
                        </div>

                        <div class="row kt-margin-b-20">
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Name') }}:</label>
                                <input type="text" id="name" name="name" class="form-control kt-input" placeholder="{{ t('Name') }}">
                            </div>
                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Mobile') }}:</label>
                                <input type="text" id="mobile" name="mobile" class="form-control kt-input" placeholder="{{ t('Mobile') }}">
                            </div>

                            <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Action') }}:</label>
                                <br />
                                <button type="submit" class="btn btn-danger btn-brand--icon" id="kt_search">
                                    <span>
                                        <i class="la la-search"></i>
                                        <span>{{t('Search')}}</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>{{ t('Owner Name') }}</th>
                        <th>{{ t('Mobile') }}</th>
                        <th>{{ t('Email') }}</th>
                        <th>{{ t('Source') }}</th>
                        <th>{{ t('City') }}</th>
                        <th>{{ t('Merchant Name') }}</th>
                        <th>{{ t('Merchant Type') }}</th>
                        <th>{{ t('Received At') }}</th>
                        <th>{{ t('Actions') }}</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">{{ t('Confirm Delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="delete_form">
                    <input type="hidden" name="_method" value="delete">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>{{ t('Are You Sure To Delete The Selected Record?') }}</p>
                        <p>{{ t('Deleting The Record Will Delete All Records Related To It.') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                        <button type="submit" class="btn btn-warning">{{ t('Delete') }}</button>
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
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function(){
            $('.date').datepicker({
                autoclose: true,
                rtl: true,
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd'
            });
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.join_us.destroy", ":id") }}';
                url = url.replace(':id', id );
                $('#delete_form').attr('action',url);
            }));
            $('#kt_search').click(function(e){
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            });
            $(function() {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering:false,
                    searching: false,
                    ajax: {
                        url : '{{ route('manager.join_us.index') }}',
                        data: function (d) {
                            d.source = $("#source").val();
                            d.city_id = $("#city_id").val();
                            d.merchant_type_id = $("#merchant_type_id").val();
                            d.date = $("#date").val();
                            d.name = $("#name").val();
                            d.mobile = $("#mobile").val();
                        }
                    },
                    columns: [
                        {data: 'merchant_name', name: 'merchant_name'},
                        {data: 'phone', name: 'phone'},
                        {data: 'email', name: 'email'},
                        {data: 'source_name', name: 'source_name'},
                        {data: 'city', name: 'city'},
                        {data: 'merchant_name', name: 'merchant_name'},
                        {data: 'merchant_type', name: 'merchant_type'},
                        {data: 'created_at', name: 'created_at'},
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
