<div class="card" id="addForm">
    <div class="card-body">
        <style>
            tr:hover {background-color:#f5f5f5;}
        </style>
        {!! $imp !!}
        <fieldset  @if($adm !=1) disabled @endif >
        <form class="" id="addForm" name="addForm" action="{{url("examens/saisirnote")}}" method="post">
        {{ csrf_field() }}
        <div  class="form-row col-md-12" >
            {!! $html !!}
        </div>
            <hr>
          {{-- <input type="submit">--}}
            <div class="form-row col-md-12">
                @if($val == 'a' and Auth::user()->hasAccess(1) )
                    <div class="col-md-6 form-group text-left">
                        @if($val == 'a' )
                            <a href="#" class="btn btn-info btn-icon-split " value="visualiser" id="btn2" onclick="return  affecterNotesEtudiansdev(this)" container="addForm">
                                                <span class="icon text-white-50">
                                                    <i class="answers-well-saved text-success fa fa-check"></i>
                                                    <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                                    <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                </span>
                                <span class="text">{{ trans('text_me.devalider') }}</span>
                            </a>
                        @endif
                    </div>
                @endif
                    <?php
                    ob_start();
                    system("ipconfig /all");
                    $mycom=ob_get_contents();
                    ob_clean();
                    $findme = "physique";
                    $pmac = strpos($mycom, $findme);
                    $mac=substr($mycom,($pmac+33),17);
                    ?>
                    <input value="{{$mac}}" name="mac" id="mac" type="hidden">
                @if($val != 'a' )
                <div class="col-md-6 form-group text-right " >
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

                        <div class="col-md-6 form-group text-left">
                            @if($val == 'v' )
                            <a href="#" class="btn btn-info btn-icon-split " value="visualiser" id="btn2" onclick="return  affecterNotesEtudians2(this)" container="addForm">
                                                <span class="icon text-white-50">
                                                    <i class="answers-well-saved text-success fa fa-check"></i>
                                                    <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                                    <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                </span>
                                <span class="text">{{ trans('text_me.valider') }}</span>
                            </a>
                        @endif
                        </div>
                @endif
        </div>
        </form>
        </fieldset>
    </div>
</div>
