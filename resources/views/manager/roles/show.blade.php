@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.manager_roles.index') }}">{{ t('Roles Management') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($role) ? t('Add Role') : t('Edit Role') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($role) ? t('Add Role') : t('Edit Role') }}</h3>
                    </div>
                </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ t('name') }}:</strong>
                    {{ $role->name }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                    <th colspan="4" class=""><strong>{{ t('Permissions') }}:</strong></th>
                    </thead>
                    <tbody>
                    @foreach($rolePermissions as $row)
                        <tr class="">
                            @foreach($row as $permission)
                                <td>
                                    {{ $permission->name }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

            </div>
        </div>
    </div>

@endsection
