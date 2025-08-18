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
                <h4 class="mb-sm-0">{{ trans('user_management_trans.role_management') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="javascript: void(0);">{{ trans('user_management_trans.role') }}</a></li>
                        <li class="breadcrumb-item active">{{ trans('user_management_trans.role_management') }}</li>
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
                    <h5 class="card-title mb-0">{{ trans('user_management_trans.role_create') }}</h5>
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

                <!-- row opened -->
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




                {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}
                <!-- row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mg-b-20">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <p>{{ trans('user_management_trans.role_name') }}</p>
                                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- col -->
                                    <div class="col-lg-12">
                                        <ul id="treeview1">
                                            <li><a href="#">{{ trans('user_management_trans.role_list') }}</a>
                                                <ul>
                                            </li>
                                            <div class="row" style="margin-top: 25px;">

                                                @foreach ($groupedpermission as $category => $permission)
                                                    <div class="col-md-3"  style="margin-top: 15px;">
                                                        <h4 style="margin-top: 15px;margin-bottom: 15px;">{{ $category }}</h4>
                                                        <div data-simplebar style="max-height: 215px;">
                                                            <div class="list-group">
                                                                @foreach ($permission as $value)
                                                                    <label class="list-group-item">
                                                                        {{ Form::checkbox('permission[]', $value->name, false, ['class' => 'name']) }}
                                                                        {{ $value->title }}
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            </li>

                                        </ul>
                                        </li>
                                        </ul>
                                    </div>
                                    <!-- /col -->
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button type="submit"
                                            class="btn btn-main-primary">{{ trans('user_management_trans.confirm') }}</button>
                                    </div>

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
        {!! Form::close() !!}
    @endsection
