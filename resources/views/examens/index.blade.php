@extends('layout')
@section('page-title')
    {{ trans('text_me.examen') }}
@endsection

@section('page-content')
    <div class="row">
        <div class="col-lg-12">
            @if (session('successmsg') || session('errormsg'))
                <div class="alert alert-{{(session('successmsg'))?'success':'danger'}} alert-dismissible">
                    {{ session('successmsg') }}{{ session('errormsg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
                <input id="idsEt" name="idsEt" type="hidden">
            <div class="card">
                <div class="card-body">
                    <div class="form-row ">
                        <div id="divleft" class="text-center form-group col-md-2 ">
                            <div class="card" >
                                <div class="card-header">{{ trans('text_me.operation_collectives') }}</div>
                                <div class="card-body p-0 " id="divlefdesabled" style="display: none">
                                    <br>

                                    @if(Auth::user()->hasAccess(1) )
                                            <div class="col-md-12 mb-3">
                                                <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getParemetreImpression()">{{ trans('text_me.liste_emergement') }}</button>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getParemetreImpression1()">{{ trans('text_me.liste_emergement1') }}</button>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                    <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getParemetreImpressionCollect()">{{ trans('text_me.liste_collecte_notes') }}</button>
                                            </div>
                                     @endif
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="saisieNotes()">{{ trans('text_me.saisies_notes') }}</button></div>
                                    @if(Auth::user()->hasAccess(1))
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="calculerNotes()">{{ trans('text_me.calcul_notes') }}</button></div>
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" ONCLICK="getPVImpressionCollect()">{{ trans('text_me.impresion_pv') }}</button></div>
                                        <div class="col-md-12">
                                            <form role="form"  id="formst1" name="formst1" class=""  method="get" >
                                                <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getBultinImpressionCollect()">{{ trans('text_me.impresion_bultion_notes') }}</button>
                                            </form>
                                        </div>
                                        <div class="col-md-12">
                                            <form role="form"  id="formst11" name="formst11" class=""  method="get" >
                                                <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getBultinImpressionCollect11()">{{ trans('text_me.impresion_bultion_notes1') }}</button>
                                            </form>
                                        </div>
                                        <div class="col-md-12">
                                            <form role="form"  id="formst111" name="formst111" class=""  method="get" >
                                            <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getBultinImpressionCollect11AN()">{{ trans('text_me.cloture_periode') }}</button>
                                            </form>
                                        </div>
										<div class="col-md-12">
                                            <form role="form"  id="formst111" name="formst111" class=""  method="get" >
                                            <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getBultinImpressionCollect11AN2()">{{ trans('text_me.IMP_periode') }}</button>
                                            </form>
                                        </div>
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="annulercalculerNotes()">{{ trans('text_me.ancalcul_notes') }}</button></div>
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="genererAnonymats()">{{ trans('text_me.genererAnonymat') }}</button></div>
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block"  onclick="repartisserLesSalles()">{{ trans('text_me.genererSalles') }}</button></div>
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="saisieNotesSemestreAN()">{{ trans('text_me.saisies_notesSemAn') }}</button></div>
                                        <div class="col-md-12">
                                            <form role="form"  id="formst12" name="formst12" class=""  method="get" >
                                                <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="imprimerSemestreAN()">{{ trans('text_me.imprimerAttestation') }}</button>
                                            </form>
                                        </div>
                                         <div class="col-md-12">
                                             <form role="form"  id="formst1M" name="formst1M" class=""  method="get" >
                                                 <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getMajSemestre()">{{ trans('text_me.impresion_5premieres') }}</button>
                                             </form>
                                        </div>
                                        <div class="col-md-12">
                                            <form role="form"  id="formst13" name="formst13" class=""  method="get" >
                                                <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="imprimerAtteAN()">{{ trans('text_me.imprimerAttestationATT') }}</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="divcenter" class=" form-group col-md-8">
                            <div class="card">
                                <div class="card-header">{{ trans('text_me.traitement') }}</div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-4 form-group">
                                            <label for="profil">{{ trans('text_me.profil') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                            <select name="profil" id="profil" class="selectpicker form-control" onchange="groupes_profil()">
                                                <option value=""></option>
                                                @foreach($profils as $profil)
                                                    @if(Auth::user()->id== 28)
                                                        @if($profil->id ==66)
                                                        <option value="{{$profil->id}}">{{$profil->id}} -{{$profil->libelle}}  /{{$profil->ref_niveau_etude->libelle}}</option>
                                                        @endif
                                                    @endif
                                                        @if(Auth::user()->id== 56)
                                                            @if($profil->id ==65)
                                                                <option value="{{$profil->id}}">{{$profil->id}} -{{$profil->libelle}}  /{{$profil->ref_niveau_etude->libelle}}</option>
                                                            @endif
                                                        @endif
                                                        @if(Auth::user()->id != 56 and Auth::user()->id != 28)
                                                    <option value="{{$profil->id}}">{{$profil->id}} -{{$profil->libelle}}  /{{$profil->ref_niveau_etude->libelle}}</option>
                                                        @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label for="semestre">{{ trans('text_me.semestre') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                            <select name="semestre" id="semestre" class="selectpicker form-control" onchange="activedivs()">
                                                <option value=""></option>
                                                @foreach($semestres as $semestre)
                                                    <option value="{{$semestre->id}}">{{$semestre->libelle}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="etape">{{ trans('text_me.etape') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                            <select name="etape" id="etape" class="selectpicker form-control" onchange="activedivs()">
                                                <option value=""></option>
                                                @foreach($etapes as $etape)
                                                    <option value="{{$etape->id}}">{{$etape->libelle}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label for="groupe">{{ trans('text_me.groupe') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                            <select name="groupe" id="groupe" class=" form-control" onchange="activedivs()">
                                                <option value=""></option>
                                                @foreach($groupes as $groupe)
                                                    <option value="{{$groupe->id}}">{{$groupe->libelle}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="datatableshow" selected="" link="{{url("examens/getDT/all")}}" colonnes="id,NODOS,NOMA,NOMF,case_coche" class="table table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <th width="30px"></th>
                                                <th>{{ trans('text_me.nodos') }}</th>
                                                <th>{{ trans('text_me.noma') }}</th>
                                                <th>{{ trans('text_me.nomf') }}</th>
                                                <th width="80px">{{ trans('text.actions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="divright" class="text-center form-group col-md-2">
                            @if((Auth::user()->hasAccess(1) or Auth::user()->hasAccess([4,3]) or Auth::user()->hasAccess([5,3])))
                            <div class="card">
                                <div class="card-header">
                                    {{ trans('text_me.operation_individuele') }}
                                </div>
                                @if(Auth::user()->hasAccess(1))
                                    <div class="col-md-12">

                                        <button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="releveNoteIndiv()()">{{ trans('text_me.releveNoteIndiv') }}</button>
                                    </div>

                                @endif
                                <div class="card-body p-0" id="divrigtdesabled" style="display: none">
                                    <br>
                                    @if((Auth::user()->hasAccess(1) or Auth::user()->hasAccess([4,3]) or Auth::user()->hasAccess([5,3])))
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getNoteIndiv()">{{ trans('text_me.saisies_individuel_notes') }}</button></div>
                                    @endif
                                    @if(Auth::user()->hasAccess(1))
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getNoteIndivan()">{{ trans('text_me.saisies_individuel_notesan') }}</button></div>
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="calculerNote()">{{ trans('text_me.calcul_individuel_notes') }}</button></div>
										<div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="calculerNoteNow()">{{ trans('text_me.calcul_individuel_note_now') }}</button></div>

                                    @endif
                                     @if(Auth::user()->hasAccess(1))
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="annulercalculerNoteAn()">{{ trans('text_me.ancalcul_notes') }}</button></div>
                                        <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block">{{ trans('text_me.impresion_bultion_notes') }}</button></div>
                                     @endif
                                    @if(Auth::user()->hasAccess(1) and Auth::user()->code == null)
                                    <form role="form"  id="formst1MM" name="formst1MM" class=""  method="get" >
                                    <div class="col-md-12"><button type="button" class="btn btn-outline-secondary mb-3 btn-block" onclick="getNoteIndivModifier()">{{ trans('text_me.getNoteIndivModifier') }}</button></div>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
