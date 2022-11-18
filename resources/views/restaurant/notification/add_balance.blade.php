{{--Dev Omar Shaheen
    Devomar095@gmail.com
    WhatsApp +972592554320
--}}
@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.notification.index') }}">{{ t('Notifications') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Show Notification') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-8 offset-2">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{$notification->title}}</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="kt-section__body">
                            <div class="kt-portlet__body">
                                <p class="kt-font-brand">{{  $notification->content }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
