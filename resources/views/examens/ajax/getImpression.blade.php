<div class="card">
    <div class="card-header">
        {{ trans('text_me.parametrer_votre_imp_listeEmergement') }}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card-body">
        <div class="form-check form-check-inline col-md-12 form-group" >
            <input type="checkbox" class="form-check-input" id="col" name="col"  onchange="getform('col')" checked>
            <label class="form-check-label" for="">{{ trans('text_me.allmatieres') }} </label>
        </div>
        <div class="form-check form-check-inline col-md-12 form-row" >
            <div class="form-group col-md-4">
                <input type="checkbox" class="form-check-input" id="ind" name="ind"  onchange="getform('ind')">
                <label class="form-check-label" for="">{{ trans('text_me.onematiere') }} </label>
            </div>
            <div id="divonmatier" class="col-md-8 form-group form-row p-0" style="display: none">
                <div class="col-md-4 form-group">
                    <label class="form-check-label" for="">{{ trans('text_me.matiere') }} </label>
                </div>
                <div class="col-md-8 form-group">
                <select id="matiers_profil" name="matiers_profil" class="form-control" onchange="activerDivInd()">
                </select>
                </div>
            </div>
        </div>
        <div class="text-right col-md-12">
            <hr>
            <div class="text-right">
                <form role="form"  id="formst" name="formst" class=""  method="get" >
                    <div id="divcol">
                        <button type="button"  class="d-none d-sm-inline-block btn btn-sm warning shadow-sm  text-red" onclick="imprimerListeEmergemet()">{{ trans('text_me.exporterpdf') }}</button>
                    </div>
                    <div id="divind" style="display: none">
                        <button type="button"   class="d-none d-sm-inline-block btn btn-sm warning shadow-sm  text-red" onclick="imprimerListeEmergemetInd()">{{ trans('text_me.exporterpdf') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
