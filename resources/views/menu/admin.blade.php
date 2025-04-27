@if(Auth::user()->hasAccess([1]))


<hr class="sidebar-divider my-0">
  <li class="nav-item">
      <a class="nav-link collapsed @if(App::isLocale('ar')) text-right @endif" href="#" data-toggle="collapse" data-target="#collapseManageUsers" aria-expanded="true" aria-controls="collapseManageUsers">
        <i class="fas fa-fw fa-cog"></i>
        <span>{{trans("text_menu.gestion_utilisateurs")}}</span>
      </a>
      <div id="collapseManageUsers" class="collapse" aria-labelledby="heaManageUsers" data-parent="#mainMenu">
        <div class="bg-white py-2 collapse-inner">
          <a class="collapse-item" href="{{url('admin/users')}}">{{trans("text_menu.utilisateurs")}}</a>
          <a class="collapse-item d-none" href="{{url('admin/users/disabled')}}">{{trans("text_menu.utilisateurs_suspendus")}}</a>
          <a class="collapse-item" href="{{url('admin/profiles')}}">{{trans("text_menu.profils")}}</a>
          <a class="collapse-item" href="{{url('admin/droits')}}">{{trans("text_menu.droits")}}</a>
        </div>
      </div>
  </li>
@endif
