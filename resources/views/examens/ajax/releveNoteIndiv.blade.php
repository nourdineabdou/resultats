 <div class="card" id="addForm">
    <div class="card-header">
        {{ trans('text_me.releveNoteIndiv') }}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="card-body">
        <form role="form"  id="formst1RL" name="formst1RL" class=""  method="get" >

        <div class="form-row">
            <div class="col-md-3 form-group">
                <label for="profil">{{ trans('text_me.nodos') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                <input name="nodos" id="nodos" class=" form-control" />
            </div>
            <div class="col-md-3 form-group">
                <label for="">{{ trans('text_me.annee') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                <select name="annee_id" id="annee_id" class=" form-control">
                   <option value=""></option>
                    @foreach($annees as $annee)
                        <option value="{{$annee->id}}">{{$annee->annee}}</option>
                    @endforeach
                </select>
            </div> <div class="col-md-3 form-group">
                <label for="">{{ trans('text_me.semestre') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                <select name="semestrein" id="semestrein" class=" form-control">
                    <option value=""></option>
                    @foreach($semestres as $semestre)
                        <option value="{{$semestre->id}}">{{$semestre->libelle}}</option>
                    @endforeach
                </select>
            </div>

        </div>
            <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getBultinImpressionIndiRel()">{{ trans('text_me.impresion_bultion_notes') }}</button>
        </form>
    </div>
</div>
