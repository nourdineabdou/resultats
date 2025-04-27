<div class="modal-header">
    <h4 class="modal-title">Ajouter un profil à l'utilisateur {{$user->name}} </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12" id="addProfileToUser">
                    <form class="" action="{{ url('admin/users/addProfileToUser') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="profile">Profile <span class="required_field">*</span>:</label>
                                <select id="profile" name="profile" class="form-control selectpicker" title="Selectionner..." required>
                                    @foreach ($profiles as $profile)
                                        <option value="{{ $profile->id}}" >{{ $profile->libelle}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12 agences-container d-none">
                                <label for="commune">Commune <span class="required_field">*</span>:</label>
                                <select id="commune" name="communes[]" data-live-search="true" multiple="multiple" class="form-control selectpicker" title="Selectionner...">
                                    @foreach ($communes as $commune)
                                        <option value="{{ $commune->id}}" @if($commune->id == env('APP_COMMUNE')) selected="selected" @endif >{{ $commune->libelle}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="agence" value="0">
                        <input type="hidden" value="{{ $user->id }}" name="id">
                        <div class="col-md-12 text-right">
                            <a href="#!" class="btn btn-success btn-icon-split" onclick="saveProfileUser({{ $user->id }}, this)" container="addProfileToUser">
                                <span class="icon text-white-50">
                                    <i class="main-icon fas fa-save"></i>
                                    <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                    <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                </span>
                                <span class="text">{{ trans('text.enregistrer') }}</span>
                            </a>
                            <div id="form-errors" class="text-left mt-3"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
