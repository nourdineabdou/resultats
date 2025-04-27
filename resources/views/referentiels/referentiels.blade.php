@extends('layout')
@section('page-title')
    {{ $title }}
@endsection
@section('top-page-btn')
    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addNewModal"><i class="fa fa-plus"></i> {{ $title_pop_up }} </button>
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
                        <table id="datatableshow" link="{{url("ref/getRefsDT/$model/$selected")}}" colonnes="id,libelle,libelle_ar,actions" class="table table-hover table-bordered">
                            <thead>
                              <tr>
                                <th>  </th>
                                <th>{{ trans('text.libelle') }}</th>
                                <th>{{ trans('text.libelle_ar') }}</th>
                                <th style="width:100px;">{{ trans('text.actions') }}</th>
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

<div id="addNewModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ $title_pop_up }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <form class="" action="{{ url('add_ref') }}" method="post">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="libelle">{{ trans('text.libelle') }}   <span class="required_field">*</span></label>
                <input type="text" id="libelle" name="libelle" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="libelle_ar">{{ trans('text.libelle_ar') }}   <span class="required_field">*</span></label>
                <input type="text" id="libelle_ar" name="libelle_ar" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="ordre">{{ trans('text.ordre') }}  <span class="required_field">*</span></label>
                <input type="text" id="ordre" value="{{$lastorder}}" name="ordre" class="form-control" required>
              </div>
              <input type="hidden" name="model" value="{{ $model }}">
                  <!-- <button  class="btn btn-success " >{{ trans('text_my.modifier') }}</button> -->

            </form>

            <div class="col-md-12">
                <div class="text-left">
                    <span class="required_field">*</span>: {{ trans('text.champ_obligatoire') }}
                </div>
                <div class="text-right">
                    <button class="btn btn-success btn-icon-split" onclick='addnew()' container="addForm">
                        <span class="icon text-white-50">
                            <i class="main-icon fas fa-save"></i>
                            <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                            <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                        </span>
                        <span class="text">{{ trans('text.ajouter') }}</span>
                    </button>
                    <div id="form-errors" class="text-left"></div>

                </div>
            </div>

          </div>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="ref-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <!-- <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;
          </span></button>
          <h4 class="modal-title"><span class="title_modif"></span> :  <span class="text-primary libelleref"></span></h4>
        </div> -->
        <div class="modal-header">
          <h5 class="modal-title"><span class="title_modif"></span> :  <span class="text-primary libelleref"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
            <div class="clearfix"></div>
              <div id="form-errors"></div>
            </div>
          </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal-->
@endsection

