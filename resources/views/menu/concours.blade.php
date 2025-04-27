@if(Auth::user()->hasAccess([1]))
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link collapsed @if(App::isLocale('ar')) text-right @endif" href="#" data-toggle="collapse" data-target="#collapseManageUsers" aria-expanded="true" aria-controls="collapseManageUsers">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{trans("text_menu.gestionconcours")}}</span>
        </a>
        <div id="collapseManageUsers" class="collapse" aria-labelledby="heaManageUsers" data-parent="#mainMenu">
            <div class="bg-white py-2 collapse-inner">
                <a class="collapse-item" href="{{url('candidats')}}">{{trans("text_menu.incriptions")}}</a>
                <a class="collapse-item" href="{{url('examensCON')}}">{{trans("text_menu.examen")}}</a>
            </div>
        </div>
    </li>
@endif
