function BOX_CONST()
{
    this.boxwidth  = 200;
    this.boxheight = 44;
    this.boxgap = 20;
}

var SvgCreator = new SvgCreatorLibrary();
var BOX_C = new BOX_CONST();

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
     
    this.AddTeam = function ( x , y , goH , team1 , team2, linkid , mark1 , mark2 , win  )
    {  
        if( team1  && team1.length > 20 ) team1 = team1.substring( 0 , 20 );
        if( team2  && team2.length > 20 ) team2 = team2.substring( 0 , 20 );
        var mark1_col = "rgb(79,139,183)" , mark2_col = "rgb(79,139,183)";
        if( win )
            if( win == 1 ) mark1_col = "white";
            else mark2_col = "white";

        var pObject = this.svg_group;      
        
        pObject.appendChild( SvgCreator.AddPolygon( "0,0 38,0 16,22 0,22" , "rgb(131,187,216)" , mark1_col , null , 1 ) ) ;
        pObject.appendChild( SvgCreator.AddPolygon( "0,22 16,22 38,44 0,44" , "rgb(131,187,216)" , mark2_col , null , 1 ) ) ;

        var rect1 = SvgCreator.AddPolygon( "38,0  16,22 199,22 199,0" ,"rgb(36,121,159)" , "rgb(12,91,126)"  , null , 1 );
        var rect2 = SvgCreator.AddPolygon( "38,44 16,22 199,22 199,44" ,"rgb(36,121,159)" , "rgb(12,91,126)" , null , 1 );
        
        pObject.appendChild( rect1 );
        pObject.appendChild( rect2 );
        pObject.appendChild( SvgCreator.AddLine( 0 , 22 , 199 , 22 ,"stroke:rgb(5,65,97);stroke-width:1" ) );

        if( team1 != null )
        {
            var xtext = SvgCreator.AddText( 0 , 0 , team1 , "rgb(158,224,255)" , 'xtext'+id  );
            pObject.appendChild(xtext);
            this.setTextObjectPosition( xtext , 113 , 8 );
            if( linkid != null )
            {
                xtext.onclick = function() { window.open( "/match/scorecard/edit/" + linkid , '_self' ); }
                xtext.style.cursor='pointer';
            }
        }

        if( team2 != null )
        {
            var ytext = SvgCreator.AddText( 0 , 0 , team2 , "rgb(158,224,255)" , 'ytext'+id  );
            pObject.appendChild(ytext);
            this.setTextObjectPosition( ytext , 113, 29 );
            if( linkid != null )
            {
                ytext.onclick = function() { window.open( "/match/scorecard/edit/" + linkid , '_self' ); }
                ytext.style.cursor='pointer';
            }

        }

        if( mark1 != null )
        {
            var xmark = SvgCreator.AddText( 0 , 0 , mark1 , "rgb(0,34,50)" , 'xmark'+id  );
            pObject.appendChild(xmark);
            this.setTextObjectPosition( xmark , 11, 8 );
        }

        if( mark2 != null )
        {
            var ymark = SvgCreator.AddText( 0 , 0 , mark2 , "rgb(0,34,50)" , 'ymark'+id  );
            pObject.appendChild(ymark);
            this.setTextObjectPosition( ymark , 11, 30 );
        }

        

        if( goH != null )
        {
            var x1 = BOX_C.boxwidth ;
            var y1 = BOX_C.boxheight / 2;
            var x2 = x1 + BOX_C.boxgap + BOX_C.boxwidth / 2;
            var y2 = y1 + goH ;
            pObject.appendChild( SvgCreator.AddLine(  x1 , y1 , x2 , y1  ,"stroke:rgb(0,34,50);stroke-width:1" ) );
            pObject.appendChild( SvgCreator.AddLine(  x1 , y1 , x2 , y1  ,"stroke:rgb(0,34,50);stroke-width:1" ) );
            pObject.appendChild( SvgCreator.AddLine(  x2 , y1 , x2 , y2  ,"stroke:rgb(0,34,50);stroke-width:1" ) );

            pObject.appendChild( SvgCreator.AddCircle( x1 + 3 , y1 , 3  ,"rgb(0,34,50)" , "rgb(0,34,50)" , "circlemark" + id ) );
        }

        pObject.setAttribute('transform','translate(' + x + ',' + y + ')' );


        // setTextObjectPosition( ytext ,  );
        // setTextObjectPosition( mark1 , );
        // setTextObjectPosition( mark2 ,  );
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

     this.addMatch = function( i , j  , roundno , T1name , T2name , linkid )
     {
        var xx = ( BOX_C.boxwidth + BOX_C.boxgap ) * ( i - 1 ); // go right
        var ydelta = ( BOX_C.boxheight + BOX_C.boxgap ) * Math.pow( 2 , i - 1 );
        var yy = ydelta / 2 + ydelta * ( j - 1 );
        var B = new TeamLibrary( 'teambox'+ '_' + i + '_' + j );
        var goH = null;

        if( i < roundno ) 
                goH = ( ( j - 1 ) % 2 ) ?  0 - ydelta / 2 + BOX_C.boxheight / 2 - 1: ydelta / 2 - BOX_C.boxheight / 2;
        B.AddTeam( xx , yy , goH , T1name , T2name , linkid );
     }

     this.generateSingleElimination = function( D )
     {
        this.remove_content();       
        var fRound = new Array();
        var sRound = new Array();
        var i , j , k;

        var NN = Math.pow( 2 , D.roundno - 1 );

        for( i = 1 ; i <= NN ; i++ )
        {
            fRound[i] = -1;
            sRound[i] = -1;
        }

        for( k = 0 ; k < D.units.length ; k++ )
        {
            var T1name = D.units[k]['a_id'] > 0 ? D.units[k]['team_name_a'] : null;
            var T2name = D.units[k]['b_id'] > 0 ? D.units[k]['team_name_b'] : null;

            i = D.units[k]['tournament_round_number'];
            j = D.units[k]['tournament_match_number'];

            if( i == 1 ) fRound[j ] = k;
            if( i == 2 ) sRound[j ] = k;
            
            this.addMatch(  i , j  , D.roundno , T1name , T2name , D.units[k]['id'] );
            
        }

        for( i = 1 ; i <= NN ; i++ ) // manual bye insert of first line
            if( fRound[i] == -1 )
            {
                var j = Math.ceil( i / 2 );
                var isTop = i % 2;
                if(sRound[j] == -1) continue;

                var T1name = isTop == 1 ? D.units[sRound[j]]['team_name_a'] : D.units[sRound[j]]['team_name_b'];
                
                this.addMatch(  1 , i  , D.roundno , T1name , "Bye" , null );
            }
        
     }

     this.generateDoubleElimination = function( names )
     {
       // this.generateSingleElimination( names , true );
        // add one more match in the end
        // team numbers
        
     }
    
    
}