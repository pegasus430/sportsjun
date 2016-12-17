    @foreach($match_obj->archery_rounds as $key=>$round)
                      <div class="row main_tour teams_search_display">
                        <div class="col-sm-12">

                      <div class="col-sm-2" style="padding:10px;background:#ffdddd;height:100%;font-size:25px" >
                       <center>   Round {{$key +1}}  </center>
                      </div>
                      <div class="col-sm-6">
                        <center>
                          Distance :<span class="text-primary"> {{$round->distance}} meters </span><br>
                          Number of Arrows : <span class="text-primary"> {{$round->number_of_arrows}}  </span>
                          </center>
                      </div>
                      <div class="col-sm-4">
                        <center>
                            <span style="font-size:25px"> {{$round->number_of_arrows}}0</span> <br>
                          Total round points
                        </center>
                      </div>
                      </div>
                  </div>
    @endforeach


    @if(count($match_obj->archery_rounds))
        <div class="row main_tour">
            <div class="pull-right">
                <button type="button" class="btn btn-info" onclick='start_scoring()'>Score Now</button>
            </div>
        </div>

    @endif