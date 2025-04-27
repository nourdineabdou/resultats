<div class="row">
    <div class="col-md-12">
        <form class="" action="{{ url('admin/droits/edit') }}" method="post">
            {{ csrf_field() }}
            <fieldset @if(!Auth::user()->hasAccess(1)) disabled @endif>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="libelle">{{ trans('Admin::admin.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input type="text" id="libelle" name="libelle" class="form-control" value="{{ $droit->libelle }}" required>
                    </div>
                    <div class="form-group col-md-6" id="ref_groupes_traitements">
                      <label for="sys_groupes_traitement_id">{{ trans('Admin::admin.groupe_traitements') }}<span class="required_field">*</span></label>
                      <select id="sys_groupes_traitement_id" name="sys_groupes_traitement_id" class="form-control selectpicker" title="Selectionner..." data-live-search="true">
                        @foreach ($sys_groupes_traitements as $ref_groupe_traitement)
                          <option value="{{ $ref_groupe_traitement->id}}" @if($droit->sys_groupes_traitement_id == $ref_groupe_traitement->id) selected="selected" @endif>{{ $ref_groupe_traitement->libelle}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="type_acces">{{ trans('Admin::admin.type_acces') }}<span class="required_field">*</span></label>
                      <select id="type_acces" name="type_acces" class="form-control">
                        <option value="0" @if($droit->type_acces == 0) selected="selected" @endif>{{ trans('Tous') }}</option>
                        <option value="1" @if($droit->type_acces == 1) selected="selected" @endif>{{ trans('Admin::admin.consultation') }}</option>
                        <option value="2" @if($droit->type_acces == 2) selected="selected" @endif>{{ trans('Admin::admin.enregistrement') }}</option>
                        <option value="3" @if($droit->type_acces == 3) selected="selected" @endif>{{ trans('Admin::admin.validation') }}</option>
                        <option value="4" @if($droit->type_acces == 4) selected="selected" @endif>{{ trans('Admin::admin.edition') }}</option>
                        <option value="5" @if($droit->type_acces == 5) selected="selected" @endif>{{ trans('Admin::admin.suppression') }}</option>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="ordre">{{ trans('Admin::admin.ordre') }}</label>
                      <input type="number" id="ordre" name="ordre" class="form-control" value="{{ $droit->ordre }}">                                   
                    </div>
                    <input type="hidden" value="{{ $droit->id }}" name="id">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-success btn-icon-split" onclick="saveform(this)" container="tab1">
                            <span class="icon text-white-50">
                                <i class="main-icon fas fa-save"></i>
                                <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                            </span>
                            <span class="text">{{ trans('text.enregistrer') }}</span>
                        </button>
                        <div id="form-errors" class="text-left mt-3"></div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>