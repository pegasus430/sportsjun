<script src="{{ asset('/js/bracket/SvgCreatorLibrary.js') }}"></script>
<script src="{{ asset('/js/bracket/bracket.js') }}"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/asvd/dragscroll/master/dragscroll.js"></script>

<div class="dragscroll" style="width: 1200px; height: 700px; overflow: hidden; cursor: grab; cursor : -o-grab; cursor : -moz-grab; cursor : -webkit-grab; border:1px solid rgb(200,200,200);">
    <svg version="1.1"
        baseProfile="full"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        xmlns:ev="http://www.w3.org/2001/xml-events" id='SVG_CONTENT' width=2000 height=3000 style='background-color:rgb(255,255,255)'>
    </svg>
  </div>
</div>

<script>

var B = new BracketLibrary('br'); 

$(document).ready(function(){

    function update_bracket_table()
    {
        if('{{$tournament_type}}' == 'knockout' || '{{$tournament_type}}' == 'multistage')
        {
            $.ajax({
                type: 'GET',
                url: base_url + '/JsonOutputScheduleKnockout/{{$tournament_id}}',
                beforeSend: function() {
                    $.blockUI({width: '50px' , message: 'Generating Bracket' });
                },
                success: function(response) {
                    console.log(response);
                    $.unblockUI();
                    B.generateSingleElimination( response , 0 , 0 );
                }
            });
        }

        if( '{{$tournament_type}}' == 'doubleknockout'  || '{{$tournament_type}}' == 'doublemultistage' )
        {
            $.ajax({
                type: 'GET',
                url: base_url + '/JsonOutputScheduleKnockoutDouble/{{$tournament_id}}',
                beforeSend: function() {
                    $.blockUI({width: '50px' , message: 'Generating Bracket' });
                },
                success: function(response) {
                    console.log(response);
                    $.unblockUI();                
                    B.generateDoubleElimination( response);
                }
            });
        }
    }

    update_bracket_table();        

 
    $("#tournament_bracket_tab_btn").click( function(){
        B.remove_content();
        update_bracket_table();        
    });
});

</script> 