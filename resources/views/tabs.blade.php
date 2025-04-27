<div class="modal-header">
    <h4 class="modal-title">{!! $modal_title !!}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <nav>
        <div class="nav nav-tabs main-tabs" id="nav-tab_tab" role="tablist">
            @foreach($tabs as $tab_title => $tab_link)
                @php
                    $it = (isset($numbre)) ? $numbre[$loop->iteration] : $loop->iteration;
                @endphp
                <a link="{{$tab_link}}" href="#tab{{$it}}" @if($loop->first) aria-selected="true" class="nav-item nav-link active" @else class="nav-item nav-link" aria-selected="false" @endif id="link{{$it}}" aria-controls="tab{{$it}}" role="tab" data-toggle="tab"> {!!$tab_title!!} </a>
            @endforeach
        </div>
    </nav>
    <div class="tab-content" style="z-index: 10000!important;">
        @foreach($tabs as $tab)
            @php
                $it = (isset($numbre)) ? $numbre[$loop->iteration] : $loop->iteration;
            @endphp
            <div role="tabpanel" class="tab-pane fade @if($loop->first) show active @endif" aria-labelledby="link{{$it}}" id="tab{{$it}}"></div>
        @endforeach
    </div>

</div>
