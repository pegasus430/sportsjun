<script src="{{ asset('/js/jquery-2.1.1.min.js') }}" type="text/javascript"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js" type="text/javascript"></script>
<script src="{{ asset('/js/bootstrap-switch.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/jquery-form.js')}}" type="text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="{{ asset('/js/bracket/SvgCreatorLibrary.js') }}"></script>
<script src="{{ asset('/js/bracket/bracket.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
<script src="/js/canvas/rgbcolor.js"></script>
<script src="/js/canvas/StackBlur.js"></script>
<script src="/js/canvas/canvg.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/asvd/dragscroll/master/dragscroll.js"></script>

<link href="{{ asset('/css/sportsform.css') }}" rel="stylesheet">
<link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('/css/bootstrap-datepicker.css') }}" rel="stylesheet">
<link href="{{ asset('/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('/css/bootstrap-switch.css') }}" rel="stylesheet">
<link href="{{ asset('/css/bootstrap-select.css') }}" rel="stylesheet">
<link href="{{ asset('/css/album-popup.css') }}" rel="stylesheet">
<link href="{{ asset('/css/marketplace-showdetails.css') }}" rel="stylesheet">
<link href="{{ asset('/css/green.css') }}" rel="stylesheet">
<link href="{{ asset('/css/_all.css') }}" rel="stylesheet">
<link href="{{ asset('/css/admin_album.css') }}" rel="stylesheet">
<link href="{{ asset('/css/aftab.css') }}" rel="stylesheet">
<link href="{{ asset('/css/sb-admin-2.css') }}" rel="stylesheet">
<!-- End Admin -->

@extends('home.layout')

@section('content')
<div class="sportsjun-forms">
  <div class="row" align='center'>
    &nbsp;
  </div>

  <div class="row" align='center'>
    <div class="col-sm-4">
      <h1>Bracket Generation</h1>
    </div>    
  </div>
  <div class="row">
    <div class="col-sm-2">
      &nbsp;
    </div>
    <div class="col-sm-4">
      <div class="section">
                <label class="form_label">{{  trans('message.tournament.fields.type') }} <span  class='required'>*</span></label>
                <label class="field prepend-icon">
                {!! Form::select('type',  config('constants.ENUM.TOURNAMENTS.TYPE'), null,array('class'=>'gui-input','id'=>'gametype')) !!}
       </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-2">
      &nbsp;
    </div>
    <div class="col-sm-4">
      <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.noofteams') }} <span  class='required'>*</span></label>
            <label class="field prepend-icon">
            <input class="form-control" name="noofteams" id='noofteams' value=""/>
       </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-2">
      &nbsp;
    </div>
    <div class="col-sm-4">
      <div class="section" align='right'>
            <button class="button btn-primary" id='GenerateBracket'>Create</button>
       </div>
    </div>
  </div>
</div>

<div class="dragscroll" style=" overflow: hidden; cursor: grab; cursor : -o-grab; cursor : -moz-grab; cursor : -webkit-grab; border:1px solid rgb(200,200,200); height:80%;">
      <svg version="1.1"
          baseProfile="full"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          xmlns:ev="http://www.w3.org/2001/xml-events" id='SVG_CONTENT' width=2500 height=3000 style='background-color:rgb(255,255,255)'>
      </svg>
  </div>

  
<script>

var B = new BracketLibrary('br'); 
BracketDemoMode = true;

$(function () {
    $("#GenerateBracket").click( function (){
        var req_url ;
        var noofteams = $("#noofteams").val();
        switch( $("#gametype").val() )
        {
            case 'knockout':
              req_url = '/generateDemoSingleElimination/'+ noofteams; 
              break;

            case 'doubleknockout':
              req_url = '/generateDemoDoubleElimination/'+ noofteams;
              break;
        }
        if( !noofteams || !req_url || noofteams <= 1 || noofteams >= 300 )
          return;

        $.ajax({
            type: 'GET',
            url: req_url,
            dataType: 'json', 
            success: function(response) { 
                if(response  ) 
                {
                    B.remove_content();
                    switch( $("#gametype").val() )
                    {
                      case 'knockout':
                        B.generateSingleElimination( response , 0 , 0 );
                        break;
                      case 'doubleknockout':
                        B.generateDoubleElimination( response );
                        break;
                    }
                    
                }
            }   
    });
    });
});
</script>  
@endsection
