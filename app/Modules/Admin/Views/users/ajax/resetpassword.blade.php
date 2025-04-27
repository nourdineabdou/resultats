<div class="modal-header">
    <h4 class="modal-title">Réinitialiser le mot de passe pour {{ $user->name }} </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12" id="ressetPassword">
                    <form class="" action="{{ url('admin/users/resetpassword') }}" method="post">
                        {{ csrf_field() }}
                        <fieldset>      
                            <div class="form-group">
                                <label for="password" class="control-label">Mot de passe <span class="required_field">*</span></label>
                                <input id="password" type="password" class="form-control {!! $errors->has('password') ? 'has-error' : '' !!}" name="password" required>
                                {!! $errors->first('password', '<small class="help-block">:message</small>') !!}
                            </div>
                            <div class="form-group">
                                <label for="password-confirm" class="control-label">Confirmer mot de passe <span class="required_field">*</span></label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                            <input type="submit" name="">
                            <input type="hidden" value="{{ $user->id }}" name="id">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-success btn-icon-split" onclick="saveform(this)" container="ressetPassword">
                                    <span class="icon text-white-50">
                                        <i class="main-icon fas fa-save"></i>
                                        <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                        <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                    </span>
                                    <span class="text">{{ trans('text.enregistrer') }}</span>
                                </button>
                                <div id="form-errors" class="text-left mt-3"></div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>