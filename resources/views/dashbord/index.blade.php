
@extends('layouts.master')
<link href="{{asset('assets/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" />

@section('title')
{{ trans('main_trans.title') }}

   
@stop


@section('content')


                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">{{ trans('dashbord_trans.dashboard') }}</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('dashbord_trans.dashboard') }}</a></li>
                                        <li class="breadcrumb-item active">{{ trans('dashbord_trans.dashboard') }}</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                      <!-- end col -->
                        <div class="col-xxl-12 order-xxl-0 order-first">
                            <div class="d-flex flex-column h-100">
                                <div class="row h-100">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle shadow fs-3">
                                                            <i class="mdi mdi-account-injury align-middle"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                                            {{ trans('dashbord_trans.total_patient') }}</p>
                                                        <h4 class=" mb-0"><span class="counter-value" data-target="500">0</span></h4>
                                                    </div>
                                                    <div class="flex-shrink-0 align-self-end">
                                                        <span class="badge badge-soft-success"><i class="ri-arrow-up-s-fill align-middle me-1"></i>6.24
                                                            %<span>
                                                            </span></span>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle shadow fs-3">
                                                            <i class="mdi mdi-human-male align-middle"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">{{ trans('dashbord_trans.total_male_patient') }}</p>
                                                        <h4 class=" mb-0"><span class="counter-value" data-target="300">0</span></h4>
                                                    </div>
                                                    <div class="flex-shrink-0 align-self-end">
                                                        <span class="badge badge-soft-success"><i class="ri-arrow-up-s-fill align-middle me-1"></i>3.67
                                                            %<span>
                                                            </span></span>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle shadow fs-3">
                                                            <i class="mdi mdi-human-female align-middle"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> {{ trans('dashbord_trans.total_female_patient') }}</p>
                                                        <h4 class=" mb-0"><span class="counter-value" data-target="200">0</span></h4>
                                                    </div>
                                                    <div class="flex-shrink-0 align-self-end">
                                                        <span class="badge badge-soft-danger"><i class="ri-arrow-down-s-fill align-middle me-1"></i>4.80
                                                            %<span>
                                                            </span></span>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-light text-primary rounded-circle shadow fs-3">
                                                            <i class="mdi mdi-human-capacity-decrease align-middle"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">{{ trans('dashbord_trans.total_employees') }}</p>
                                                        <h4 class=" mb-0"><span class="counter-value" data-target="10">0</span></h4>
                                                    </div>
                                                    <div class="flex-shrink-0 align-self-end">
                                                            </span></span>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!-- end row -->
                    
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{ trans('dashbord_trans.total_visits_today') }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="text-success fs-14 mb-0">
                                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 %
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="20">0</span> </h4>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-success rounded fs-3">
                                                <i class="mdi mdi-human-queue"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{ trans('dashbord_trans.total_examination_today') }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="text-danger fs-14 mb-0">
                                                <i class="ri-arrow-right-down-line fs-13 align-middle"></i> -3.57 %
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="15">0</span></h4>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info rounded fs-3">
                                                <i class="mdi mdi-human-baby-changing-table"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">{{ trans('dashbord_trans.total_follow_up_today') }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="text-success fs-14 mb-0">
                                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +29.08 %
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="5">0</span> </h4>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-warning rounded fs-3">
                                                <i class="mdi mdi-history"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <!-- card -->
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> {{ trans('dashbord_trans.total_cash_today') }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h5 class="text-muted fs-14 mb-0">
                                                +0.00 %
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span class="counter-value" data-target="1">0</span>k </h4>
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-danger rounded fs-3">
                                                <i class="bx bx-wallet"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div> <!-- end row-->
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1"> {{ trans('dashbord_trans.financial_analysis') }}</h4>
                                    <div>
                                        <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                                            ALL
                                        </button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                                            1M
                                        </button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                                            6M
                                        </button>
                                        <button type="button" class="btn btn-soft-primary btn-sm shadow-none">
                                            1Y
                                        </button>
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-header p-0 border-0 bg-soft-light">
                                    <div class="row g-0 text-center">
                                        <div class="col-6 col-sm-4">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1"><span class="counter-value" data-target="7585">0</span></h5>
                                                <p class="text-muted mb-0">{{ trans('dashbord_trans.number_reservations') }}</p>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-6 col-sm-4">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1">$<span class="counter-value" data-target="22.89">0</span>k</h5>
                                                <p class="text-muted mb-0">{{ trans('dashbord_trans.total_income') }}</p>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-6 col-sm-4">
                                            <div class="p-3 border border-dashed border-start-0">
                                                <h5 class="mb-1">$<span class="counter-value" data-target="12.89">0</span>k</h5>
                                                <p class="text-muted mb-0">{{ trans('dashbord_trans.total_cost') }}</p>
                                            </div>
                                        </div>
                                        <!--end col-->
                                       
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-body p-0 pb-2">
                                    <div class="w-100">
                                        <div id="customer_impression_charts" data-colors='["--vz-success", "--vz-primary", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                        <div class="col-xl-3">
                            <div class="card card-height-100">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">{{ trans('dashbord_trans.patients_by_region') }}</h4>
                                   
                                </div><!-- end card header -->

                                <div class="card-body">
                                    <div id="store-visits-source" data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]' class="apex-charts" dir="ltr"></div>
                                </div>
                            </div> <!-- .card-->
                        </div> <!-- .col-->
                         <div class="col-xxl-3">
                            <div class="card card-height-100">
                                <div class="card-header border-0 align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">{{ trans('dashbord_trans.top_diseases') }}</h4>
                                    <div>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-primary btn-sm shadow-none" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-uppercase">diseases<i class="mdi mdi-chevron-down align-middle ms-1"></i></span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">diseases</a>
                                                <a class="dropdown-item" href="#">diseases</a>
                                                <a class="dropdown-item" href="#">diseases</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end cardheader -->
                                <div class="card-body">
                                    <div id="portfolio_donut_charts" data-colors='["--vz-primary", "--vz-info", "--vz-warning", "--vz-success"]' class="apex-charts" dir="ltr"></div>

                                    <ul class="list-group list-group-flush border-dashed mb-0 mt-3 pt-2">
                                        <li class="list-group-item px-0">
                                            <div class="d-flex">
                                                
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-primary me-1"></i>diseases 1 </p>
                                                </div>
                                                <div class="flex-shrink-0 text-end">
                                                    <p class="text-success fs-12 mb-0">19,405.12</p>
                                                </div>
                                            </div>
                                        </li><!-- end -->
                                        <li class="list-group-item px-0">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-info me-1"></i>diseases 2
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0 text-end">
                                                    <p class="text-danger fs-12 mb-0">40552.18</p>
                                                </div>
                                            </div>
                                        </li><!-- end -->
                                        <li class="list-group-item px-0">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-warning me-1"></i> diseases 3
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0 text-end">
                                                    <p class="text-success fs-12 mb-0">15824.58</p>
                                                </div>
                                            </div>
                                        </li><!-- end -->
                                        <li class="list-group-item px-0 pb-0">
                                            <div class="d-flex">
                                                <div class="flex-grow-1 ms-2">
                                                    <p class="fs-12 mb-0 text-muted"><i class="mdi mdi-circle fs-10 align-middle text-success me-1"></i>diseases 4
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0 text-end">
                                                    <p class="text-success fs-12 mb-0">30635.84</p>
                                                </div>
                                            </div>
                                        </li><!-- end -->
                                    </ul><!-- end -->
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div>
                      
                    </div>



@endsection
