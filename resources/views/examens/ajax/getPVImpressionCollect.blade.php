<div class="card">
    <div class="card-header">
        {{ trans('text_me.imp_listeCollect') }}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card-body">
        <div class="form-check form-check-inline col-md-12 form-group" >
            <input type="checkbox" class="form-check-input" id="matiere1" name="matiere"  onchange="changeClick()" checked>
            <label class="form-check-label" for="">{{ trans('text_me.pvMatiere') }} </label>
        </div>
        <div class="form-check form-check-inline col-md-12 form-group">
            <input type="checkbox" class="form-check-input" id="module" name="module" onchange="changeClick1()"  >
            <label class="form-check-label" for="">{{ trans('text_me.pvModule') }} </label>
        </div>
        <div class="form-check form-check-inline col-md-12 form-group" >
            <input type="checkbox" class="form-check-input" id="semestre1" name="semestre"  onchange="changeClick2()" >
            <label class="form-check-label" for="">{{ trans('text_me.pvSemestre') }} </label>
        </div>
        <div class="text-right col-md-12">
            <hr>
            <div class="text-right">
                <form role="form"  id="formst" name="formst" class=""  method="get" >
                    <div id="divcol">
                        <button type="button"  class="d-none d-sm-inline-block btn btn-sm warning shadow-sm  text-red" onclick="imprimerPVNotes()">{{ trans('text_me.exporterpdf') }}</button>
                    </div>

                </form>
                <table border="1">
                    <tr>
                        <th style="-webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    transform: rotate(90deg);">First</th>
                        <th class="verticalTableHeader">Second</th>
                        <th class="verticalTableHeader">Third</th>
                    </tr>
                    <tr>
                        <td>foo</td>
                        <td>foo</td>
                        <td>foo</td>
                    </tr>
                    <tr>
                        <td>foo</td>
                        <td>foo</td>
                        <td>foo</td>
                    </tr>
                    <tr>
                        <td>foo</td>
                        <td>foo</td>
                        <td>foo</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
