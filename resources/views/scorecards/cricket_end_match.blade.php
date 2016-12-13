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
                                                <div id="matchResultSelect" class="form-group">
                                                        <div class="toss-detail">
                                                                <span class="head">MATCH RESULT</span>
                                                                <select name="match_result" class="form-control">
                                                                    <option value="win" <?php if ($match_data[0]['is_tied'] == 0 && $match_data[0]['winner_id'] > 0 || !($match_data[0]['is_tied'] > 0)) { echo "selected"; } ?>>Win</option>
                                                                    <option value="tie" <?php if ($match_data[0]['is_tied'] > 0) { echo "selected"; } ?>>Tie</option>
                                                                    <option value="washout" <?php if (isset($match_data[0]['match_result']) && $match_data[0]['match_result'] == "washout") { echo "selected"; } ?>>No Result (Washout)</option>
                                                                </select>
                                                        </div>
                                                </div>
                                                <div id="matchWinnerRadio" class="form-group" <?php if ($match_data[0]['is_tied'] > 0 || $match_data[0]['match_result'] == "washout") { echo "style=\"display:none;\""; } ?>>
                                                        <div class="toss-detail">
                                                                <span class="head">WINNER</span>
                                                                <div class="radio-box">
                                                                        <div class="radio">
                                                                                <input name="matchWinnerRadio" type="radio" value="{{ $match_data[0]['a_id'] }}" id="{{ $match_data[0]['a_id'] }}" <?php if ((isset($match_data[0]['winner_id']) && $match_data[0]['winner_id'] == $match_data[0]['a_id']) || empty($match_data[0]['winner_id']) ) { echo "checked=\"\""; } ?>>
                                                                                <label for="{{ $match_data[0]['a_id'] }}">{{ $team_a_name }}</label>
                                                                        </div>
                                                                        <div class="radio">
                                                                                <input name="matchWinnerRadio" type="radio" value="{{ $match_data[0]['b_id'] }}" id="{{ $match_data[0]['b_id'] }}" <?php if (isset($match_data[0]['winner_id']) && $match_data[0]['winner_id'] == $match_data[0]['b_id']) { echo "checked=\"\""; } ?>>
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
                                                                                <option value="{{$a_key}}" <?php if (!empty($match_data[0]['player_of_the_match']) && $a_key == $match_data[0]['player_of_the_match']) { echo "selected"; } ?>>{{ $a_val }}</option>
                                                                                @endforeach
                                                                        @endif
                                                                        @if(!empty($team_b) && count($team_b)>0)
                                                                                @foreach($team_b as $b_key => $b_val)
                                                                                <?php if (empty($b_key)) continue; ?>
                                                                                <option value="{{$b_key}}" <?php if (!empty($match_data[0]['player_of_the_match']) && $b_key == $match_data[0]['player_of_the_match']) { echo "selected"; } ?>>{{ $b_val }}</option>
                                                                                @endforeach
                                                                        @endif
                                                                </select>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="button btn-primary end_match_btn_submit" onclick="SJ.SCORECARD.endMatch();">Done</button>
                                        <button type="button" class="button btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
                        </div>
                </div>
        </div>
</div>
<!-- Toss won by modal end -->