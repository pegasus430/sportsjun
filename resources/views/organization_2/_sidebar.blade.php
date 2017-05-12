<div class="col-md-3">
                <div class="row">
                <div class="col-md-12">                       
                    </div>
                    <div class="col-md-12">
                        <div class="wg wg-dk-grey no-shadow no-margin">
                            <div class="wg-wrap">
                                <h4 class="">Top Headlines
                                <a href="/organization/{{$organisation->id}}/news"  class="viewmore">View More &raquo;</a> </h4>
                                <div class="wg wg-white no-shadow ">
                                    <ul class="wg-tp-headlines">
                                        @if(count($organisation->news))
                                        @foreach($organisation->news as $nw)
                                        <li><a href="/organization/{{$organisation->id}}/news/{{$nw->id}}">{{$nw->title}}</a></li>
                                        @endforeach
                                        @else
                                        <li>
                                            Nothing to display
                                        </li>
                                        @endif

                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 hidden-lg hidden-md">
                        <br>
                        <br> </div>
                    <div class="col-md-12">
                        <div class="tabbable-panel">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs ">
                                    <li class="active"> <a href="#tab_default_1" data-toggle="tab">
							LIVE </a> </li>
                                    <li> <a href="#tab_default_2" data-toggle="tab">
							RESULT </a> </li>
                                    <li> <a href="#tab_default_3" data-toggle="tab">
							FIXTURE </a> </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_default_1">
                                    @if(count($schedules = Helper::get_organization_reports($organisation->id)))
                                        @foreach($schedules as $schedule)
                                        <table class="wg-score-table">
                                            <tr>
                                                <td width="36"><img src="/org/images/nations-flags/india-sm.png" alt="" width="36"></td>
                                                <td>IND</td>
                                                <td rowspan="2" style="vertical-align: middle">VS</td>
                                                <td>ENG</td>
                                                <td width="36"><img src="/org/images/nations-flags/england-sm.png" alt="" width="36"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">312/3 <span>(32.4)</span></td>
                                                <td colspan="2">312/3 <span>(32.4)</span></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">Result: IND won by 8 wkts</td>
                                            </tr>
                                        </table>
                                        @endforeach
                                    @else
                                        <p>No Schedule Available
                                    @endif
                                    </div>
                                    <div class="tab-pane" id="tab_default_2">
                                    @if(count($organisation->reports))
                                        @foreach($organization->reports as $report)
                                            {!!$report->match_report!!}
                                        @endforeach
                                    @else
                                        <p>No Results available
                                    @endif
                                    </div>
                                    <div class="tab-pane" id="tab_default_3"> ... </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-6">
                        <div class="wg wg-white no-shadow">
                            <div class="wg-wrap">
                                <h4>Media Gallery</h4></div>
                            <div id="Carousel" class="carousel slide carousel-fade col-offset-2">
                                <div class="carousel-inner">

                                @foreach($organisation->photos as $key_p=>$photo)
                                    <div class="item {{$key_p==0?'active':''}}"> <img src="" class="img-responsive"> </div>                                
                                @endforeach
                                </div> <a class="left carousel-control" href="#Carousel" data-slide="prev">‹</a> <a class="right carousel-control" href="#Carousel" data-slide="next">›</a> </div>
                        </div>
                    </div>
                    <div class="col-md-12 visible-xs ">
                        <br>
                        <br> </div>
                    <div class="col-md-12 col-sm-6">
                        <div class="wg wg-purple no-shadow wg-poll" style="min-height: 100px;">
                            <div class="wg-wrap">
                                <h4>Poll</h4>

                                   <div id="poll_carousel" class="carousel slide carousel-fade col-offset-2">
                                        <div class="carousel-inner">
                                            @foreach($organisation->polls as $key=>$poll)
                                                <div class="item {{$key==0?'active':''}}" >
                                                    <p>{{$poll->title}}</p>
                                                    <div class="panel-body">
                                                        <ul class="list-group">
                                                            @foreach($poll->options as $option)
                                                                <BR>
                                                            <li class="list-item" style="text-align: left; padding-left: 10px;">
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" name="optionsRadios"> {{$option->title}} </label>
                                                                </div>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach

                                            <a class="left carousel-control" href="#poll_carousel" data-slide="prev">‹</a> <a class="right carousel-control" href="#poll_carousel" data-slide="next">›</a> </div>

                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>