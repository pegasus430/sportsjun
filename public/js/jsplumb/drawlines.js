
jsPlumb.ready(function() {

   jsPlumb.ready(function () {

    var instance = window.jsp = jsPlumb.getInstance({
        // default drag options
        //DragOptions: { cursor: 'pointer', zIndex: 2000 },
        // the overlays to decorate each connection with.  note that the label overlay uses a function to generate the label text; in this
        // case it returns the 'labelText' member that we set on each connection in the 'init' method below.
        // ConnectionOverlays: [
        //     [ "Arrow", {
        //         location: 1,
        //         visible:true,
        //         id:"ARROW",
        //         events:{
        //             click:function() { alert("you clicked on the arrow overlay")}
        //         }
        //     } ],
        //     [ "Label", {
        //         location: 0.1,
        //         id: "label",
        //         cssClass: "aLabel",
        //         events:{
        //             tap:function() { alert("hey"); }
        //         }
        //     }]
        // ],
        Container: "canvas"
    });

    var basicType = {
        connector: ["Flowchart",{stub:10}],

        paintStyle: { strokeStyle: "red", lineWidth: 4 },
        hoverPaintStyle: { strokeStyle: "blue" },
        
    };
    instance.registerConnectionType("basic", basicType);

    // this is the paint style for the connecting lines..
    var connectorPaintStyle = {
            lineWidth: 1,
            strokeStyle: "#61B7CF",
            joinstyle: "round",
            outlineColor: "white",
            outlineWidth: .8
        },
    // .. and this is the hover style.
        connectorHoverStyle = {
            lineWidth: .4,
            strokeStyle: "#216477",
            outlineWidth: .8,
            outlineColor: "white"
        },
        endpointHoverStyle = {
            fillStyle: "#216477",
            strokeStyle: "#216477"
        },
    // the definition of source endpoints (the small blue ones)
        sourceEndpoint = {
            endpoint: "Dot",
            paintStyle: {
                strokeStyle: "#7AB02C",
                fillStyle: "transparent",
                radius: .1,
                lineWidth: .7
            },
            isSource: true,
            connector: [ "Flowchart", { stub: [1,1], gap: 1, cornerRadius: 5, alwaysRespectStubs: true } ],
            connectorStyle: connectorPaintStyle,
            hoverPaintStyle: endpointHoverStyle,
            connectorHoverStyle: connectorHoverStyle,
            dragOptions: {},
            overlays: [
                [ "Label", {
                    location: [0.5, 1.5],
                    label: "Drag",
                    cssClass: "endpointSourceLabel",
                    visible:false
                } ]
            ]
        },
    // the definition of target endpoints (will appear when the user drags a connection)
        targetEndpoint = {
            endpoint: "Dot",
            paintStyle: { fillStyle: "#7AB02C", radius: .1 },
            hoverPaintStyle: endpointHoverStyle,
            maxConnections: -1,
            dropOptions: { hoverClass: "hover", activeClass: "active" },
            isTarget: true,
            overlays: [
                [ "Label", { location: [0.5, -0.5], label: "Drop", cssClass: "endpointTargetLabel", visible:false } ]
            ]
        },
        init = function (connection) {
            connection.getOverlay("label").setLabel(connection.sourceId.substring(15) + "-" + connection.targetId.substring(15));
        };

    var _addEndpoints = function (toId, sourceAnchors, targetAnchors) {
        for (var i = 0; i < sourceAnchors.length; i++) {
            var sourceUUID = toId + sourceAnchors[i];
            instance.addEndpoint(toId, sourceEndpoint, {
                anchor: sourceAnchors[i], uuid: sourceUUID
            });
        }
        for (var j = 0; j < targetAnchors.length; j++) {
            var targetUUID = toId + targetAnchors[j];
            instance.addEndpoint(toId, targetEndpoint, { anchor: targetAnchors[j], uuid: targetUUID });
        }
    };

    // suspend drawing and initialise.
    instance.batch(function () {

       
            
            var teams_count=window.matches;
            var matches = Math.ceil(teams_count/2);

            var tours=Math.ceil(Math.log(teams_count,2)) + 2;
            console.log(tours);

            for(t=1; t<=tours; t++){

                for(m=1; m<=matches; m++){
                        console.log('matches - '+ matches);

                        if($("#tour_"+t+"_match_"+m).length){
                                console.log( 't - ' +t  + ' m - ' + m);

                            if(m%2==1){
                                _addEndpoints("tour_"+t+"_match_"+m, ["TopCenter", "BottomCenter"], ["LeftMiddle", "RightMiddle"]);
                            }
                            else{
                                _addEndpoints("tour_"+t+"_match_"+m, ["TopCenter", "BottomCenter"], ["LeftMiddle", "RightMiddle"]);
                            }
                        }

                }

                matches=Math.ceil(matches/2);
            }
        

        // _addEndpoints("tour_1_match_1", ["TopCenter", "BottomCenter"], ["LeftMiddle", "RightMiddle"]);
        // _addEndpoints("tour_1_match_2", ["LeftMiddle", "BottomCenter"], ["TopCenter", "RightMiddle"]);
        // _addEndpoints("tour_1_match_2", ["LeftMiddle", "BottomCenter"], ["TopCenter", "RightMiddle"]);
        // _addEndpoints("tour_1_match_2", ["LeftMiddle", "BottomCenter"], ["TopCenter", "RightMiddle"]);

        // _addEndpoints("tour_2_match_1", ["TopCenter", "BottomCenter"], ["RightMiddle", "TopCenter"]);
        // _addEndpoints("tour_1_match_4", ["LeftMiddle", "RightMiddle"], ["TopCenter", "BottomCenter"]);

        // listen for new connections; initialise them the same way we initialise the connections at startup.
        instance.bind("connection", function (connInfo, originalEvent) {
            init(connInfo.connection);
        });

        // make all the window divs draggable
        // instance.draggable(jsPlumb.getSelector(".flowchart-demo .window"), { grid: [20, 20] });
        // // THIS DEMO ONLY USES getSelector FOR CONVENIENCE. Use your library's appropriate selector
        // // method, or document.querySelectorAll:
        // //jsPlumb.draggable(document.querySelectorAll(".window"), { grid: [20, 20] });

        // connect a few up

          var teams_count=window.matches;
          var matches = Math.ceil(teams_count/2);

            var tours=Math.ceil(Math.log(matches,2)) + 2;

            for(t=1; t<=tours; t++){

                for(m=1; m<=matches; m++){
                     var cur_m=Math.ceil(m/2);
                    if($("#tour_"+t+"_match_"+cur_m).length){

                        console.log( ' cur_m ' + cur_m)
                        if(m%2==1){
                           instance.connect({uuids: ["tour_"+t+"_match_"+m+"RightMiddle", "tour_"+(t+1)+"_match_"+cur_m+"TopCenter"], editable: false});;
                        }
                        else{
                           instance.connect({uuids: ["tour_"+t+"_match_"+m+"RightMiddle", "tour_"+(t+1)+"_match_"+cur_m+"BottomCenter"], editable: false});
                        }

                        }

                    }

                matches=Math.ceil(matches/2);
            }

       
      
        // instance.connect({uuids: ["tour_1_match_1RightMiddle", "tour_2_match_1TopCenter"], editable: false});
        // instance.connect({uuids: ["tour_1_match_2RightMiddle", "tour_2_match_1BottomCenter"], editable: false});
        // instance.connect({uuids: ["tour_1_match_3RightMiddle", "tour_2_match_2TopCenter"], editable: false});
        // instance.connect({uuids: ["tour_1_match_4RightMiddle", "tour_2_match_2BottomCenter"], editable: false});

        // instance.connect({uuids: ["tour_2_match_1RightMiddle", "tour_3_match_1TopCenter"], editable: false});


        

        //
        // listen for clicks on connections, and offer to delete connections on click.
        //
        instance.bind("click", function (conn, originalEvent) {
           // if (confirm("Delete connection from " + conn.sourceId + " to " + conn.targetId + "?"))
             //   instance.detach(conn);
            conn.toggleType("basic");
        });

        instance.bind("connectionDrag", function (connection) {
            console.log("connection " + connection.id + " is being dragged. suspendedElement is ", connection.suspendedElement, " of type ", connection.suspendedElementType);
        });

        instance.bind("connectionDragStop", function (connection) {
            console.log("connection " + connection.id + " was dragged");
        });

        instance.bind("connectionMoved", function (params) {
            console.log("connection " + params.connection.id + " was moved");
        });
    });

    jsPlumb.fire("jsPlumbDemoLoaded", instance);

    $(window).resize(function(){
        instance.repaintEverything();
    });

});
})


