@extends('layouts.master')
@section('css')
<!-- Internal Nice-select css  -->
<link href="{{URL::asset('assets/plugins/jquery-nice-select/css/nice-select.css')}}" rel="stylesheet" />
@section('title')
تعديل مستخدم - ايجي سوفت
@stop


@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
                مستخدم</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
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
                {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
                <div class="">
                    <div class="row mg-b-20">
                        <div class="parsley-input col-md-6" id="fnWrapper">
                            <label>{{ trans('user_management_trans.user_name')}} <span class="tx-danger">*</span></label>
                            {!! Form::text('name', null, array('class' => 'form-control','required')) !!}
                        </div>

                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                                <label>{{ trans('user_management_trans.email')}}  <span class="tx-danger">*</span></label>
                            {!! Form::text('email', null, array('class' => 'form-control','required')) !!}
                        </div>
                    </div>
                </div>
                <div class="row mg-b-20">
                    <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                        <label> {{ trans('user_management_trans.password')}} <span class="tx-danger">*</span></label>
                        {!! Form::password('password', array('class' => 'form-control')) !!}
                    </div>

                    <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label>{{ trans('user_management_trans.confirm-password')}}  <span class="tx-danger">*</span></label>
                        {!! Form::password('confirm-password', array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="row row-sm mg-b-20">
                    <div class="col-lg-6">
                        <label class="form-label">{{ trans('user_management_trans.Status')}} </label>
                        <select name="Status" id="select-beast" class="form-control  nice-select  custom-select">
                            <option value="{{ $user->Status}}"></option>
                             <option @if($user->Status=="Enabled") selected @endif value="Enabled">{{ trans('user_management_trans.Enabled')}}</option>
                             <option @if($user->Status=="not_Enabled") selected @endif value="not_Enabled">{{ trans('user_management_trans.not_Enabled')}}</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">{{ trans('user_management_trans.employee')}}</label>
                        <select name="job" id="select-beast" class="form-control  nice-select  custom-select">
                            <option value="" selected disabled>{{ trans('user_management_trans.employee')}}</option>
                            @foreach($employee as $x) 
                               <option @if($user->employee==$x->id) selected @endif  value="{{$x->id}}" @if($user->employee==$x->name)selected @endif>{{$x->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mg-b-20">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                             <label class="form-label">{{ trans('user_management_trans.role_list')}} </label>
                            {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control js-example-basic-multiple','multiple'))
                            !!}
                        </div>
                    </div>
                </div>
                <div class="mg-t-30">
                    <button class="btn btn-main-primary pd-x-20" type="submit">تحديث</button>
                </div>
                {!! Form::close() !!}
            </div>
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
@section('js')

<!-- Internal Nice-select js-->
<script src="{{URL::asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery-nice-select/js/nice-select.js')}}"></script>

<!--Internal  Parsley.min js -->
<script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
<!-- Internal Form-validation js -->
<script src="{{URL::asset('assets/js/form-validation.js')}}"></script>
@endsection