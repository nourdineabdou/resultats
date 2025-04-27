@extends('layouts.app')

        <div class="col-lg-12 panel" align="center">
            {!! $html !!}
            <br><div class="panel-body">
            رجاءا تأكدوا من صحة البيانات المطلوب عن طريق افادة التسجيل
            </div> <br> <a class="btn btn-primary" href="{{ url('login') }}">
                <i class="fa fa-sign-out fa-fw"></i> @lang('text_ah.deconnecter')<br> Déconnecter
            </a>
        </div>


