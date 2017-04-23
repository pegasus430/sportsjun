@extends('layouts.organisation')

@section('content')



 <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-header"><div class="ph-mark"><div class="glyphicon glyphicon-menu-right"></div></div> Players in your organization</h2>
                    <div class="create-btn-link"> <a href="" class="wg-cnlink" data-toggle="modal" data-target="#add_player" style="margin-right: 150px;">Add Player</a> <a href="" class="wg-cnlink" data-toggle="modal" data-target="#invite_player">Invite Player</a></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="search-box">
                        <div class="sb-label">Filter by teams in your organization</div>
                        <div class="input-group col-md-12">
                        <form action="" method="get">
                            <input type="text" class="form-control input-lg" placeholder="" name="filter-team" value="{{$filter_team}}" id='filter_team'/> <span class="input-group-btn" type='submit'>                       
              <i class="fa fa-search"></i>
            </span> 
             </form></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="search-reasult">
                      @if(!$members->count())
                        <div class="no-records"><i class="fa fa-frown-o" aria-hidden="true"></i> No Records Found</div>
                      @else
                        <div class="sr-table">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Teams</th>
                                            <th>Sports</th>
                                            <th class="text-center">Ratings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($members as $member)
                                        <tr>
                                            <td>
                                               <span class="member-image">
                    {!! Helper::Images( (count($member['user']['photos'])?$member['user']['photos'][0]['url']:''),'user_profile',array('height'=>60,'width'=>60,'class'=>'img-circle img-border ') ) !!}
                </span>
                                            </td>
                                            <td> <!-- <a class="player-name" onclick="openNav({{$member->id}})"> -->
                                            <a class="player-name" href="#"  onclick="openNav({{$member->id}})" >
                                                  {{$member->name}}
                                                </a></td>
                                            <td>@foreach($member->userdetails as $team)  {{$team->team?$team->team->name:''}}, @endforeach</td>
                                            <td>@foreach($member->getSportListAttribute() as $sport) {{$sport->sports_name}} @endforeach</td>
                                            <td>
                                                  <input type="hidden" class="rating b-rating" value="{{$member->rate}}" data-filled="fa fa-star s-rating" data-empty="fa fa-star-o s-rating"
                           data-target_id="{{$member->id}}" data-type="user"
                    />
                                            </td>
                                        </tr>

                                         <div id="myNav{{$member->id}}" class="overlay"> <a href="javascript:void(0)" class="closebtn" onclick="closeNav({{$member->id}})">&times;</a>
                                
                                            <div class="overlay-content"> 
                                                         <iframe src="/editsportprofile/{{$member->id}}?from_org=true" width="100%"; height="1000px">
                                             
                                                          </iframe>


                                            </div>
                                        </div>
                                      @endforeach

        
                                        <tr>
    <td colspan="5">
        @if ($members->hasMorePages())
            <div id="viewmorediv">
                <a id="viewmorebutton" class="view_tageline_mkt" data-replace="tr"
                   @if (!(isset($is_widget) && $is_widget))
                        data-url="{{route('organization.members.list',['id'=>$id,'page'=>$members->currentPage()+1,'filter-team'=>$filter_team])}}"
                   @else
                        data-url="{{route('widget.organization.members',['id'=>$id,'page'=>$members->currentPage()+1,'filter-team'=>$filter_team])}}"
                   @endif

                   onclick="return DataTableLoadMore(this,function() {InitRatings();})"
                >
                    <span class="market_place"><i
                                class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span>
                </a>
            </div>
        @endif
    </td>
</tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                      @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div data-include="footer"></div>
    </div>
    <!-- Modal Add Player -->
    <div class="modal fade" id="add_player" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="/organization/{{$organisation->id}}/save_player" accept-charset="UTF-8" class="form form-horizontal">
          {!!csrf_field()!!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Add Player</h3> </div>
                    <div class="modal-body">
                        <div class="content row">
                            <div class="input-container two-col">
                                <input type="text" id="player_name" required="required" name="name" />
                                <label for="Username">Player Name</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container two-col">
                                <input type="text" id="player_email" required="" name="email" />
                                <label for="Username">Player Email</label>
                                <div class="bar"></div>
                            </div>
                 <!--            <div class="input-container two-col">
                                <input type="text" id="player_dob" required="required" name="dob" />
                                <label for="Username">DOB</label>
                                <div class="bar"></div>

                            </div> -->
                            <input type="hidden" name="organization_id" value="{{$organisation->id}}">
                            <div class="clearfix"></div>
                            <div class="input-container two-col">
                                <input type="text" id="parent_name" required="" name="parent_name" />
                                <label for="Username">Parent Name</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container two-col">
                                <input type="text" id="parent_name" required="" name="parent_email" />
                                <label for="Username">Parent Email</label>
                                <div class="bar"></div>
                            </div>
                    
                            <div class="input-container select two-col">
                                <div>
                                    <label>Country</label>
                              {!!Form::select('country_id', $countries,$organisation->country_id, ['id'=>'country_id'])!!}
                                  
                                </div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>State</label>
                                   
                                {!!Form::select('state_id', $states,null, ['id'=>'state_id'])!!}
                                  
                                </div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>City</label>
                                {!!Form::select('city_id', $cities,null, ['id'=>'city_id'])!!}
                                  
                                </div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>Team</label>
                                    <select class="" name="team_id" required="">
                                        @foreach($organisation->teamplayers as $team)
                                            <option value="{{$team->id}}">{{$team->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Player</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Modal Invite Player -->
    <div class="modal fade" id="invite_player" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="/getplayers" accept-charset="UTF-8" class="form form-horizontal">
                    {!!csrf_field()!!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Invite Player</h3> </div>
                    <div class="modal-body">
                        <div class="content row">
                            <div class="input-container">
                                <input type="text" id="staff_name" required="required"  name="name" />
                                <label for="Username">Enter Player Name</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container">
                                <input type="text" id="staff_email" required=""  name="email" />
                                <label for="Username">Enter Player Email</label>
                                <div class="bar"></div>
                            </div>

                               <div class="input-container select">
                               <label for="Username">Team</label>
                                <select  name="teamid" required="" >
                                    @foreach($organisation->teamplayers as $team)
                                        <option value="{{$team->id}}">{{$team->name}}</option>
                                    @endforeach
                                </select>
                                
                                <div class="bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Player</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- Overlay -->
   


@stop


@section('end_scripts')
    <script src="/org/js/scripts.js"></script>
    <script src="/org/js/ratings.js"></script>
      <script>
        $(document).ready(function(){
           $('#filter_team').autocomplete({
                        minLength:3,
                       source: function( request, response ) {
                           $.getJSON( "{{route('organization.members.teamlist',['id'=>$id])}}", request, function( data, status, xhr ) {
                               var items = [];
                               for (var key in data){
                                  items.push(
                                          {
                                              label:data[key],
                                              value:data[key]
                                          }
                                  )
                               };
                               response( data );
                           });
                       }
                   }
            );
            $('#filter_team').on('focus', function() {
                $(this).autocomplete( "search");
            });

        });
    </script>
@stop