<script src="{{ asset('/js/bracket/SvgCreatorLibrary.js') }}"></script>
<script src="{{ asset('/js/bracket/bracket.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
<script src="/js/canvas/rgbcolor.js"></script>
<script src="/js/canvas/StackBlur.js"></script>
<script src="/js/canvas/canvg.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/asvd/dragscroll/master/dragscroll.js"></script>
<br>
<p align='right'><input type='button' class="button btn-primary" value='Export as PDF' onclick='export_pdf();'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<br>
<div class="dragscroll" style=" overflow: hidden; cursor: grab; cursor : -o-grab; cursor : -moz-grab; cursor : -webkit-grab; border:1px solid rgb(200,200,200);">
    <svg version="1.1"
        baseProfile="full"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        xmlns:ev="http://www.w3.org/2001/xml-events" id='SVG_CONTENT' width=2000 height=3000 style='background-color:rgb(255,255,255)'>
    </svg>
</div>
 

<script>

var B = new BracketLibrary('br'); 

function export_pdf()
{
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
    pdf.save('bracket_'+'{{$tournament_type}}'+'.pdf');
}

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