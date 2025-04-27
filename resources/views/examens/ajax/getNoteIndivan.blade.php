 <div class="card" id="addForm">
    <div class="card-header">
        <button type="button" class="close text-left" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ trans('text_me.saisies_individuel_notesan') }}

    </div>
     <fieldset  @if($adm !=1) disabled @endif >
         <form class=""  action="{{url("examens/saisirnoteIndivan")}}" method="post">
             {{ csrf_field() }}
    <div class="card-body">
        <div class="form-row">
            {!! $html !!}
        </div>
      {{--<input type="submit">--}}
        <div class="text-right " >
            <button class="btn btn-success btn-icon-split mb-3" id="btn1" onclick="affecterNotesEtudians(this)" container="addForm" >
                            <span class="icon text-white-50">
                                <i class="main-icon fas fa-save"></i>
                                <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                            </span>
                <span class="text">{{ trans('text.enregistrer') }}</span>
            </button>
            <div id="form-errors" class="text-left"></div>

        </div>
    </div>
         </form>
     </fieldset>
</div>
