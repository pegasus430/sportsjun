    <div class="col-sm-12">
      <br>
        <p>
          <h3>Referees</h3>
       @foreach(ScoreCard::get_referees($match_data[0]['id']) as $referee)
          <div class="col-sm-4">
              <div class="col-sm-4">
                <div class="player_img">
                 {!! Helper::Images($referee->user->logo,'user_profile',array('height'=>75,'width'=>75 ) )
                               !!}
               </div>
              </div>
              <div class="col-sm-7">
                 <a href="/editsportprofile/{{$referee->user->id}}" class="text-primary"> {{$referee->user->name}} </a>
              </div>
          </div>
       @endforeach

       <br>
    </div>