function BOX_CONST()
{
    this.boxwidth  = 200;
    this.boxheight = 52;
    this.boxgap = 40;
}

var SvgCreator = new SvgCreatorLibrary();
var BOX_C = new BOX_CONST();
var info_text_box = null;
var baseX = 20;

function getCurrentDate()
{
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd<10){
        dd='0'+dd;
    } 
    if(mm<10){
        mm='0'+mm;
    } 
    var today = yyyy+'-'+mm+'-'+dd;
    return today;
}

//////////////// Red Rectangle SVG Object ////////////////

function InfoTextBox()
{
    this.svg_content = document.getElementById('SVG_CONTENT')
    this.svg_group = SvgCreator.CreateSvgGroup( "ShowTeamInfo" );
    this.svg_content.appendChild(this.svg_group);

    var pObject     = this.svg_group;
    this.topText     = SvgCreator.AddText( 0 , 14 , "Test1" , "rgb(255,255,255)" ,  'font-size: 14px; font-weight: bold','toptext'  );
    this.bottomText  = SvgCreator.AddText( 0 , 34 , "Test2" , "rgb(255,255,255)" ,  'font-size: 14px; font-weight: bold','bottomtext'  );

    pObject.appendChild( SvgCreator.AddRoundRect(0 , 0 , 5 , 5 , 180 , 40 ,"rgb(226,126,121)" , "rgb(226,126,121)"  ));
    pObject.appendChild( this.topText  );
    pObject.appendChild( this.bottomText  );
    this.svg_group.setAttributeNS( null , "style","display:none");
    

    this.MoveInformation = function( x , y , match_start_date , match_type , schedule_type )
    {
        x += baseX;
        var pObject = this.svg_group;
        this.svg_content.appendChild(pObject);
        this.topText.innerHTML      = ( match_start_date ? match_start_date : "") + " " + match_type;
        this.bottomText.innerHTML   = schedule_type;
        var box1 = this.topText.getBBox();
        var box2 = this.bottomText.getBBox();
        this.topText.setAttributeNS( null , "x" , 180/2 - box1.width / 2 );
        this.bottomText.setAttributeNS( null , "x" , 180/2 - box2.width / 2 );
        pObject.setAttributeNS( null, "style" , "display:" );
        pObject.setAttributeNS( null,"transform" ,"translate(" + x + "," + y + ")" );
    }
    this.Hide = function()
    {
        var pObject = this.svg_group;
        pObject.setAttributeNS(null , "style" , "display:none");
    }
}

var RoundName = ["Finals" , "Semi-Finals" , "Quarter-Finals"];

function AddColumnTitle( baseY , roundno )
{
    this.svg_content = document.getElementById('SVG_CONTENT');
    this.svg_group = SvgCreator.CreateSvgGroup( "ShowColumnInfo" );
    this.svg_content.appendChild(this.svg_group);
    var pObject     = this.svg_group;
    var x = baseX;;

    for( var i = roundno - 1 ; i>=0 ; i-- )
    {
        var j = i + 1;
        var col_name = i < 3 ? RoundName[i] : "ROUND " + j;
        console.log(col_name);
        var obj = SvgCreator.AddText( x , baseY + 30 , col_name  , "rgb(0,0,0)" ,  'font-size: 18px; font-weight: bold','matchtext'+i );
        pObject.appendChild( obj );
        var txt_box = obj.getBBox();
        obj.setAttributeNS( null , "x" , x + BOX_C.boxwidth/2 - txt_box.width/2 );
        
        x += BOX_C.boxwidth + BOX_C.boxgap;
    }
}
////////////////////////////////////////////////////////////////////////////////

