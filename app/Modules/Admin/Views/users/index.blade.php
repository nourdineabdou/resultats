@extends('layout')
@section('page-title')
    {{ trans('Admin::admin.utilisateurs') }}
@endsection
@section('top-page-btn')
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="openFormAddInModal('admin/users')"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("Admin::admin.add_user")}}</a>
@endsection
@section('page-content')
    <div class="row">
        <div class="col-lg-12">
            @if (session('successmsg') || session('errormsg'))
                <div class="alert alert-{{(session('successmsg'))?'success':'danger'}} alert-dismissible">
                    {{ session('successmsg') }}{{ session('errormsg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatableshow" selected="" link="{{url("admin/users/getDT/1")}}" colonnes="name,email,actions" class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('Admin::admin.nom') }}</th>
                                    <th>{{ trans('Admin::admin.email') }}</th>
                                    <th width="160px">{{ trans('Admin::admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
