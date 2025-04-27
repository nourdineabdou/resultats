<div class="modal-header">
    <h5 class="modal-title">{{ trans('Admin::admin.new_user') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="row">
          <div class="col-md-12" id="addForm">
              <form class="" action="{{ url('admin/users/add') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-row">

                        <div class="col-md-6 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">Nom</label>

                            <div class="">
                                <input id="name" type="text" class="form-control" name="name" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">Email</label>

                            <div class="">
                                <input id="email" type="email" class="form-control" name="email" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="username" class="control-label">Login</label>
                            <div class="">
                                <input id="username" type="text" class="form-control" name="username" required>
                            </div>
                        </div>

                        <div class="col-md-6 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Mot de passe</label>

                            <div class="">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="password-confirm" class="control-label">Confirmer le mot de passe</label>
                            <div class="">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="password-confirm" class="control-label">Deparetement</label>
                            <div class="">
                               <select class="form-control" id="departement" name="departement">
                                   <option value=""></option>
                                   @foreach($departements as $departement)
                                       <option value="{{$departement->id}}">{{$departement->libelle}}</option>
                                   @endforeach
                               </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-right">
                                <button class="btn btn-success btn-icon-split" onclick="addObject(this,'admin/users')" container="addForm">
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
