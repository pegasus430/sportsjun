@extends('home.layout')

@section('content')
    <div class="container">
        @if (count($search_data))
            <div class="clearfix"></div>
            <ul class="search_results">
            @foreach ($search_data as $item)
                <?php $type= array_get(\App\Http\Controllers\SearchController::$TYPES_STRING,get_class($item)); ?>
                <li class="s-result-item">
                        <a href="{{route('public.search.view',['type'=>$type,'id'=>$item->id])}}">
                            <div class="search_thumbnail right-caption row">
                                <div class="col-md-1 col-sm-2 col-xs-12 text-center">
                                    <img data-original="" src="{{ $item->logoImage }}"
                                         title=""
                                         onerror="this.onerror=null;this.src=&quot;http://sportsjun.local/images/default-profile-pic.jpg&quot;"
                                         height="60" width="60" class="img-circle img-border img-scale-down lazy"
                                         style="display: block;">
                                </div>
                                <div class="col-md-11 col-sm-10 col-xs-12">
                                    <span class="s-r-i-title">{{$item->name}}</span>
                                    <span class="s-r-i-type">{{$type}}</span>
                                </div>
                            </div>
                        </a>
                </li>
            @endforeach
            </ul>
    </div>
    @else
        <div class="containter">
            <div class="row">
                <div class="col-md-12" style="min-height:300px;padding:30px;">
                    <div class="alert alert-info" role="alert">
                        <b>{{ trans('search.empty') }}</b>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection