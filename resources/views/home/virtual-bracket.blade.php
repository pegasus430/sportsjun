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
    <div class="col-sm-3">
      <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.name') }} <span  class='required'>*</span></label>
            <label class="field prepend-icon">
            <input class="form-control" name="tournamentname" id='tournamentname' value=""/>
       </div>
    </div>
    <div class="col-sm-3">
      <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.type') }} <span  class='required'>*</span></label>
            <label class="field prepend-icon">
            {!! Form::select('type',  config('constants.ENUM.TOURNAMENTS.TYPE'), null,array('class'=>'gui-input','id'=>'gametype','style'=>'height:35px;')) !!}
       </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-2">
      &nbsp;
    </div>
    <div class="col-sm-3" id='row_noofteams'>
      <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.noofteams') }} <span  class='required'>*</span></label>
            <label class="field prepend-icon">
            <input class="form-control" name="noofteams" id='noofteams' value=""/>
       </div>
    </div>
    <div class="col-sm-3">
      <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.noofteamsfinal') }} <span  class='required'>*</span></label>
            <label class="field prepend-icon">
            <input class="form-control" name="noofteamsfinal" id='noofteamsfinal' value=""/>
       </div>
    </div>
  </div>
  <div class="row" id='row_group'>
    <div class="col-sm-2">
      &nbsp;
    </div>
    <div class="col-sm-3">
      <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.groups') }} <span  class='required'>*</span></label>
            <label class="field prepend-icon">
            <input class="form-control" name="noofgroups" id='noofgroups' value=""/>
       </div>
    </div>
    <div class="col-sm-3">
      <div class="section">
            <label class="form_label">{{  trans('message.tournament.fields.roundofplay') }} <span  class='required'>*</span></label>
            <label class="field prepend-icon">
            <input class="form-control" name="roundofplay" id='roundofplay' value=""/>
       </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-2">
      &nbsp;
    </div>
    <div class="col-sm-6">
      <div class="section" align='right'>
            <button class="button btn-primary" id='GenerateBracket'>Create</button>
            &nbsp;&nbsp;&nbsp;
            <button class="button btn-primary" id='ResetBracket'>Reset&nbsp;</button>
            &nbsp;&nbsp;&nbsp;
            <button class="button btn-primary" id='PrintBracket'>Export As PDF</button>
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
    $("#gametype").change( function(){
      switch( $("#gametype").val() )
      {
        case 'knockout':
        case 'doubleknockout': 
          $("#row_roundofplay").hide();
          $("#row_group").hide();
          $("#row_noofteams").hide();
          break;
        default:
          $("#row_roundofplay").show();
          $("#row_group").show();
          $("#row_noofteams").show();
        break;
      }
    });
    $("#ResetBracket").click( function(){
        $("#noofteams").val('');
        $("#noofgroups").val('');
        $("#roundofplay").val('');
        $("#noofteamsfinal").val('');
        $("#tournamentname").val('');
        
    });
    
      $("#PrintBracket").click( function (){
          $("#SVG_CONTENT").find('image').remove();
        
          var pdf = new jsPDF('p', 'pt', 'c1');
          var c = pdf.canvas;
          c.width = 1000;
          c.height = 500;

          var ctx = c.getContext('2d');
          ctx.ignoreClearRect = true;
          ctx.fillStyle = '#ffffff';
          ctx.fillRect(0, 0, 2000, 700);
          //load a svg snippet in the canvas with id = 'drawingArea'
          canvg(c, document.getElementById('SVG_CONTENT').outerHTML, {
              ignoreMouse: true,
              ignoreAnimation: true,
              ignoreDimensions: true
          });
          pdf.save( $("#tournamentname").val() + '.pdf' );
    } );

    $("#GenerateBracket").click( function (){
        var req_url ;
        var noofteams       = $("#noofteams").val();
        var noofgroups      = $("#noofgroups").val();
        var roundofplay     = $("#roundofplay").val();
        var noofteamsfinal  = $("#noofteamsfinal").val();

        if( noofteamsfinal < 3 && ( $("#gametype").val() == 'doubleknockout' || $("#gametype").val() == 'doublemultistage' ) )
        {
            return;
        }

        switch( $("#gametype").val() )
        {
            case 'knockout':
              if( !noofteamsfinal ) 
              {
                console.log( 'forbidden' );
                return;
              } 
              req_url = '/generateDemoSingleElimination/'+ noofteamsfinal; 
              break;

            case 'doubleknockout':
              if( !noofteamsfinal ) 
                {
                  console.log( 'forbidden' );
                  return;
                } 
              req_url = '/generateDemoDoubleElimination/'+ noofteamsfinal;
              break;

            default:
              if( !noofteamsfinal || !noofgroups || !roundofplay || !noofteams ) 
              {
                console.log( 'forbidden' );
                return;
              } 
              req_url = '/generateDemoLeagueMatch/'+ noofteams+'/'+noofgroups+'/'+roundofplay+'/';
              break;
        }
 
        var groupFlag = true;

        $.ajax({ type: 'GET', url:  '/footprintDemoBracketGeneration/'+ $("#tournamentname").val() + '/'+ $("#gametype").val() , dataType: 'json',  success: function(response) { } });
        


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
                          B.generateSingleElimination( response , 50 );
                          break;
                      case 'doubleknockout':
                          B.generateDoubleElimination( response , 50 );
                          break;
                      default: 
                        {
                            var baseY = B.generateLeagueMatch( response , 50 );
                        
                            if( $("#gametype").val() == 'multistage' )
                            {
                                $.ajax({ type: 'GET', url:  '/generateDemoSingleElimination/'+ noofteamsfinal , dataType: 'json',  success: function(response) { 
                                      B.generateSingleElimination( response , baseY , true );
                                    }
                                });
                            }
                            if( $("#gametype").val() == 'doublemultistage' )
                            {
                                $.ajax({ type: 'GET', url:  '/generateDemoDoubleElimination/'+ noofteamsfinal , dataType: 'json',  success: function(response) { 
                                      B.generateDoubleElimination( response , baseY , true );
                                    }
                                });
                            }
                            break;
                        }
                    }
                }
            }   
      });
    });
});
</script>  
@endsection
