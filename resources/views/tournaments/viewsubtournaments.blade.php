



                        <table class="table tournament_profile">
                            <tbody>
                                @if(count($subTournamentArray))
                                @foreach($subTournamentArray as $id => $managedTeam)
							
                                <tr>
                                    <td style="border-top: none; padding: 0;">
                                    
                                                <div class="main_tour clearfix">
                                    
                                                <div class="t_details" style="min-height: inherit;">
                                        <div class="row">
                                                <div class="col-sm-2 col-xs-12 text-center">
    
    
                            {!! Helper::Images($managedTeam['sports_logo'],config('constants.PHOTO_PATH.SPORTS'),array('height'=>90,'width'=>90,'class'=>'img-circle img-border lazy') )!!}
                                                </div>
                                                <div class="col-sm-10 col-xs-12">
                                                        <div class="t_tltle">
                                                            <div class="pull-left">
                                                                <a href="{{ url('tournaments/groups').'/'.$id }}">{{ $managedTeam['name'] }}</a>
                                                                <p class="t_by">By {{ $managedTeam['user_name'] }}</p>
                                                            </div>
															@if($isOwner)
																<div class="pull-right"><a href="#" class="schedule_match_main t_action_btns" onclick="subTournamentEdit({{$id}})"><i class="fa fa-pencil"></i></a></div>
															@endif
                                                        </div>
                                                        <ul class="t_tags">
                                                            <li>Sport <span>{{ $managedTeam['sports_name'] }}</span></li>
                                                        </ul>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
<div id="displaytournament"></div>


