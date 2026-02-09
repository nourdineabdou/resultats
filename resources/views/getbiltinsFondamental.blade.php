@extends('layout')
@section('page-title')
    
@endsection
@section('top-page-btn')
    <button  onClick="imprimer();" style="float:right; width: 160px; margin-right:185px; margin-top:50px;"><b>
            <img style="width: 30px; height: 30px;"src=""/> <b>سحب إفادة التسجيل</b></b></button>
@endsection
@section('page-content')
    <div class="row">
<div id="" class="box" height="400">


    <div class="right_content" id="printable">
<div id="DivIdToPrint">
{!! $html !!}
</div>
    </div>
</div>
<script type="text/javascript">
    <!--
    function imprimer(){
        var divToPrint=document.getElementById('DivIdToPrint');

        var newWin=window.open('P','Print-Window','font-size: 9px;\n' +
            '        line-height: 9px;');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();


    }
    //-->
</script>
    </div>
@endsection
