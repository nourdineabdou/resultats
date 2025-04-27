@if(Auth::user()->hasAccess([5]))
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDemandeurs" aria-expanded="true" aria-controls="collapseDemandeurs">
    <i class="fas fa-fw fa-address-book"></i>
    <span>{{trans("text_menu.patrimoine")}}</span>
  </a>
  <div id="collapseDemandeurs" class="collapse" aria-labelledby="headingDemandeurs" data-parent="#mainMenu">
    <div class="bg-white py-2 collapse-inner">
      <a class="collapse-item" href="{{url('equipements')}}">{{trans("text_me.equipements")}} </a>
      <a class="collapse-item" href="{{url('typesEquipements')}}">{{trans("text_me.types_equipement")}} </a>
      <a class="collapse-item" href="{{url('localites')}}">{{trans("text_me.localites")}} </a>
      <a class="collapse-item" href="{{url('secteurs')}}">{{trans("text_me.secteurs")}} </a>
    </div>
  </div>
</li>
@endif
