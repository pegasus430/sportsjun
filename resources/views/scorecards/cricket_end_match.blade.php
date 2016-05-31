<div class="row game-assets">
        <div class="col-lg-2 col-lg-offset-5">
                <ul class="list-inline game-assets-list">
                        <li onclick="SJ.SCORECARD.endMatchModal();">
                                <div class="circle-icon hidden-xs">
                                        <i class="icon-end-match"></i>
                                </div>
                                <div class="dropdown">
                                        <a href="javascript:void(0);" class="trigger">END MATCH</a>
                                </div>
                        </li>
                </ul>
        </div>
</div>

<!-- End match modal start -->
<div class="modal fade in tossDetail" tabindex="-1" role="modal" aria-labelledby="endMatchModal" id="endMatchModal">
        <div class="vertical-alignment-helper">
                <div class="modal-dialog modal-lg vertical-align-center">
                        <div class="modal-content create-team-model create-album-popup model-align">
                                <div class="modal-header text-center">
                                        <button type="button" class="close" data-dismiss="modal">×</button>
                                        <h4>END MATCH</h4>
                                </div>
                                <div class="modal-body">
                                        <div class="content">
                                                <div id="matchResultRadio" class="form-group">
                                                        <div class="toss-detail">
                                                                <span class="head">MATCH RESULT</span>
                                                                <div class="radio-box">
                                                                        <div class="radio">
                                                                                <input name="matchResultRadio" type="radio" value="win" id="matchResultRadioWin" checked="">
                                                                                <label for="matchResultRadioWin">WIN</label>
                                                                        </div>
                                                                        <div class="radio">
                                                                                <input name="matchResultRadio" type="radio" value="tie" id="matchResultRadioTie">
                                                                                <label for="matchResultRadioTie">TIE</label>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <div id="matchWinnerRadio" class="form-group">
                                                        <div class="toss-detail">
                                                                <span class="head">WINNER</span>
                                                                <div class="radio-box">
                                                                        <div class="radio">
                                                                                <input name="matchWinnerRadio" type="radio" value="{{ $match_data[0]['a_id'] }}" id="{{ $match_data[0]['a_id'] }}" checked="">
                                                                                <label for="{{ $match_data[0]['a_id'] }}">{{ $team_a_name }}</label>
                                                                        </div>
                                                                        <div class="radio">
                                                                                <input name="matchWinnerRadio" type="radio" value="{{ $match_data[0]['b_id'] }}" id="{{ $match_data[0]['b_id'] }}">
                                                                                <label for="{{ $match_data[0]['b_id'] }}">{{ $team_b_name }}</label>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <div id="matchPlayerSelect" class="form-group">
                                                        <div class="toss-detail">
                                                                <span class="head">PLAYER OF THE MATCH</span>
                                                                <select name="player_of_the_match" class="form-control" id="playerOfTheMatch">
                                                                        @if(!empty($team_a) && count($team_a)>0)
                                                                                @foreach($team_a as $a_key => $a_val)
                                                                                <option value="{{$a_key}}">{{ $a_val }}</option>
                                                                                @endforeach
                                                                        @endif
                                                                        @if(!empty($team_b) && count($team_b)>0)
                                                                                @foreach($team_b as $b_key => $b_val)
                                                                                <?php if (empty($b_key)) continue; ?>
                                                                                <option value="{{$b_key}}">{{ $b_val }}</option>
                                                                                @endforeach
                                                                        @endif
                                                                </select>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="button btn-primary" onclick="SJ.SCORECARD.endMatch();">Done</button>
                                        <button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
                        </div>
                </div>
        </div>
</div>
<!-- Toss won by modal end -->