@if(Auth::user()->hasAccess([6]))
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDemandeurs" aria-expanded="true" aria-controls="collapseDemandeurs">
    <i class="fas fa-fw fa-address-book"></i>
    <span>{{trans("text_menu.rh")}}</span>
  </a>
  <div id="collapseDemandeurs" class="collapse" aria-labelledby="headingDemandeurs" data-parent="#mainMenu">
    <div class="bg-white py-2 collapse-inner">
      <a class="collapse-item" href="{{url('employes')}}">{{trans("text_hb.employes")}} </a>
      <a class="collapse-item" href="{{url('ref/Specialite')}}">{{trans("text_hb.specialites")}} </a>
      <a class="collapse-item" href="{{url('ref/RefNiveauEtude')}}">{{trans("text_hb.niveau_etudes")}} </a>
      <a class="collapse-item" href="{{url('ref/Service')}}">{{trans("text_hb.services")}} </a>
      <a class="collapse-item" href="{{url('ref/RefAppreciationsHierarchy')}}">{{trans("text_hb.Appreciation_heirarchie")}} </a>
      <a class="collapse-item" href="{{url('ref/RefTypesContrat')}}">{{trans("text_hb.type_contrat")}} </a>
      <a class="collapse-item" href="{{url('ref/RefSituationFamilliale')}}">{{trans("text_hb.sit_fam")}} </a>
    </div>
  </div>
</li>
@endif
