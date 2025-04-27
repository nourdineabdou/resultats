<div class="modal-header">
    <h5 class="modal-title">{{ $title_modif }} : {{ $listes->libelle }} </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="row">
          <div class="col-md-12" id="addForm">
              <form class="" action="{{ url('ref/update_ref') }}" method="post">
		          {!! csrf_field() !!}
              <div class="form-group ">
                    <label for="libelle">{{ trans('text.libelle') }} <span class="required_field">*</span></label>
                    <input id="libelle" name="libelle" value="{{$listes->libelle}}" class="form-control">
              </div>
                  <div class="form-group ">
                    <label for="libelle_ar">{{ trans('text.libelle_ar') }} <span class="required_field">*</span></label>
                    <input id="libelle_ar" name="libelle_ar" value="{{$listes->libelle_ar}}" class="form-control">
    			    </div>
              <div class="form-group ">
    							  <label for="ordre">{{ trans('text.ordre') }} <span class="required_field">*</span></label>
                    <input id="ordre" name="ordre" value="{{$listes->ordre}}" class="form-control">
    					      
                    <input type="hidden" name="model" value="{{ $model }}">
					    </div>
              <div class="text-left">
                    <span class="required_field">*</span>: {{ trans('text.champ_obligatoire') }}
              </div>
              <div class="text-right">
                  <input type="hidden" name="id" value="{{ $listes->id }}">

                    <button class="btn btn-success btn-icon-split" onclick='saveform(this)' container="addForm">
                        <span class="icon text-white-50">
                            <i class="main-icon fas fa-save"></i>
                            <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                            <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                        </span>
                        <span class="text">{{ trans('text.enregistrer') }}</span>
                    </button>
                    <div id="form-errors" class="text-left"></div>
              </div>

    					<!-- <button  class="btn btn-success " >{{ trans('text_my.modifier') }}</button> -->

            </form>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
