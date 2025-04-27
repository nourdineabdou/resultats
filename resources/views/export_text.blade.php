@extends('layout_export')
@section('page-title')
    Export pdf
@endsection
@section('page-content')

    <div class="exp_info">
            <table style="width: 100%">
                <tr>

                            <td><b>{{ trans("text.objet") }} : </b></td>
                            <td>{{ $libelle }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ trans("text.population") }} : </b></td>

                        </tr>


             </tr>
            </table>



    </div>


    <div class="row">

        <div class="col-md-12">

            <div>
                {!!$data!!}
            </div>



        </div>


    </div>


@endsection
