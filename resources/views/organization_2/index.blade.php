@extends('layouts.organisation')


@section('content')


    <!-- Body Section -->
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                 <div class="row">
                    <div class="col-md-12">
                        <div class="wg wg-dk-grey  no-shadow no-margin">
                            <div class="wg-wrap clearfix ">
                                <h4 class="no-margin pull-left">Tournaments</h4> <a href="/organization/{{$organisation->id}}/new_tournament"  class="wg-viewmore pull-right">New Tournament <i class="fa fa-angle-double-right">    </i></a> </div>
                        </div>
                    </div>
                </div>

                <div class="wg wg-white">
                    <div class="wg-wrap">
                      
                        <select class="selectpicker">
                         @foreach($parent_tournaments as $parent)
                            <optgroup label="{{$parent->name}}">
                                      @foreach($parent->tournaments as $tournament)
                                <option>{{$tournament->name}}</option>
                                      @endforeach
                             </optgroup>
                         @endforeach
                        </select>
                        <div class="carousel slide event-carousel events" id="events-list">
                            <div class="carousel-inner">

                            <?php $ik=0; ?> 
                                @foreach($parent_tournaments as $key=>$parent)
                                          
                                    @foreach($parent->tournaments as $tournament)
                                          @if($ik%3==0)
                                        <div class="item  {{$key==0?'active':''}}">
                                        <div class="row">
                                            @endif
                                        <div class="col-md-4">
                                            <h4><a href="/tournaments/{{$tournament->id}}">{{$tournament->name}}</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: {{$tournament->status}}</label>
                                        </div>                                        

                                          @if($ik%3==2 )
                                            </div>                                
                                            </div>
                                          @endif

                                     <?php $ik++; ?>

                                    @endforeach
                                   
                             @endforeach
                         @if(!$ik%3==0 )
                            </div>
                        </div>
                        @endif

                            </div> <a data-slide="prev" href="#events-list" class="left carousel-control">‹</a> <a data-slide="next" href="#events-list" class="right carousel-control">›</a> </div>
                    </div>
                </div>

                  <div class="row">
                    <div class="col-md-12">
                        <div class="wg wg-dk-grey  no-shadow no-margin">
                            <div class="wg-wrap clearfix ">
                                <h4 class="no-margin pull-left">Coaching</h4> <a  class="wg-viewmore pull-right">View More <i class="fa fa-angle-double-right">    </i></a> </div>
                        </div>
                    </div>
                </div>
                <div class="wg wg-white">
                    <div class="wg-wrap">
                        <div class="wg-hd">
                            <a href="" class="wg-cnlink">Create Coaching</a> </div>
                        <div class="carousel slide event-carousel events" id="coachings">
                            <div class="carousel-inner">
                                <div class="item  active">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h4><a href="#">Hyderabad Corporate Olymipics - Mens Socces 2016</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: Completed</label>
                                        </div>
                                        <div class="col-md-4">
                                            <h4><a href="#">Hyderabad Corporate Olymipics - Mens Socces 2016</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: Completed</label>
                                        </div>
                                        <div class="col-md-4">
                                            <h4><a href="#">Hyderabad Corporate Olymipics - Mens Socces 2016</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: Completed</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h4><a href="#">Hyderabad Corporate Olymipics - Mens Socces 2016</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: Completed</label>
                                        </div>
                                        <div class="col-md-4">
                                            <h4><a href="#">Hyderabad Corporate Olymipics - Mens Socces 2016</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: Completed</label>
                                        </div>
                                        <div class="col-md-4">
                                            <h4><a href="#">Hyderabad Corporate Olymipics - Mens Socces 2016</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: Completed</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h4><a href="#">Hyderabad Corporate Olymipics - Mens Socces 2016</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: Completed</label>
                                        </div>
                                        <div class="col-md-4">
                                            <h4><a href="#">Hyderabad Corporate Olymipics - Mens Socces 2016</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: Completed</label>
                                        </div>
                                        <div class="col-md-4">
                                            <h4><a href="#">Hyderabad Corporate Olymipics - Mens Socces 2016</a></h4> <span class="schedule">23rd Oct, 2017 - 05th Nov, 2017</span>
                                            <label>Status: Completed</label>
                                        </div>
                                    </div>
                                </div>
                            </div> <a data-slide="prev" href="#coachings" class="left carousel-control">‹</a> <a data-slide="next" href="#coachings" class="right carousel-control">›</a> </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="wg wg-dk-grey  no-shadow no-margin">
                            <div class="wg-wrap clearfix ">
                                <h4 class="no-margin pull-left">Just Added Teams</h4> <a href="/organizationTeamlist/{{$organisation->id}}" class="wg-viewmore pull-right">View More <i class="fa fa-angle-double-right">    </i></a> </div>
                        </div>
                    </div>
                </div>
                 <div class="col-md-12 wg wg-white space-top-half text-center">
                    @foreach($organisation->teamplayers as $team)
                    <div class="col-lg-3 col-sm-6 ">
                        <p class="text-center">{{$team->name}}</p> <img src="/uploads/teams/{{$team->logo}}" alt="" class="img-responsive" style="height: 150px; width: 150px; border-radius: 50%"> 
                    </div>
                    <!-- .col-lg-3.col-sm-6 -->
                    @endforeach
                    <!-- .col-lg-3.col-sm-6 -->
                </div>
            </div>
            <div class="col-md-12 hidden-lg hidden-md">
                <br>
                <br> </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="wg wg-dk-grey no-shadow no-margin">
                            <div class="wg-wrap">
                                <h4>Top Headlines</h4>
                                <div class="wg wg-white no-shadow no-margin">
                                    <ul class="wg-tp-headlines">
                                        <li><a href="#">Lorem ipsum dolor sit amet</a></li>
                                        <li><a href="#">Sed do eiusmod tempor incididunt</a></li>
                                        <li><a href="#">Velit esse cillum dolore eu fugiat</a></li>
                                        <li><a href="#">Excepteur sint occaecat cupidatat</a></li>
                                        <li><a href="#">Sunt in culpa qui officia</a></li>
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
                                    @if(count($schedules))
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
                                    @if(count($reports))
                                        @foreach($reports as $report)
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

                                @foreach($photos as $key_p=>$photo)
                                    <div class="item {{$key_p==0?'active':''}}"> <img src="http://placehold.it/300x200/6a737b/ffffff" class="img-responsive"> </div>
                                @endforeach
                                </div> <a class="left carousel-control" href="#Carousel" data-slide="prev">‹</a> <a class="right carousel-control" href="#Carousel" data-slide="next">›</a> </div>
                        </div>
                    </div>
                    <div class="col-md-12 visible-xs ">
                        <br>
                        <br> </div>
                    <div class="col-md-12 col-sm-6">
                        <div class="wg wg-purple no-shadow wg-poll">
                            <div class="wg-wrap">
                                <h4>Poll</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.?</p>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios"> YES </label>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios"> NO </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="wg wg-dk-grey no-shadow">
                    <div class="wg-wrap clearfix">
                        <h4 class="no-margin pull-left">Just Added Products</h4> <a href="" class="wg-viewmore pull-right">View More <i class="fa fa-angle-double-right">    </i></a></div>
                </div>
            </div>
        </div>
        <div class="row space-top-half">

            @foreach($marketplace as $item)
            <div class="col-lg-3 col-sm-6">
                <div class="shop-item">
                    <div class="shop-thumbnail"> <span class="shop-label text-danger">Sale</span>
                        <a href="javascript:void(0)" data-pid="{{ $item['id'] }}"  data-page='marketplace' data-toggle="tooltip" data-placement="top" title="View Detail" class="view_gallery  item-link"></a> <img src="/org/marketplace/mp_img_1.png" alt="Shop item">
                        <div class="shop-item-tools">
                         @if(Auth::user()->type !=1)
                            <a href="#" class="add-to-whishlist" data-toggle="tooltip" data-placement="top" title="" data-original-title="Wishlist"> <i class="fa fa-heart-o"></i> </a>
                            <a href="#" class="add-to-cart" onclick="add_to_cart({{$item->id}})"> <em>Add to Cart</em>
                                <svg x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32">
                                    <path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"></path>
                                </svg>
                            </a>
                        @endif
                        </div>
                    </div>
                    <div class="shop-item-details">
                        <h3 class="shop-item-title"><a href="">{{$item->item}}</a></h3> <span class="shop-item-price">
                        <span class="old-price">${{$item->base_price}}</span> ${{$item->actual_price}} </span>
                    </div>
                </div>
                <!-- .shop-item -->
            </div>
            @endforeach
            <!-- .col-lg-3.col-sm-6 -->
           
            <!-- .col-lg-3.col-sm-6 -->
        </div>
    </div>





