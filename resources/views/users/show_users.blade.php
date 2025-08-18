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

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                      @can('users-create')
                            <a style="margin: 10px;" class="btn btn-outline-success waves-effect waves-light shadow-none" href="{{ route('users.create') }}">{{ trans('user_management_trans.user_create')}}</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive hoverable-table">
                        <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th class="wd-10p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">{{ trans('user_management_trans.user_name')}} </th>
                                    <th class="wd-20p border-bottom-0">{{ trans('user_management_trans.email')}} </th>
                                    <th class="wd-15p border-bottom-0">{{ trans('user_management_trans.employee')}} </th>
                                    <th class="wd-15p border-bottom-0">{{ trans('user_management_trans.Status')}} </th>
                                    <th class="wd-15p border-bottom-0">{{ trans('user_management_trans.role_list')}}</th>
                                    <th class="wd-10p border-bottom-0">{{ trans('user_management_trans.oprtion')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $user)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->employes->name??null }}</td>

                                        <td>
                                            @if ($user->Status == 'Enabled')
                                                <span class="label text-success d-flex">
                                                    <div class="dot-label bg-success ml-1"></div>{{ trans('user_management_trans.Enabled')}}
                                                </span>
                                            @else
                                                <span class="label text-danger d-flex">
                                                    <div class="dot-label bg-danger ml-1"></div>{{ trans('user_management_trans.not_Enabled')}}
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            @if (!empty($user->getRoleNames()))
                                                @foreach ($user->getRoleNames() as $v)
                                                    <label class="label text-success d-flex">{{ $v }}</label>
                                                @endforeach
                                            @endif
                                        </td>

                                        <td>
                                            @can('users-edit')
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn  btn-info"
                                                    title="تعديل"><i class="bx bx-edit-alt"></i></a>
                                            @endcan

                                             @can('users-delete')
                                                <a class="modal-effect  btn btn-danger" data-bs-toggle="modal" data-bs-target="#signupModal{{$user->id}}" title="حذف"><i class="bx bx-trash-alt"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                    <div id="signupModal{{$user->id}}" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 overflow-hidden">
                                                    <div class="modal-header p-3">
                                                        <h4 class="card-title mb-0">{{ trans('settings_trans.massagedelete_area') }}</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center p-5">
                                                        <lord-icon src="{{URL::asset('assets/images/icon/tdrtiskw.json')}}" trigger="loop" colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px">
                                                        </lord-icon>
                                                        <div class="mt-4 pt-4">
                                                            <h4>{{ trans('settings_trans.massagedelete_d') }}!</h4>
                                                            <p class="text-muted">   {{ trans('settings_trans.massagedelete_p') }}{{$user->name}}</p>
                                                            <!-- Toogle to second dialog -->
                                                        <form action="{{ route('users.destroy', 'test') }}" method="post">
                                                                {{ method_field('delete') }}
                                                                {{ csrf_field() }}
                                                             <input  class="form-control" id="id" name="id" value="{{$user->id}}" type="hidden">
                                                            <button class="btn btn-warning" data-bs-target="#secondmodal" data-bs-toggle="modal" data-bs-dismiss="modal">
                                                                {{ trans('user_management_trans.massagedelete_user') }}
                                                            </button>
                                                           </form>
                                                        </div>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
    <!-- /row -->
    </div>
    <!-- Container closed -->
    </div>

@endsection
