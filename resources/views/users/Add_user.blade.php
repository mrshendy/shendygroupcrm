@extends('layouts.master')
@section('title')
    {{ trans('main_trans.title') }}
@stop
@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ trans('user_management_trans.user_management') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="javascript: void(0);">{{ trans('user_management_trans.users') }}</a></li>
                        <li class="breadcrumb-item active">{{ trans('user_management_trans.user_management') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ trans('user_management_trans.user_management')}}</h5>
                    {{-- Message --}}
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            <strong>Success !</strong> {{ session('success') }}
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            <strong>Error !</strong> {{ session('error') }}
                        </div>
                    @endif

                </div>

<!-- row -->
<div class="row">


    <div class="col-lg-12 col-md-12">

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطا</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right">
                        <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}">رجوع</a>
                    </div>
                </div><br>
                <form class="parsley-style-1" id="selectForm2" autocomplete="off" name="selectForm2"
                    action="{{route('users.store','test')}}" method="post">
                    {{csrf_field()}}

                    <div class="">

                        <div class="row mg-b-20" style="margin: 10px;">
                            <div class="parsley-input col-md-4" id="fnWrapper">
                                <label>{{ trans('user_management_trans.user_name')}} <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm mg-b-20"
                                    data-parsley-class-handler="#lnWrapper" name="name" required="" type="text">
                            </div>

                            <div class="parsley-input col-md-4 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label>{{ trans('user_management_trans.email')}}  <span class="tx-danger">*</span></label>
                                <input class="form-control form-control-sm mg-b-20"
                                    data-parsley-class-handler="#lnWrapper" name="email" required="" type="email">
                            </div>
                            <div class="col-lg-4">
                            <label class="form-label">{{ trans('user_management_trans.Status')}} </label>
                            <select name="Status" id="select-beast" class="form-control  nice-select  custom-select">
                                <option value="Enabled">{{ trans('user_management_trans.Enabled')}}</option>
                                <option value="not_Enabled">{{ trans('user_management_trans.not_Enabled')}}</option>
                            </select>
                        </div>
                        </div>

                    </div>

                    <div class="row mg-b-20" style="margin: 10px;">
                         <div class="col-lg-4">
                            <label class="form-label">{{ trans('user_management_trans.employee')}} </label>
                            <select name="employee" id="select-beast" class="form-control  nice-select  custom-select">
                                <option value="" selected disabled> {{ trans('user_management_trans.employee')}}</option>
                                @foreach($employee as $x) 
                                   <option value="{{$x->id}}">{{$x->full_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="parsley-input col-md-4 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label> {{ trans('user_management_trans.password')}} <span class="tx-danger">*</span></label>
                            <input class="form-control form-control-sm mg-b-20" data-parsley-class-handler="#lnWrapper"
                                name="password" required="" type="password">
                        </div>

                        <div class="parsley-input col-md-4 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label>{{ trans('user_management_trans.confirm-password')}}  <span class="tx-danger">*</span></label>
                            <input class="form-control form-control-sm mg-b-20" data-parsley-class-handler="#lnWrapper"
                                name="confirm-password" required="" type="password">
                        </div>
                    </div>

                    <div class="row row-sm mg-b-20">
                        
                       
                    </div>

                    <div class="row mg-b-20" style="margin: 10px;"> 
                        <div class="col-xs-6 col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ trans('user_management_trans.role_list')}} </label>
                                {!! Form::select('roles_name[]', $roles,[], array('class' => 'form-control js-example-basic-multiple','multiple')) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">{{ trans('user_management_trans.default_account')}} </label>
                            <select name="id_account" id="select-beast" class="form-control  nice-select  custom-select" >
                                <option value="1" selected>{{ trans('user_management_trans.default_account')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center m-10" style="margin: 10px;">
                        <button class="btn btn-main-primary pd-x-20" type="submit">{{ trans('user_management_trans.confirm')}} </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
