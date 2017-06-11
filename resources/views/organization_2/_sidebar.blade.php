<div class="col-md-3 col-sm-12">
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
                                    @if(count($schedules = Helper::get_organization_schedules($organisation->id)))
                                        @foreach($schedules as $schedule)
                                        <table class="wg-score-table">
                                           
                                            <tr>
                                                <td width="36"><img src="{{$schedule->getSideALogoAttribute()}}" alt="" width="36"></td>
                                                <td>{{count($schedule->getSideAAttribute())?$schedule->getSideAAttribute()->name:''}}</td>
                                                <td rowspan="2" style="vertical-align: middle"><a href='match/scorecard/edit/{{$schedule->id}}'>VS
                                                </a></td>
                                                <td>{{count($schedule->getSideBAttribute())?$schedule->getSideBAttribute()->name:''}}</td>
                                                <td width="36"><img src="{{$schedule->getSideBLogoAttribute()}}" alt="" width="36"></td>
                                            </tr>
                                            <tr> 
                                                <td colspan="2">{{$schedule->getScoresAttribute(true)['a']}}</td>
                                              
                                                <td colspan="2">{{$schedule->getScoresAttribute(true)['b']}}</td>
                                            </tr>
                                         
                                            @if($schedule->match_report)
                                            <tr>
                                                <td colspan="5"><a href='match/scorecard/edit/{{$schedule->id}}'>Result:</a> {{$schedule->match_report}}</td>
                                            </tr>
                                            @endif
                                        </table>
                                        <hr>
                                        @endforeach
                                    @else
                                        <p>No Schedule Available
                                    @endif
                                    </div>
                                    <div class="tab-pane" id="tab_default_2">
                                    @if(count($reports = Helper::get_organization_reports($organisation->id)))
                                        @foreach($reports as $key=>$report)
                                         <a href='match/scorecard/edit/{{$report->id}}'>Match {{$key+1}} : </a>   {!!$report->match_report!!}
                                       
                                         <hr>
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
                    <div class="col-md-12 ">
                        <div class="wg wg-white no-shadow">
                            <div class="wg-wrap">
                                <h4>Media Gallery</h4></div>
                            <div id="Carousel" class="carousel slide carousel-fade col-offset-2">
                                <div class="carousel-inner">

                                @foreach($organisation->all_photos as $key_p=>$photo)
                                    <div class="item {{$key_p==0?'active':''}}"> <img src="{{$photo->imagePath}}" class="img-responsive"> 
                                    {{$photo}}
                                    </div>                                
                                @endforeach
                                </div> <a class="left carousel-control" href="#Carousel" data-slide="prev">‹</a> <a class="right carousel-control" href="#Carousel" data-slide="next">›</a> </div>
                        </div>
                    </div>
                    <div class="col-md-12 visible-xs ">
                        <br>
                        <br> </div>
                    <div class="col-md-12">
                        <a href="/organization/{{$organisation->id}}/polls">
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
                                        </div>
                                    </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>