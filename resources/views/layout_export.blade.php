<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>@yield("page-title")</title>

 <style>
     @page { margin: 25px;
     padding: 20px;}


     body {
         font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
         font-size: 14px;
         line-height: 1.42857143;
         color: #333;
         background-color: #fff;
     }

     table {
         border-collapse: collapse;
         border-spacing: 0;
         background-color: transparent;
     }
     caption {
         padding-top: 8px;
         padding-bottom: 8px;
         color: #777777;
         text-align: left;
     }
     th {
         text-align: left;
     }
     .table {
         width: 100%;
         max-width: 100%;
         margin-bottom: 20px;
     }
     .table > thead > tr > th,
     .table > tbody > tr > th,
     .table > tfoot > tr > th,
     .table > thead > tr > td,
     .table > tbody > tr > td,
     .table > tfoot > tr > td {
         padding: 8px;
         line-height: 1.42857143;
         vertical-align: top;
         border-top: 1px solid #dddddd;
     }
     .table > thead > tr > th {
         vertical-align: bottom;
         border-bottom: 2px solid #dddddd;
     }
     .table > caption + thead > tr:first-child > th,
     .table > colgroup + thead > tr:first-child > th,
     .table > thead:first-child > tr:first-child > th,
     .table > caption + thead > tr:first-child > td,
     .table > colgroup + thead > tr:first-child > td,
     .table > thead:first-child > tr:first-child > td {
         border-top: 0;
     }
     .table > tbody + tbody {
         border-top: 2px solid #dddddd;
     }
     .table .table {
         background-color: #ffffff;
     }
     .table-condensed > thead > tr > th,
     .table-condensed > tbody > tr > th,
     .table-condensed > tfoot > tr > th,
     .table-condensed > thead > tr > td,
     .table-condensed > tbody > tr > td,
     .table-condensed > tfoot > tr > td {
         padding: 5px;
     }
     .table-bordered {
         border: 1px solid #dddddd;
     }
     .table-bordered > thead > tr > th,
     .table-bordered > tbody > tr > th,
     .table-bordered > tfoot > tr > th,
     .table-bordered > thead > tr > td,
     .table-bordered > tbody > tr > td,
     .table-bordered > tfoot > tr > td {
         border: 1px solid #dddddd;
     }
     .table-bordered > thead > tr > th,
     .table-bordered > thead > tr > td {
         border-bottom-width: 2px;
     }
     .table-striped > tbody > tr:nth-of-type(odd) {
         background-color: #f9f9f9;
     }
     .table-hover > tbody > tr:hover {
         background-color: #f5f5f5;
     }
     table col[class*="col-"] {
         position: static;
         float: none;
         display: table-column;
     }
     table td[class*="col-"],
     table th[class*="col-"] {
         position: static;
         float: none;
         display: table-cell;
     }


 .exp_info
 {
     display: block;
     min-height: 20px;
     padding: 19px;
     margin-bottom: 20px;
     background-color: #f5f5f5;
     border: 1px solid #e3e3e3;
     border-radius: 6px;
     -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
     box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
     /*padding: 24px;
     border-radius: 6px;*/
 }

     /*@if((App::isLocale('ar')))
        body {
         direction: rtl !important; text-align: right!important;
     }
     *{ font-family: DejaVu Sans !important;}

     @endif*/
 </style>

</head>
<body>
<div id="app-container">
    <div class="header-img" style="width: 100%; margin-bottom:5px;">

       {{-- @if(env('DCS_APP') == '0')
            <img  style="width: 100%;" src="{{ url('img/header.jpg')}}"/>
        @else
            <img  style="width: 100%;" src="{{ url('img/header.jpg')}}"/>
        @endif--}}
    </div>
    @yield("page-content")

</div>



</body>
</html>
