@extends('layout_without_menu')
@section('page-menu')
  @if(Auth::user()->hasAccess([1,11]) && session()->get('module') && ( session()->get('module')->id == 8 || session()->get('module')->id == 9 || session()->get('module')->id == 5 || session()->get('module')->id == 4 || session()->get('module')->id == 7 || session()->get('module')->id == 11))
  <ul class="navbar-nav {{session()->get('module')->bg_color}} sidebar sidebar-dark accordion" id="mainMenu">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{url('/')}}">
      <div class="sidebar-brand-icon">
        <img height="50px" src="{{url('img/modules/'.session()->get('module')->id.'.png')}}" alt="">
      </div>
      <div class="sidebar-brand-text ml-2"> {{(App::isLocale('ar')) ? session()->get('module')->libelle_ar : session()->get('module')->libelle}}</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active">
      <a class="nav-link @if(App::isLocale('ar')) text-right @endif" href="{{url('dashboard')}}">
        <i class="fas fa-fw fa-list"></i>
        <span>{{trans("text_menu.menu_principal")}}</span></a>
    </li>
    <hr class="sidebar-divider my-0">

    @switch(session()->get('module')->id)
      @case(5) @include('menu.concours') @break
      @case(2) @include('menu.patrimoines') @break
     {{-- @case(3) @include('menu.employes') @break--}}
      @case(4) @include('menu.editions') @break
      @case(7) @include('menu.editions') @break
      @case(8) @include('menu.admin') @break
      @case(9) @include('menu.parametres') @break
    @endswitch
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
  </ul>
  @endif
@endsection
