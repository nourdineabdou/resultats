<div class="row">
    <div class="col-md-12">
        <form class="" action="{{ url('admin/profiles/edit') }}" method="post">
            {{ csrf_field() }}
            <fieldset @if(!Auth::user()->hasAccess(1)) disabled @endif>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="libelle">{{ trans('Admin::admin.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input type="text" id="libelle" name="libelle" class="form-control" value="{{ $profile->libelle }}" required>
                        {!! $errors->first('profile', '<small class="help-block">:message</small>') !!}
                    </div>
                    <input type="hidden" value="{{ $profile->id }}" name="id">
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