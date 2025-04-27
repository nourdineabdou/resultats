<div class="row">
    <div class="col-md-12">
        <form class="" action="{{ url('admin/users/edit') }}" method="post">
            {{ csrf_field() }}
            <fieldset @if(!Auth::user()->hasAccess(1)) disabled @endif>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">{{ trans('Admin::admin.nom') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">{{ trans('Admin::admin.email') }}</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="username">{{ trans('Admin::admin.pseudo') }}</label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ $user->username }}" required>
                    </div>
                    <input type="hidden" value="{{ $user->id }}" name="id">
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
