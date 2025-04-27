 <div class="card" id="addForm">
    <div class="card-header">
        {{ trans('text_me.liste_note') }}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="card-body">
        <div class="form-row">
            <div class="col-md-12 form-group">
                <label for="profil">{{ trans('text_me.matieres') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                <select name="matiere_id" id="matiere_id" class="selectpicker form-control" onchange="getNoEtudiants()">
                    <option value=""></option>
                    @foreach($matieres as $matiere)
                        <option value="{{$matiere->id}}">{{$matiere->id}} -- {{$matiere->libelle}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12" id="getEtudiants" >

	        </div>
        </div>
    </div>
</div>