function TeamLibrary(id)
{
    this.svg_content = document.getElementById('SVG_CONTENT')
    this.svg_group = SvgCreator.CreateSvgGroup( id );
    this.svg_content.appendChild(this.svg_group);


    this.setTextObjectPosition = function ( obj , cenx , ceny )
    {
        var bbox = obj.getBBox();
        var p={x:0 , y:0};
        p.x = cenx - ( bbox.width ) / 2;
        p.y = ceny + ( bbox.height  ) / 2 ;
        obj.setAttributeNS(null, "x" , p.x  );
        obj.setAttributeNS(null, "y" , p.y  );
        return p;
    }
     
    this.AddTeam = function ( x , y , goH , team1 , team2 , win  , I )
    {
        x += baseX;
        if( team1  && team1.length > 20 ) team1 = team1.substring( 0 , 20 );
        if( team2  && team2.length > 20 ) team2 = team2.substring( 0 , 20 );

        var mark1_col = "rgb(79,139,183)" , mark2_col = "rgb(79,139,183)";
        if( win )
            if( win == 1 ) mark1_col = "white";
            else mark2_col = "white";

        var pObject = this.svg_group;

        
        pObject.appendChild( SvgCreator.AddPolygon( "0,0 199,0 199,52 0,52" ,"rgb(204,204,204)" , "rgb(255,255,255)"  , null , "1" ) );
        pObject.appendChild( SvgCreator.AddPolygon( "30,1 198,1 198,51 30,51" ,"rgb(204,204,204)" , "rgb(250,250,250)"  , null , "0" ) );
        pObject.appendChild( SvgCreator.AddLine( 0   , BOX_C.boxheight / 2 , 199 , BOX_C.boxheight / 2 ,"stroke:rgb(227,227,227);stroke-width:1" ) );
        pObject.appendChild( SvgCreator.AddLine( 30 ,  0 , 30 , BOX_C.boxheight  ,"stroke:rgb(227,227,227);stroke-width:1" ) );
        

        if( team1 != null )
        {
            var xtext = SvgCreator.AddText( 0 , 0 , team1 , "rgb(119,119,119)" ,  'font-size: 14px; font-weight: bold','xtext'+id  );
            pObject.appendChild(xtext);
            this.setTextObjectPosition( xtext , 113 , 9 ); 
        }

        if( team2 != null )
        {
            var ytext = SvgCreator.AddText( 0 , 0 , team2 , "rgb(119,119,119)" ,  'font-size: 14px; font-weight: bold', 'ytext'+id  );
            pObject.appendChild(ytext);
            this.setTextObjectPosition( ytext , 113, 35 );
        }


        var imark = SvgCreator.AddCircle( BOX_C.boxwidth - 12 , -14 , 10 , 'RGB(144,155,204)', 'RGB(144,155,204)' , "imark" , null ,"cursor:pointer" )
        var imarktext = SvgCreator.AddText(   BOX_C.boxwidth - 14 , -8 , "i" , "rgb(255,255,255)" ,  'font-size: 14px; font-weight: bold;cursor:pointer', 'ymarktext')

        pObject.appendChild( imark );
        pObject.appendChild( imarktext ); 
        
        imark.addEventListener( "mouseover" , function (){
            info_text_box.MoveInformation( x+2 , y-3 , I['match_start_date'] , I['match_type'] , I['schedule_type'] );
        } );

        imarktext.addEventListener( "mouseover" , function (){
            info_text_box.MoveInformation( x+2 , y-3 , I['match_start_date'] , I['match_type'] , I['schedule_type'] );
        } );

        imark.addEventListener( "mouseleave" , function (){
            info_text_box.Hide();
        } );

        imarktext.addEventListener( "mouseleave" , function (){
            info_text_box.Hide();
        } );
 

   

        if( I['a_score'] != null )
        {
            var xmark;
            if( win == 1 )
                 xmark = SvgCreator.AddText( 0 , 0 , I['a_score'] , "rgb(221,117,40)" , null , 'xmark' + id  );
            else xmark = SvgCreator.AddText( 0 , 0 , I['a_score'] , "rgb(85,85,85)"   , null , 'xmark' + id  );
            pObject.appendChild(xmark);
            var xmarkbox = xmark.getBBox();
            xmark.setAttributeNS( null, "x" ,  15 - xmarkbox.width / 2 );
            xmark.setAttributeNS( null, "y" ,  8 + xmarkbox.height / 2 );
            

        }

        if( I['b_score'] != null )
        {
            var xmark;
            if( win == 2 )
                 xmark = SvgCreator.AddText( 0 , 0 , I['b_score'] , "rgb(221,117,40)" , null , 'ymark'+id  );
            else xmark = SvgCreator.AddText( 0 , 0 , I['b_score'] , "rgb(85,85,85)"   , null , 'ymark'+id  );
            pObject.appendChild(xmark);
            var xmarkbox = xmark.getBBox();
            xmark.setAttributeNS( null, "x" ,  15 - xmarkbox.width / 2 );
            xmark.setAttributeNS( null, "y" ,  30 + xmarkbox.height / 2 );
        }
 
        if( team1 != "Bye" && team2 != "Bye") // valid bucket
        {
            var today = getCurrentDate();
            var txt_but;
            var editFlag = false; 
            if( I['match_start_date'] <= today )
            {
                if( win )
                    txt_but = SvgCreator.AddText( 0 , 0 , 'Match Stats' , "rgb(124,190,127)", 'font-size: 14px; font-weight: bold' , 'add_edit' + id  );
                else
                    txt_but = SvgCreator.AddText( 0 , 0 , 'Add Score' ,"rgb(124,190,127)", 'font-size: 14px; font-weight: bold' , 'add_edit' + id  );
            }
            else {
                editFlag = true;
                txt_but = SvgCreator.AddText( 0 , 0 , 'Edit Schedule' ,"rgb(124,190,127)", 'font-size: 14px; font-weight: bold' , 'add_edit' + id  );
            }
            pObject.appendChild( txt_but );
            var txtbox = txt_but.getBBox(); 
            txt_but.setAttributeNS(null, "y" , -txtbox.height+10);

            if( I['id']  != null )
            {
                if( editFlag )
                {
                    txt_but.onclick = function() { editschedulegroupmatches( I['id']  , 1 , 0 ); }                    
                }
                else
                {
                    txt_but.onclick = function() { window.open( "/match/scorecard/edit/" + I['id']  , '_self' ); }
                }
                txt_but.style.cursor = 'pointer';
            }
        } 
        

        if( goH != null )
        {
            var x1 = BOX_C.boxwidth ;
            var y1 = BOX_C.boxheight / 2;

            if( goH == 0 )
            {
                var x2 = x1 + BOX_C.boxgap;
                pObject.appendChild( SvgCreator.AddLine(  x1 , y1 , x2 , y1  ,"stroke:rgb(0,172,237);stroke-width:1" ) );
            }
            else
            {
                var x2 = x1 + BOX_C.boxgap + BOX_C.boxwidth / 2;
                var y2 = y1 + goH ;
                pObject.appendChild( SvgCreator.AddLine(  x1 , y1 , x2 , y1  ,"stroke:rgb(0,172,237);stroke-width:1" ) );
                pObject.appendChild( SvgCreator.AddLine(  x1 , y1 , x2 , y1  ,"stroke:rgb(0,172,237);stroke-width:1" ) );
                pObject.appendChild( SvgCreator.AddLine(  x2 , y1 , x2 , y2  ,"stroke:rgb(0,172,237);stroke-width:1" ) ); 
            }
        }

        if( I['is_final_match'] == '1' && win > 0 )
        {
            var x1 = BOX_C.boxwidth + 50 ;
            var y1 = BOX_C.boxheight - 128;
            var gold_medal_team = win == 1 ? team1: team2;
            var silver_medal_team = win == 1 ? team2: team1;
            pObject.appendChild( SvgCreator.AddImage( x1 , y1 , 72 , 117       , '/images/medal1.png' , 'goldMedal' ) );
            pObject.appendChild( SvgCreator.AddImage( x1 , y1 + 128 , 72 , 117 , '/images/medal2.png' , 'silverMedal' ) );
            pObject.appendChild( SvgCreator.AddText( x1 + 85 , y1 + 50 , '1st: ' + gold_medal_team   , "rgb(0,0,0)" , 'font-size: 21px;', 'goldMedalText'   ) );
            pObject.appendChild( SvgCreator.AddText( x1 + 85 , y1 + 128 + 50 , '2nd: ' + silver_medal_team , "rgb(0,0,0)" , 'font-size: 21px;', 'silverMedalText' ) );

        }

        pObject.setAttribute('transform','translate(' + x + ',' + y + ')' );
    }
}




