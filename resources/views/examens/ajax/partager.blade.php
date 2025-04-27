<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.partager') }}</h5>


    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body" id="addForm">

    <form class="" id="addForm" name="addForm" method="post">
        {{ csrf_field() }}
    <div class="row">

        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3 filters-item">
                            <div class="filters-label">
                                <i class="fa fa-filter"></i> {{ trans('text_me.profils') }}
                            </div>
                            <select id="profil"  name="profil" data-live-search="true" class="selectpicker form-control" onchange="changeProfilEdition()" >
                                <option value="all" >{{ trans('text_me.tous') }}</option>
                                @foreach($profils as $profil)
                                    <option value="{{ $profil->id }}"> {{ $profil->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 filters-item">
                            <div class="filters-label">
                                <i class="fa fa-filter"></i> {{ trans('text_me.classechanger') }}
                            </div>
                            <div class="filters-input">
                                <select name="classe2" id="classe2" class=" form-control" onchange="activeBtn()">
                                    <option value=""></option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}"> {{ $classe->libelle }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatableshow1" selected="" link="{{url("editions/getDT/all/all")}}" colonnes="case_coche,NODOS,NOMA,NOMF,nni" class="table table-hover table-bordered datatableshow1">
                            <thead>
                            <tr>
                                <th width="30px"></th>
                                <th>{{ trans('text_me.nodos') }}</th>
                                <th>{{ trans('text_me.prenom') }}</th>
                                <th>{{ trans('text_me.nom') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="col-md-12 form-row">
                            <div class="col-md-6 text-left form-group" @if(!Auth::user()->hasAccess(1,2)) style="display: none" @endif>
                                <button class="btn btn-primary btn-icon-split" id="btn2" value="save" onclick="retourneEtudiantsPartager(this)" container="addForm" >
                                        <span class="icon text-white-50">
                                            <i class="main-icon fas fa-save"></i>
                                            <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                            <i class="answers-well-saved text-primary fa fa-check" style="display:none" aria-hidden="true"></i>
                                        </span>
                                    <span class="text">Retourner tous les eleves aux classe 1 de ce niveau</span>
                                </button>
                                <div id="form-errors" class="text-left"></div>
                            </div> <div class="col-md-6 text-right form-group">
                                <button class="btn btn-success btn-icon-split" id="btn1" value="save" onclick="saveEtudiantsPartager(this)" container="addForm" style="display: none">
                                        <span class="icon text-white-50">
                                            <i class="main-icon fas fa-save"></i>
                                            <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                            <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                        </span>
                                    <span class="text">{{ trans('text_me.distribuer') }}</span>
                                </button>
                                <div id="form-errors" class="text-left"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
