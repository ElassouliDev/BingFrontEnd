@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.manager_roles.index') }}">{{ t('Roles Management') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($role) ? t('Edit Role') : t('Add Role') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($role) ? t('Edit Role') : t('Add Role') }}</h3>
                    </div>
                </div>
    <div class="container mt-2">
        <form action="{{ route('manager.manager_roles.store') }}" method="post" class="kt-form kt-form--label-right">
            {{ csrf_field() }}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ t('name') }}:</strong>
                    <input type="text" name="name" placeholder="{{ t('name') }}" class="form-control">
                </div>
            </div>

                <table class="table table-striped table-bordered w-100">
                        <thead>
                            <th colspan="4" class=""><strong>{{ t('Permissions') }}:</strong> <input type="checkbox" onClick="toggle(this)" /> {{t('Check All')}} <br/></th>
                        </thead>
                        <tbody>
                            @foreach($permissions as $row)
                                <tr class="">
                                    @foreach($row as $permission)
                                        <td>
                                            <input type="checkbox" name="permission[]" value="{{ $permission->id }}" class="">
                                            {{ $permission->name }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                <button type="submit" class="btn btn-primary">{{ t('Save') }}</button>
            </div>
            <br />
        </div>
        </form>
    </div>

            </div>
        </div>
    </div>


@endsection
@section('script')
    <script type="text/javascript">
        function toggle(source) {
            checkboxes = document.getElementsByName('permission[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
    @endsection