function BracketLibrary(id)
{
    var teamcount ;
    var level;
    var svg_content = document.getElementById('SVG_CONTENT');
    svg_content.setAttribute('transform','translate(' + 10 + ',' + 15 + ')' );
    this.remove_content = function()
    {
        while (svg_content.hasChildNodes()) {
            svg_content.removeChild(svg_content.lastChild);
        }
    }
     this.addMatch = function( baseY , i , j  , roundno , T1name , T2name , winner_id  , I )
     {
        var xx = ( BOX_C.boxwidth + BOX_C.boxgap ) * ( i - 1 ); // go right
        var ydelta = ( BOX_C.boxheight + BOX_C.boxgap ) * Math.pow( 2 , i - 1 );
        var yy = ydelta / 2 + ydelta * ( j - 1 );
        var B = new TeamLibrary( 'teambox'+ '_' + i + '_' + j );
        var goH = null;

        if( i < roundno ) 
                goH = ( ( j - 1 ) % 2 ) ?  0 - ydelta / 2 + BOX_C.boxheight / 2 - 1: ydelta / 2 - BOX_C.boxheight / 2;
        B.AddTeam( xx , yy + baseY , goH , T1name , T2name , winner_id  , I );
     }

     this.addMatchDouble = function( baseY , i , j  , roundno , T1name , T2name , winner_id, round_one_two_same , I )
     {
        var xx = ( BOX_C.boxwidth + BOX_C.boxgap ) * ( i - 1 ); // go right

        var ydelta = ( BOX_C.boxheight + BOX_C.boxgap ) * Math.pow( 2 , ( round_one_two_same ? Math.floor(( i - 1 ) / 2) : Math.floor(i / 2) ) );
        var yy = ydelta / 2 + ydelta * ( j - 1 );
        var B = new TeamLibrary( 'teambox'+ '_' + i + '_' + j );
        var goH = null;

        if( i < roundno ) 
            if( round_one_two_same == 1 )
            {
                if( i % 2 )  // 1,3,5 case 
                    goH = 0;
                else // init_round_count =  2
                    goH = ( ( j - 1 ) % 2 ) ?  0 - ydelta / 2 + BOX_C.boxheight / 2 - 1: ydelta / 2 - BOX_C.boxheight / 2;
            }
            else 
            {
                if( i % 2 )  // 1,3,5 case 
                    goH = ( ( j - 1 ) % 2 ) ?  0 - ydelta / 2 + BOX_C.boxheight / 2 - 1: ydelta / 2 - BOX_C.boxheight / 2;
                else // init_round_count =  2
                    goH = 0;
            } 

        B.AddTeam( xx , yy + baseY , goH , T1name , T2name , winner_id  , I );
     }



     this.generateSingleElimination = function( D , baseY , course ) // course 0:single , 1: double winner  2: double loser
     {

        if( !info_text_box )
            info_text_box =new InfoTextBox();

        var fRound = new Array();
        var sRound = new Array();
        var i , j , k;

        // D.roundno
        AddColumnTitle( baseY ,  D.roundno );

        baseY += 30;
        
        var NN = Math.pow( 2 , D.roundno - 1 );

        for( i = 1 ; i <= NN ; i++ )
        {
            fRound[i] = -1;
            sRound[i] = -1;
        }

        var round_one_count = 0 , round_two_count = 0;

        for( k = 0 ; k < D.units.length ; k++ )
        {
            i = D.units[k]['tournament_round_number'];
            if( i == 1 ) round_one_count++;
            if( i == 2 ) round_two_count++;
        }

   //     console.log( round_one_count , round_two_count );

        //console.log(D.units);

        if( D.units.length <= 2 )
            baseY += 117;

        for( k = 0 ; k < D.units.length ; k++ )
        {
            var T1name = D.units[k]['a_id'] > 0 ? D.units[k]['team_name_a'] : null;
            var T2name = D.units[k]['b_id'] > 0 ? D.units[k]['team_name_b'] : null;

            i = D.units[k]['tournament_round_number'];
            j = D.units[k]['tournament_match_number'];

            if( i == 1 ) fRound[j] = k;
            if( i == 2 ) sRound[j] = k;

            var win = null;
            if( T1name && T2name && D.units[k]['winner_id'] > 0 )
                win = D.units[k]['winner_id'] == D.units[k]['a_id'] ? 1 : 2; 
            if( course == 0 )
            {
                this.addMatch(  baseY , i , j  , D.roundno , T1name , T2name , win  , D.units[k] );
            }
            else 
            {
                if( course == 1 )
                    if( D.units[k]['tournament_round_number'] == D.roundno ) // special case : last round of winner course
                         this.addMatch(  baseY , i , j  , D.roundno , T1name , T2name , win  , D.units[k] );
                    else this.addMatch(  baseY , i , j  , D.roundno , T1name , T2name , win  , D.units[k]);
                else 
                    this.addMatchDouble( baseY , i , j  , D.roundno , T1name , T2name , win , round_one_count == round_two_count , D.units[k]);
            }
            
        }

        for( i = 1 ; i <= NN ; i++ ) // manual bye insert of first line
            if( fRound[i] == -1 )
            {
                var j = Math.ceil( i / 2 );
                var isTop = i % 2;
                if( round_one_count == round_two_count || sRound[j] == -1) continue;

                var T1name = isTop == 1 ? D.units[sRound[j]]['team_name_a'] : D.units[sRound[j]]['team_name_b'];
                
                this.addMatch(  baseY , 1 , i  , D.roundno , T1name , "Bye" );
            }
     }

     this.generateDoubleElimination = function( D )
     {
         console.log(D);
        var winY = ( BOX_C.boxheight + BOX_C.boxgap ) * ( Math.pow( 2 , D['w'].roundno - 2 ) ) + 100; // -1 means , winner course has one more game for last
        this.generateSingleElimination( D.w, 0 , 1 );
        this.generateSingleElimination( D.l, winY , 2 );
     }
    
    
}
 