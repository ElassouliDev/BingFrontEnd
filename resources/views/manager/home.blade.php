@extends('manager.layout.container')
@section('style')
    <style>
        #chartdiv1,#chartdiv2,#chartdiv3,#chartdiv0 {
            width: 100%;
            height: 400px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="kt-portlet">
            <div class="kt-portlet__body  kt-portlet__body--fit">
                <div class="row row-no-padding row-col-separator-lg">
                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::Total Profit-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Orders Amount') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Orders Amount') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-dark">
					        {{ $total_orders }}
					    </span>
                            </div>

                        </div>
                        <!--end::Total Profit-->
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Feedbacks-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Payments Amount') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Payments Amount') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-danger">
					       {{ $total_payments }}
					    </span>
                            </div>
                        </div>
                        <!--end::New Feedbacks-->
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Orders-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Orders') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Orders') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-dark">
					        {{ $orders}}
					    </span>
                            </div>
                        </div>
                        <!--end::New Orders-->
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Orders-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Pending Orders') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Pending Orders') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-danger">
					        {{ $new_orders}}
					    </span>
                            </div>
                        </div>
                        <!--end::New Orders-->
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="kt-portlet">
            <div class="kt-portlet__body  kt-portlet__body--fit">
                <div class="row row-no-padding row-col-separator-lg">

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::Total Profit-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Accepted Orders') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Accepted Orders') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-dark">
					        {{ $prepare_orders }}
					    </span>
                            </div>

                        </div>
                        <!--end::Total Profit-->
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Feedbacks-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Ready orders') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Ready orders') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-danger">
					       {{ $delivery_orders }}
					    </span>
                            </div>
                        </div>
                        <!--end::New Feedbacks-->
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Orders-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Complete Orders') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Complete Orders') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-dark">
					        {{ $complete_orders}}
					    </span>
                            </div>
                        </div>
                        <!--end::New Orders-->
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Orders-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Cancel Orders') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Cancel Orders') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-danger">
					        {{ $cancel_orders}}
					    </span>
                            </div>
                        </div>
                        <!--end::New Orders-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="kt-portlet">
            <div class="kt-portlet__body  kt-portlet__body--fit">
                <div class="row row-no-padding row-col-separator-lg">

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Orders-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Branch Meals') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Branch Meals') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-dark">
					        {{ $branches_items}}
					    </span>
                            </div>
                        </div>
                        <!--end::New Orders-->
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Orders-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Restaurants') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Restaurants') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-danger">
					        {{ $restaurants}}
					    </span>
                            </div>
                        </div>
                        <!--end::New Orders-->
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Orders-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Branchs') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Branchs') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-dark">
					        {{ $branches}}
					    </span>
                            </div>
                        </div>
                        <!--end::New Orders-->
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-3">
                        <!--begin::New Orders-->
                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        {{ t('Clients') }}
                                    </h4>
                                    <span class="kt-widget24__desc">
					            {{ t('Total Clients') }}
					        </span>
                                </div>

                                <span class="kt-widget24__stats kt-font-danger">
					        {{ $users}}
					    </span>
                            </div>
                        </div>
                        <!--end::New Orders-->
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ t('Total orders during the current month') }}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fluid">
                    <div id="chartdiv0">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @if(app()->getLocale() == 'ar')
        <script src="{{asset('core_ar.js')}}"></script>
    @else
        <script src="{{asset('core.js')}}"></script>
    @endif
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/material.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://www.amcharts.com/lib/4/lang/ar.js"></script>
    <script>
        am4core.ready(function() {

// Themes begin
            am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
            var chart = am4core.create("chartdiv0", am4charts.XYChart);
            @if(isRtlJS())
                chart.rtl = true;
            @endif
            // Add data
            {{--chart.data = [--}}
            {{--        @foreach($orders_date as $order)--}}
            {{--    {--}}
            {{--        "date": "{{ $order->date }}",--}}
            {{--        "value": {{ $order->counts }}--}}
            {{--    },--}}
            {{--    @endforeach--}}
            {{--];--}}

// Set input format for the dates
            chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

// Create axes
            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = "value";
            series.dataFields.dateX = "date";
            series.tooltipText = "{value}"
            series.strokeWidth = 2;
            series.minBulletDistance = 15;

// Drop-shaped tooltips
            series.tooltip.background.cornerRadius = 20;
            series.tooltip.background.strokeOpacity = 0;
            series.tooltip.pointerOrientation = "vertical";
            series.tooltip.label.minWidth = 40;
            series.tooltip.label.minHeight = 40;
            series.tooltip.label.textAlign = "middle";
            series.tooltip.label.textValign = "middle";

// Make bullets grow on hover
            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.strokeWidth = 2;
            bullet.circle.radius = 4;
            bullet.circle.fill = am4core.color("#fff");

            var bullethover = bullet.states.create("hover");
            bullethover.properties.scale = 1.3;

// Make a panning cursor
            chart.cursor = new am4charts.XYCursor();
            chart.cursor.behavior = "panXY";
            chart.cursor.xAxis = dateAxis;
            chart.cursor.snapToSeries = series;

// Create vertical scrollbar and place it before the value axis
            chart.scrollbarY = new am4core.Scrollbar();
            chart.scrollbarY.parent = chart.leftAxesContainer;
            chart.scrollbarY.toBack();

// Create a horizontal scrollbar with previe and place it underneath the date axis
            chart.scrollbarX = new am4charts.XYChartScrollbar();
            chart.scrollbarX.series.push(series);
            chart.scrollbarX.parent = chart.bottomAxesContainer;

            chart.events.on("ready", function () {
                //dateAxis.zoom({start:0.79, end:1});
            });

        }); // end am4core.ready()
    </script>
@endsection
