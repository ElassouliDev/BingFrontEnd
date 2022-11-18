@extends('branch.layout.container')
@section('style')
    <link href="{{ asset('assets/css/demo6/pages/general/invoices/invoice-1.'.direction('.').'css') }}" rel="stylesheet"
          type="text/css"/>
    <style>
        .custome-class {
            align-items: center;
        }
    </style>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('branch.order.index') }}">{{ t('Orders') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Order Review') }}
        </li>
    @endpush

    <!-- begin:: Content -->
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-invoice-1">
                <div class="kt-invoice__wrapper">
                    <div class="kt-invoice__head" style="background: linear-gradient(to right,#db1515,#ec5252);">
                        <div class="kt-invoice__container kt-invoice__container--centered">
                            <div class="kt-invoice__logo mb-3" style="padding-top: 2rem;">
                                <a href="{{route('branch.restaurant.show', $order->branch_id)}}">
                                    <h1 class="mb-4">{{ t('Order Details') }}</h1>
                                    <span class="text-white"
                                          style="font-weight: 500">{{ t('Branch :').' '.optional($order->branch)->name }}</span>
                                    <br/>
                                </a>
                                <a>
                                    @if(optional($order->branch)->image)
                                        <img src="{{ asset(optional($order->branch)->image) }}" width="140px">
                                    @endif
                                </a>
                            </div>

                            <div class="kt-invoice__items"
                                 style="border-top: 1.5px solid #ffffff;padding: 2rem 0 2rem 0;">
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle">{{ t('Order') .' '. t('Date') }} :</span>
                                    <span class="kt-invoice__text text-white" style="font-weight: 500"
                                          dir="ltr">{{ $order->created_at }}</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle">{{ t('ORDER NO') }}</span>
                                    <span class="kt-invoice__text text-white" style="font-weight: 500 "
                                          dir="ltr"># {{ $order->uuid }}</span>
                                </div>
                                <div class="kt-invoice__item text-white" style="font-weight: 500">
                                    <span class="kt-invoice__subtitle">{{ t('ORDER TO') }}</span>
                                    <span class="kt-invoice__text text-white" style="font-weight: 500"><a
                                            class="text-white"
                                            href="{{route('branch.user.show', $order->user_id)}}">{{ $order->user->name .' , '. t($order->user->gender) }}</a>
                                            <br>
                                            <b dir="ltr">{{ $order->user->mobile }}</b>
                                            <br/>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-invoice__body kt-invoice__body--centered">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ t('Meals') }}</th>
                                    <th class="text-center">{{ t('Price') }}</th>
                                    <th class="text-center">{{ t('Addons') }}</th>
                                    <th class="text-center">{{ t('Addons Price') }}</th>
                                    <th class="text-center">{{ t('Quantity') }}</th>
                                    <th class="text-center">{{ t('amount') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($order->order_items as $item)
                                    <tr>
                                        <td>{{ $item->item->name }} - {{$item->item_price->name}}</td>
                                        <td class="text-center">{{ $item->price }}</td>
                                        <td class="text-center">
                                            @foreach($item->order_item_addons as $addons)
                                                <label>{{optional($addons->item_addon)->name}}</label><br/>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach($item->order_item_addons as $addons)
                                                <label>{{$addons->price}}</label><br/>
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-center">{{ $item->amount + $item->total_addon }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="4">{{t('No Meals')}}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <br>
                        <hr>
                        <hr>
                        <h1>{{t('Driver')}}</h1>
                        @if(isset($delivery))
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{ t('Name') }}</th>
                                        <th class="text-center">{{ t('status') }}</th>
                                        <th class="text-center">{{ t('distance') }}</th>
                                        <th class="text-center">{{ t('counter') }}</th>
                                        <th class="text-center">{{ t('time') }}</th>
                                        <th class="text-center">{{ t('note') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ optional($delivery->driver)->name }}</td>
                                        <td class="text-center">{{ $delivery->status_name }}</td>
                                        <td class="text-center">{{ $delivery->distance }}</td>
                                        <td class="text-center">{{ $delivery->counter }}</td>
                                        <td class="text-center">{{ $delivery->time }}</td>
                                        <td class="text-center">{{ $delivery->note }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            {{t('No Driver Accept this order')}}
                        @endif
                        @if($order->note)
                            <hr/>
                            <div class="kt-invoice__content mt-4">
                                <h5 class="kt-invoice__price">{{t('Note')}}:</h5>
                                <p>{{$order->note}}</p>
                            </div>
                        @endif
                        @if($order->rateed)
                            <hr/>
                            <div class="kt-invoice__content mt-4">
                                <h5 class="kt-invoice__price">{{t('User Rate')}}
                                    : {!! rating_d(optional($order->rate)->rate) !!}</h5>
                                <p>{{optional($order->rate)->comment}}</p>
                            </div>
                        @endif

                    </div>
                    <div class="kt-invoice__footer">
                        <div class="kt-invoice__container kt-invoice__container--centered">
                            <div class="kt-invoice__content">
                                <span>{{ t('Order details') }}</span>

                                <span>
                                    <span>{{ t('Pay Type') }}:</span>
                                    <span class="kt-invoice__price">{{ $order->paid_type_name  }}</span></span>
                                <span>
                                    <span>{{ t('Pick Up Time') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->pick_up_time }}</span></span>
                                <span>
                                    <span>{{ t('Total Cost') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->total_cost }}</span></span>
                                <span>
                                    <span>{{ t('Manager Commission Cost') }}:</span>
                                    <span class="kt-invoice__price"
                                          dir="ltr">{{ $order->manager_commission_cost }}</span>
                                </span>
                                <span>
                                    <span>{{ t('Tap Payment Gateway Cost') }}:</span>
                                    <span class="kt-invoice__price"
                                          dir="ltr">{{ $order->tap_payment_gateway_cost }}</span></span>
                                <span>
                                    <span>{{ t('branch Slice') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->branch_slice }}</span></span>
                                <span>
                                    <span>{{ t('Meals Cost') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->meals_cost }}</span></span>
                            </div>
                            <div class="kt-invoice__content" style="width:300px">
                                <div class="d-flex justify-content-between custome-class" style="">
                                    <span>{{ t('Status') }}</span>
                                    <span class="kt-invoice__price">{{ $order->status_name }}</span>
                                </div>
                                <div class="d-flex justify-content-between custome-class" style="">
                                    <span>{{ t('Delivery Cost') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->delivery_cost }}</span>
                                </div>

                                <div class="d-flex justify-content-between custome-class" style="">
                                    <span>{{ t('Commission Delivery Cost') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->commission_delivery_cost }}</span>
                                </div>

                                <div class="d-flex justify-content-between custome-class" style="">
                                    <span>{{ t('Driver Slice') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->driver_slice }}</span>
                                </div>

                                <div class="d-flex justify-content-between custome-class" style="">
                                    <span>{{ t('Tax Cost') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->tax_cost }}</span>
                                </div>

                                <div class="d-flex justify-content-between custome-class" style="">
                                    <span>{{ t('Commission Cost') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->commission_cost }}</span>
                                </div>

                                <div class="d-flex justify-content-between custome-class" style="">
                                    <span>{{ t('Coupon Discount') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->coupon_discount }}</span>
                                </div>

                                <div class="d-flex justify-content-between custome-class" style="">
                                    <span>{{ t('Discount') }}:</span>
                                    <span class="kt-invoice__price" dir="ltr">{{ $order->discount }}</span>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-invoice__actions row">
        <div class="kt-invoice__container  col-md-6">
            <button type="button" class="btn btn-danger btn-bold"
                    onclick="window.print();">{{ t('Print Order') }}</button>
        </div>
        @if(!$order->user_cancel && $order->status != \App\Models\Order::COMPLETED && $order->status != \App\Models\Order::READY)
            <div class="col-md-6">
                <form action="{{route('branch.order.changeStatus', $order->id)}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label class="col-3 col-form-label">{{t('Status')}}</label>
                        <div class="col-6">
                            <select class="form-control" name="status">
                                <option value="" disabled selected>{{t('Select Status')}}</option>
                                {{--                            <option value="1" {{$order->status == 1 ? 'selected':''}}>{{t('Pending')}}</option>--}}
                                @if($order->status == \App\Models\Order::PENDING)
                                    <option value="{{\App\Models\Order::ACCEPTED}}">{{t('Accepted')}}</option>
                                @endif
                                @if($order->status == \App\Models\Order::ACCEPTED)
                                    <option value="{{\App\Models\Order::ON_PROGRESS}}">{{t('On Progress')}}</option>
                                @endif
                                @if($order->status == \App\Models\Order::ON_PROGRESS)
                                    <option value="{{\App\Models\Order::READY}}">{{t('Ready')}}</option>
                                @endif
                                @if($order->status == \App\Models\Order::PENDING)
                                    <option value="{{\App\Models\Order::CANCELED}}">{{t('Canceled')}}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-danger">{{t('Change Status')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
        @if( $order->status == \App\Models\Order::READY)
            <div class="col-md-6">
                <form action="{{route('branch.order.sendNotification', $order->id)}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label class="col-3 col-form-label">{{t('Resend Notification ')}}</label>
                        <div class="col-6">
                            <select class="form-control" name="recipients">
                                <option value="" disabled selected>{{t('Select Type Driver')}}</option>
                                <option value="{{ALL_DRIVERS}}">{{t('All Drivers')}}</option>
                                <option value="{{DRIVERS_FOLLOWED_TO_RESTAURANT}}">{{t('Drivers Followed to Restaurant',[
    'restaurant' => optional($order->branch)->name
])}}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-danger">{{t('Send')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- end:: Content -->
@endsection
@section('script')

@endsection

