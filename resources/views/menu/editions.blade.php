@if(Auth::user()->hasAccess([1,11,7]))
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link collapsed @if(App::isLocale('ar')) text-right @endif" href="#" data-toggle="collapse" data-target="#collapseManageUsers" aria-expanded="true" aria-controls="collapseManageUsers">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{trans("text_menu.editions")}}</span>
        </a>
        <div id="collapseManageUsers" class="collapse" aria-labelledby="heaManageUsers" data-parent="#mainMenu">
            <div class="bg-white py-2 collapse-inner">
                <a class="collapse-item" href="{{url('editions')}}">{{trans("text_menu.editions")}}</a>

            </div>
        </div>
    </li>
@endif
