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
                            <input type="text" class="form-control input-lg" placeholder="" name="filter-team" value="{{$filter_team}}" /> <span class="input-group-btn" type='submit'>                       
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
                                                <div class="circle-mask">
                                                    <canvas id="canvas" class="circle" width="96" height="96"></canvas>
                                                </div>
                                            </td>
                                            <td> <a class="player-name" onclick="openNav({{$member->id}})">
                                                  {{$member->name}}
                                                </a></td>
                                            <td>@foreach($member->userdetails as $team)  {{$team->team?$team->team->name:''}}, @endforeach</td>
                                            <td>@foreach($member->getSportListAttribute() as $sport) {{$sport->sports_name}} @endforeach</td>
                                            <td>
                                                <div id="stars" class="starrr"></div>
                                            </td>
                                        </tr>
                                         <div id="myNav{{$member->id}}" class="overlay"> <a href="javascript:void(0)" class="closebtn" onclick="closeNav({{$member->id}})">&times;</a>
                                            <div class="overlay-content"> Player data goes here...</div>
                                        </div>
                                      @endforeach
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
                <form method="POST" action="/organization/{{$organisation->id}}/staff" accept-charset="UTF-8" class="form form-horizontal">
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
                            <div class="input-container one-col">
                                <input type="text" id="area" required="required" />
                                <label for="Username">Area</label>
                                <div class="bar"></div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>Country</label>
                                    <select class="" name="staff_role">
                                        <option value="1">India</option>
                                        <option value="12">USA</option>
                                        <option value="5">UK</option>
                                        <option value="14">Canada</option>
                                        <option value="7">Australia</option>
                                        <option value="2">Japan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>State</label>
                                    <select class="" name="staff_role">
                                        <option value="1">India</option>
                                        <option value="12">USA</option>
                                        <option value="5">UK</option>
                                        <option value="14">Canada</option>
                                        <option value="7">Australia</option>
                                        <option value="2">Japan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>City</label>
                                    <select class="" name="staff_role">
                                        <option value="1">India</option>
                                        <option value="12">USA</option>
                                        <option value="5">UK</option>
                                        <option value="14">Canada</option>
                                        <option value="7">Australia</option>
                                        <option value="2">Japan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-container select two-col">
                                <div>
                                    <label>Team</label>
                                    <select class="" name="staff_role">
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