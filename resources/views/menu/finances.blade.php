
@if(Auth::user()->hasAccess([4]))
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFinances" aria-expanded="true" aria-controls="collapseDemandeurs">
            <i class="fas fa-fw fa-address-book"></i>
            <span>{{trans("text_menu.finances")}}</span>
        </a>
        <div id="collapseFinances" class="collapse" aria-labelledby="headingFinances" data-parent="#mainMenu">
            <div class="bg-white py-2 collapse-inner">
                 <a class="collapse-item" href="{{url('finances')}}">{{trans("text_me.finances")}} </a>
            </div>
        </div>
    </li>
@endif