<div aria-hidden="true" style="display: none;" class="modal" id="modal-gallery" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal">×</button>
          <h3 class="modal-title">Image 11</h3>
      </div>
      <div class="modal-body clearfix">
            <div class="col-sm-8 col-xs-12">
              <div id="modal-carousel" class="carousel slide carousel-fade">
                <div class='carousel-outer'>
                    <div class="carousel-inner"></div>
                    <a class="carousel-control left" href="#modal-carousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
                    <a class="carousel-control right" href="#modal-carousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
                </div>
                <!-- Indicators -->
              <!-- <ol class='carousel-indicators mCustomScrollbar'>
                    <li data-target='#carousel-custom' data-slide-to='0' class='active'><img src='http://placehold.it/100x50&text=slide1' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='1'><img src='http://placehold.it/100x50&text=slide2' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='2'><img src='http://placehold.it/100x50&text=slide3' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='3'><img src='http://placehold.it/100x50&text=slide4' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='4'><img src='http://placehold.it/100x50&text=slide5' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='5'><img src='http://placehold.it/100x50&text=slide6' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='6'><img src='http://placehold.it/100x50&text=slide7' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='7'><img src='http://placehold.it/100x50&text=slide8' alt='' /></li>
                    <li data-target='#carousel-custom' data-slide-to='8'><img src='http://placehold.it/100x50&text=slide9' alt='' /></li>
                </ol>-->
            </div>
            </div>
      </div>
      <div class="clearfix"></div>
      <!--<div class="modal-footer">
          <button class="btn btn-green" data-dismiss="modal">Close</button>
      </div>-->
    </div>
  </div>
</div>
@stop