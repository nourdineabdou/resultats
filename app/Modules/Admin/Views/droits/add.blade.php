<div class="modal-header">
    <h5 class="modal-title">{{ trans('Admin::admin.new_droit') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="row">
          <div class="col-md-12" id="addForm">
              <form class="" action="{{ url('admin/droits/add') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-12 form-group">
                            <label for="libelle" class="control-label">{{ trans('Admin::admin.libelle') }}</label>
                            <div class="">
                                <input id="libelle" type="text" class="form-control" name="libelle" required autofocus>
                            </div>
                        </div>
                        <div class="form-group col-md-6" id="ref_groupes_traitements">
                          <label for="sys_groupes_traitement_id">{{ trans('Admin::admin.groupe_traitements') }}<span class="required_field">*</span></label>
                          <select id="sys_groupes_traitement_id" name="sys_groupes_traitement_id" class="form-control selectpicker" title="Selectionner..." data-live-search="true">
                            @foreach ($sys_groupes_traitements as $ref_groupe_traitement)
                              <option value="{{ $ref_groupe_traitement->id}}">{{ $ref_groupe_traitement->libelle}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="type_acces">{{ trans('Admin::admin.type_acces') }}<span class="required_field">*</span></label>
                          <select id="type_acces" name="type_acces" class="form-control">
                            <option value="0">{{ trans('Tous') }}</option>
                            <option value="1">{{ trans('Admin::admin.consultation') }}</option>
                            <option value="2">{{ trans('Admin::admin.enregistrement') }}</option>
                            <option value="3">{{ trans('Admin::admin.validation') }}</option>
                            <option value="4">{{ trans('Admin::admin.edition') }}</option>
                            <option value="5">{{ trans('Admin::admin.suppression') }}</option>
                          </select>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="ordre">{{ trans('Admin::admin.ordre') }}</label>
                          <input type="number" id="ordre" name="ordre" class="form-control" value="{{ $last_order }}">                                   
                        </div>
                        <div class="col-md-12">
                            <div class="text-right">
                                <button class="btn btn-success btn-icon-split" onclick="addObject(this,'admin/droits')" container="addForm">
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
              </form>
          </div>
      </div>
  </div>