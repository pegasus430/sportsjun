<div id="content-team" class="col-sm-8 tournament_profile">
    <div class="col-md-12">
        <div class="panel panel-default">
            <h4 class="panel-heading">Requests</h4>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#receive">Received</a></li>
                    <li><a data-toggle="tab" href="#sent">Sent</a></li>
                </ul>
                <div class="tab-content" style="padding: 15px; margin-top: 0;">
                    <div id="receive" class="tab-pane fade in active">
                        <div id="receivediv" class="viewmoreclass">
                            @if($received->total())
                                @include('common.requestsList',['items'=>$received,'flag'=>'in'])
                            @else
                                No Records.
                            @endif
                        </div>
                    </div>
                    <div id="sent" class="tab-pane fade">
                        <div id="sentdiv">
                            @if(count($sent))
                                @include('common.requestsList',['items'=>$sent])
                            @else
                                No Records.
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

