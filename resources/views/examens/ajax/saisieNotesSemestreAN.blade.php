 <div class="card" id="addForm">
    <div class="card-header">
        {{ $niveau->libelle }}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="card-body">
        <form class=""  action="{{url("examens/saisirnoteTarakm")}}" method="post">
        {{ csrf_field() }}
        {!! $html !!}
            <div class=" " >
                <button class="btn btn-success btn-icon-split mb-3" id="btn1" onclick="affecterNotesEtudiansNote(this)" container="addForm" >
                            <span class="icon text-white-50">
                                <i class="main-icon fas fa-save"></i>
                                <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                            </span>
                    <span class="text">{{ trans('text.enregistrer') }}</span>
                </button>
                <div id="form-errors" class="text-left"></div>

            </div>
        </form>
    </div>
</div>
