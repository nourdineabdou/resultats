@extends('layout_without_menu')
@section('page-content')
    <div class="row text-center" align="center">
        <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-dashboard"></i>اهلا بيكم يمكنكم الإطلاع  على النتائج </h3>
        </div>
    </div>

        <form role="form"  id="formst1" name="formst1" class=""  method="get" >
            <div class="row col-md-12 text-center" align="center">
                {!! $html !!}
            </div>
        </form>

@endsection
