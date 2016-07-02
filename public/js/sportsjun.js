var textAreaLength = 250;
var tabPressed = false;
$(function() {
/*document.addEventListener('keydown', function (e) {
    if(e.keyCode == 9 || e.keyCode == 16) {
        tabPressed = true;   
    } else {
        tabPressed = false;
    }
}, false); */   
/*    if(jQuery.browser.mobile){
        $('html,body').animate({scrollTop: $("#content").offset().top},'slow');
    }*/
/*$("body").on("keyup", function(e) {
  console.log(e.which);
    if (e.which == 9) {
        if(e.preventDefault) {
            e.preventDefault();
        }
        return false;
    }
});    */
/*$('.glyphicon-calendar').parent().siblings('input:text').attr('readonly','readonly');
$('.glyphicon-calendar').parent().siblings('input:text').attr('readonly','readonly').keyup(function(e) {
    if(e.keyCode == 8 || e.keyCode == 46) {
        //$.datepicker._clearDate(this);
        $(this).val('');
    }
});*/
    $("#side-menu").metisMenu();
    $("#dob").datepicker({startDate: '-120y',autoclose: true});
/*    if($('[name=dob]').val() == '' || $('[name=dob]').val() == '0000-00-00'){
        $('#dob').datepicker('setDate', new Date(2000, 0, 1));
        $('#dob').datepicker('update');
        $('[name=dob]').val('');
    }*/
    //for bootstrap switch
    $('.bootstrap-switch-handle-on').html('Yes');
    $('.bootstrap-switch-handle-off').html('No');
    $('textarea').attr('maxLength',textAreaLength);
    $('#edit-form [name="about"]').attr('maxLength',500);
    $("textarea:not(.summernote)").each(function(){
        $(this).after('<div class="characterLeft"><span class="characterLefts">'+($(this).attr('maxLength')-$(this).val().length)+' </span> characters left</div>');
    });    
    $('a').each(function() {
      var href = $(this).attr('href');
      if(href == '#' || typeof href == 'undefined')
        $(this).attr('href', "javascript:void(0);");
    }); 
    $("input:not(.switch-class)").iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
        increaseArea: "20%"
    });

    if($("input[name=search_city]").length > 0){
    $("input[name=search_city]").autocomplete({
        source: "/search_cities",
        minLength: 2,
        select: function( event, ui ) {
        $( "input[name=search_city]" ).val( ui.item.label );
          $( "input[name=search_city_id]" ).val( ui.item.id );
        return false;
      }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li>").data("item.autocomplete", item).append("<a>" + item.label + "<br><strong>" + item.desc + "</strong></a>").appendTo(ul);
    };
    }    
});
//TeaxtArea length
$("textarea").on('keyup', function (event) {
//$('textarea').keyup(function() {
     var max = $(this).attr('maxLength');//textAreaLength;
    var len = $(this).val().length;
    if (len >= max) {
        //$(this).next('.characterLeft').children().text('you have reached the limit');
        $(this).next('.characterLeft').text('you have reached the limit');
    } else {
        var ch = max - len;
        //$(this).next('.characterLeft').children().text(ch);
        $(this).next('.characterLeft').html('<span class="characterLefts">'+ch+' </span> characters left');
    }
});
// End TeaxtArea length
var showLoader = true;

$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = this.window.innerWidth > 0 ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $("div.navbar-collapse").addClass("collapse");
            topOffset = 100;
        } else $("div.navbar-collapse").removeClass("collapse");
        height = (this.window.innerHeight > 0 ? this.window.innerHeight : this.screen.height) - 1;
        height -= topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) $("#page-wrapper").css("min-height", height + "px");
    });
    var a = window.location;
    var b = $("ul.nav a").filter(function() {
        return this.href == a || 0 == a.href.indexOf(this.href);
    }).addClass("active").parent().parent().addClass("in").parent();
    if (b.is("li")) b.addClass("active");
    $("[class^='deleteimage']").click(function() {
        var a = $(this).attr("class").split("_")[1];
        confirmDialog("Do you want to delete this pic?", "Confirmation", "deleteImage(" + a + ")");
    });
    $(document).ajaxStart(function() {
        if (showLoader) $.blockUI({
            width: "50px",
            message: $("#spinner").html()
        });
    });
    $(document).ajaxStop(function() {
        $("input:not(.switch-class)").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green",
            increaseArea: "20%"
        });
		
		$(".selectTeamClass").on('ifClicked', function (event) {
			clicked_value = this.value;
			groupId = $(this).attr('groupId');
			if(clicked_value=='all_teams')
			{
				$('#all_teams_div_'+groupId).show();
				$('#req_teams_div_'+groupId).hide();
                                $('#requested_teams_div').hide();
                                $('#auto_teams_div').show();
			}else
			{
				$('#req_teams_div_'+groupId).show();
				$('#all_teams_div_'+groupId).hide();
                                $('#requested_teams_div').show();
                                $('#auto_teams_div').hide();
			}
		});
		
        $.unblockUI();
        setHeight();
/*        $('.glyphicon-calendar').parent().siblings('input:text').attr('readonly','readonly');
        $('.glyphicon-calendar').parent().siblings('input:text').attr('readonly','readonly').keyup(function(e) {
            if(e.keyCode == 8 || e.keyCode == 46) {
                //$.datepicker._clearDate(this);
                $(this).val('');
            }
        });*/
    });
    $(document).ajaxComplete(function() {
        setHeight();
        $('.bootstrap-switch-handle-on').html('Yes');
        $('.bootstrap-switch-handle-off').html('No');
        $('a').each(function() {
          var href = $(this).attr('href');
          if(href == '#')
            $(this).attr('href', "javascript:void(0);");
        });        
    });
    $(document).ajaxError($.unblockUI);
    $(document).ajaxError(function(a, b, c, d) {
        var response = b.responseText;
        if(response == 'Unauthorized')
        {
//            window.location.href = base_url + '/auth/logout';
            $('#session-expired').modal({
                    show: true,
                    backdrop: 'static'
            });
        }
        if(typeof b.responseText != 'undefined'){
            var e = $.parseJSON(b.responseText);
            $.alert({
                title: "Alert!",
                content: e.error.message
            });
        }
    });
    $.ajaxPrefilter(function(a, b, c) {
        a.beforeSend = function(a) {
            a.setRequestHeader("X-CSRF-TOKEN", CSRF_TOKEN);
        };
    });
    $("#modal-carousel").carousel({
        interval: false
    });
    $("#modal-carousel").on("slid.bs.carousel", function() {
       // $(".modal-title").html($(this).find(".active img").attr("title"));
    });
    jQuery(document.body).on("click", ".view_gallery", function(a) {
        var page = $(this).attr("data-page");
        $.ajax({
            url: base_url + "/marketplace/showgallery/" + $(this).attr("data-pid"),
            type: "GET",
            dataType: "html",
            success: function(a) {
                var c = $(".carousel-inner");
                var d = $(".modal-title");
                c.empty();
                d.empty();
                $(".details1").remove();
                $(".contact1").remove();
                $(".showdeails").remove();
                $("#marketid").remove();
			    $("#itemname").remove();
				$(".contactnumber").remove();
				$(".showdet").remove();
                $(".glyphicon-chevron-right").show();
                $(".glyphicon-chevron-left").show();
                var e = $(a);
                var f = e.filter("#image-1").clone();
                var g = f.first();
                g.addClass("active");
				
			   
			   
			    // d.html(g.find("img").attr("title"));
				$(".modal-body").append($(a).filter("#itemname"));			
                d.html(	$("#itemname").show());
                c.append(f);
                if ($(".carousel-inner").is(":empty")) {
                    c.append("<div class='sj-alert sj-alert-info'>No Images Found.</div>");
                    $(".glyphicon-chevron-right").hide();
                    $(".glyphicon-chevron-left").hide();
                }
                $(".modal-body").append($(a).filter("#details"));
                $(".modal-body").append($(a).filter("#details_contact"));
				     			
                if ("myitems" != page) $(".modal-body .col-sm-4").append($("<span class='showdet'><strong>Contact Number</strong></span><div  class='btn btn-sj-primary btn-sm showdeails' onClick ='showDetails();' style='z-index: 3000; display: inline-block;' >View Phone No.</div>")); else $(".contact1").show();
                $(".modal-body").append($(a).filter("#marketid"));
				$(".modal-body").append($(a).filter(".contactnumber"));
                $("#modal-gallery").modal("show");
            }
        });
    });
    jQuery(document.body).on("click", ".likecount", function(a) {
        var b = $(this).attr("data-pid");
        $.ajax({
            url: base_url + "/Likecount",
            type: "POST",
            data: {
                id: b
            },
            success: function(a) {
                if ("success" == a.msg) {
						
                    var c = a.like_count;
                    $("#main_" + b).hide();
                    $("#like_" + b).hide();
                    $("#dislike_" + b).show();
			    	$("#likecountid_" + b).html(c);
				//	 $("#abc_" + b).show();
                  //  $("#c").html(c);
				
                }
            }
        });
    });
    jQuery(document.body).on("click", ".Dislike", function(a) {
        var b = $(this).attr("data-pid");
        $.ajax({
            url: base_url + "/Dislikecounts",
            type: "POST",
            data: {
                id: b
            },
            success: function(a) {
                if ("success" == a.msg) {
					// location.reload();
                    var c = a.like_count;
					 // $("#ab_" + b).show();
                    // $("#ab_" + b).html(c);
					$("#likecountid_" + b).html(c);
                    $("#main_" + b).hide();
                    $("#dislike_" + b).hide();
                    $("#like_" + b).show();
                }
            }
        });
    });
	
  
	  jQuery(document.body).on("click", ".removeAlbumPhoto", function(a) {
		
		var aid=$(this).attr("data-pid");
		var apage= $(this).attr("data-page");
		
		
		$.confirm({
			title: 'Confirmation',
			content: "Are you sure you want to delete?",            
			confirm: function() {
								$.ajax({
									url: base_url + "/gallery/deleteAlbumPhoto/" + aid + "/" +apage,
									type: "GET",
									//dataType: "JSON",
									success: function(a) {
										if(a.msg=="success")
										{
										 // location.reload();
											 $(".remove_li_"+aid).remove();
											  $.alert({
												title: "Alert!",
												content: "Deleted successfully."
											});
										}
										else{
											$.alert({
												title: "Alert!",
												content: "You are not authorized to delete this image."
											});
										}
									}
			 					});
			    }   
								
              });
	});
    jQuery(document.body).on("click", ".view_gallery_album", function(a) {
        var id = $(this).attr("data-pid");
        var c = $(".carousel-inner");
        c.find(".item").removeClass("active");        
		c.find("#image-"+id).addClass("active");
        $("#modal-gallery").modal("show");

    });
    jQuery(document.body).on("click", ".sold", function(a) {
        location.reload();
        $.ajax({
            url: base_url + "/marketplace/showavailableitems/" + $(this).attr("data-pid"),
            type: "GET",
            dataType: "html",
            success: function(a) {}
        });
    });
    jQuery(document.body).on("click", ".removephoto", function(a) {
    var itemid = $(this).attr("data-pid");
    $.confirm({
    title: 'Confirmation',
    content: "Are you sure you want to delete?",
    confirm: function() {
                   
                    $.ajax({
                        url: base_url + "/marketplace/deletephoto/" + itemid,
                        type: "GET",
                        dataType: "html",
                        success: function(a) {
                            $('.remove_li_'+itemid).remove();
                            $.alert({
                                title: "Alert!",
                                content: "Deleted successfully."
                            });
                        }
                    });
    }   
                    
    });


        
    });
});

$('.search_location').each(function() {
    var mydata = $(this).text().split(',').join(", ");
    $(this).text(mydata);
});

function checkIscityEmpty()
{
 if($('#sport').val() == '')
    return false;    
 var name=$( "#search_city").val();
 if(name=="")
 {
       $( "#search_city_id" ).val('');
        $( "#search_city" ).val('');
 }
          return true;
}

function showDetails() {
    $(".contact1").show();
    $(".showdeails").hide();
    $(".showdet").hide();
    var idval = $("#marketid").html();
    var b = "{{csrf_token()}}";
    $.ajax({
        url: base_url + "/marketplaceLog",
        type: "POST",
        data: {
            token: b,
            id: idval,
        },
        dataType: "json",
        success: function(a) {}
    });
}

function confirmDialog(text, title, confirm, cancel, confirmButton, cancelButton, post, confirmButtonClass, cancelButtonClass, dialogClass) {
    $.confirm({
        content: text,
        title: title,
        confirmButton:'Yes',
        cancelButton: 'No',
        confirm: function(button) {
            if (checkVariableDefined(confirm)) eval(confirm);
        },
        cancel: function(button) {
            if (checkVariableDefined(cancel)) eval(cancel);
        }
    });
}

function checkVariableDefined(a) {
    if ("undefined" !== typeof a) return true;
}

function deleteImage(a) {
	var i=a;
    $.ajax({
        url: base_url + "/deleteimage/" + a,
        type: "GET",
        data: {
            token: CSRF_TOKEN
        },
        dataType: "html",
        success: function(a) {
				
				 $(".remove_li_"+i).remove();
				  $.alert({
                                title: "Alert!",
                                content: "Deleted successfully."
                            });
          
            $("#question_div").html(a);
        }
    });
}


function appendTabElement(a, b, c, d) {
    var userSportCount = $('#userSportCount').val();
    if(userSportCount>7) {
        $.alert({
            title: "Alert!",
            content: 'You can add upto 8 teams only.'
        });
        return false;
    }
    $('<li class=""><a href="#addplayer_' + b + '" data-toggle="tab" aria-expanded="false" onclick="displaySportQuestions(\'unfollow\',' + b + "," + c + ",'" + d + "');\">" + d + "</a><span class='btn-tooltip' data-toggle='tooltip' data-placement='top' title='Remove "+d+"' onclick='removeUserStats(\"false\","+b+","+c+",\"follow\");'><i class='fa fa-remove'></i></span></li>").insertBefore("#unfollowedSportsLi");
    $("#sport_name_" + b).parent().remove();
    var e = $("#addplayer_" + b).wrap("<p/>").parent().html();
    $("#addPlayerDiv").append(e);
    $("#addplayer_" + b).unwrap();
    $("#addplayer_" + b).remove();
    displaySportQuestions(a, b, c, d);
}

function displaySportQuestions(a, b, c, d) {
    if (!b) return false;
    $.ajax({
        url: base_url + "/getquestions",
        type: "GET",
        data: {
            flag: a,
            sportsid: b,
            userId: c,
            viewflag: $("#user_question").val()
        },
        dataType: "html",
        beforeSend: function() {
            $.blockUI({
                width: "50px",
                message: $("#spinner").html()
            });
        },
        success: function(d) {
            $.unblockUI();
            if($.trim(d) == 'countexceed')
            {
                $.alert({
                    title: "Alert!",
                    content: 'You can add upto 8 teams only.'
                });
                return false;
            }else
            {    
                $(".question_div_class").html("");
                $(".custom_form").hide();
                $("#sportsjun_forms_" + b).show();
                $("#question_div_" + b).html(d);
                var userSportCount = $('#userSportCount').val();
                if ("follow" == a) {
                    if (1 == $("#user_question").val()) {
                        $("#sport_name_" + b).addClass("active");
                        $("#sport_name_" + b).attr("onclick", "displaySportQuestions('unfollow'," + b + "," + c + ")");
                    } else if (2 == $("#user_question").val()) {
                        $("#addplayer_" + b).show();
                        $('.nav-tabs a[href="#addplayer_' + b + '"]').tab("show");
                        userSportCount++;
                        $('#userSportCount').val(userSportCount);
                    }
                }
                suggestedWidget("teams", c, b, "player_to_team",'');
                suggestedWidget("tournaments", c, b, "player_to_tournament",'');
            }
        }
    });
}

function togglePlayerStatistic(a) {
    if (1 == a) {
        $("#overall_stats_div").hide();
        $("#player_info_div").show();
    } else {
        $("#player_info_div").hide();
        $("#overall_stats_div").show();
    }
}
$('#myModal,#myTeamsPopUpModal,#mainmatchschedule').on('hidden.bs.modal', function (e) {
  $(this)
    .find("input,select")
       .val('')
       .end()
    .find("textarea")
       .val('')
       .end()
    .find("input[type=checkbox], input[type=radio]")
       .prop("checked", "")
       .end()
    .find('.section')
      .removeClass('has-error')
       .end()
    .find(".error-help-block")
       .remove()
       .end();
});
function clearModal() {
    /*$("#myteam").val("");
    $("#my_team_id").val("");
    $("#oppteam").val("");
    $("#opp_team_id").val("");
    $("#match_start_date").val("");
    $("#match_start_time").val("");
    $("#venue").val("");
    $("#address").val("");
    $("#state_id").val("");
    $("#city_id").val("");
    $("#zip").val("");
    $("#facility_id").val("");
    $("#player_type").val("");
    $("#match_type").val("");
    $("#is_edit").val("");
    $("#schedule_id").val("");
    $(".error-help-block").closest(".section").removeClass("has-error");
    $(".error-help-block").remove();*/
}

function clearMainModal() {
    /*$("#main_myteam").val("");
    $("#main_my_team_id").val("");
    $("#main_oppteam").val("");
    $("#main_opp_team_id").val("");
    $("#main_match_start_date").val("");
    $("#main_match_start_time").val("");
    $("#main_venue").val("");
    $("#main_address").val("");
    $("#state_id").val("");
    $("#city_id").val("");
    $("#zip").val("");
    $("#main_facility_id").val("");
    $("#main_player_type").val("");
    $("#main_match_type").val("");
    $("#main_is_edit").val("");
    $("#main_schedule_id").val("");
    $(".error-help-block").closest(".section").removeClass("has-error");
    $(".error-help-block").remove();*/
}

$.widget("custom.catcomplete", $.ui.autocomplete, {
    _create: function() {
        this._super();
        this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
        this.widget().addClass("sj_globalsearch");
    },
    _renderMenu: function(a, b) {
        var c = this, d = "";
        $.each(b, function(b, c) {
            var e;
            if (c.category != d) {
                a.append("<h1 class='ui-autocomplete-category'>" + c.category + "</h1><hr>");
                d = c.category;
            }
            $("<li></li>").data("item.autocomplete", c).append("<a href='" + c.url + "'><div class='global_search_img'><img class='' height='24' width='24' src='" + c.image + "'/></div><div class='gloabal_search_label'>" + c.label + "</div></a>").appendTo(a);
        });
    }
});

$(function() {
    $(window).resize(function() {
        $(".top_nav_search").catcomplete("search");
    });
    $(".top_nav_search").catcomplete({
        delay: 0,
        minLength: 3,
        source: function(a, b) {
            $.ajax({
                url: base_url + "search/searchResultsByTypeAutoComplete",
                dataType: "json",
                global: false,
                data: {
                    term: a.term
                },
                success: function(a) {
                    b(a);
                }
            });
        },
        select: function(a, b) {
            window.location = b.item.url;
        }
    });
});

function loadSearchPage(a) {
    var b = $("#txtGlobalSearch").val();
    var c = "";
    switch (a) {
      case "person":
        c = "search?search=" + b + "#person/" + b + "/1";
        break;

      case "facility":
        c = "search?search=" + b + "#facility/" + b + "/1";
        break;

      case "organization":
        c = "search?search=" + b + "#organization/" + b + "/1";
        break;

      case "teams":
        c = "search?search=" + b + "#teams/" + b + "/1";
        break;

      case "torunaments":
        c = "search?search=" + b + "#tournaments/" + b + "/1";
    }
    if ("" != c) {
        $(".ui-autocomplete").hide();
        document.location = "/" + c;
    }
}

function viewMore(a, b) {
	
    $.ajax({
        url: b,
        type: "GET",
        data: a,
        dataType: "html",
        beforeSend: function() {
            $.blockUI({
                width: "50px",
                message: $("#spinner").html()
            });
        },
        success: function(a) {
			
            $.unblockUI();
            $(".viewmoreclass").append(a);
        }
    });
}

function editMatchSchedule(a, b, c, modal_id) {
     $(".modal-body #schedule_id").val(a);
     $(".modal-body #main_schedule_id").val(a);
    if ($(".qtip").length) $(".qtip").qtip("hide");
    var roundNumber = c;
    $.get(base_url + "/editteamschedule", {
        scheduleId: a,
        isOwner: b,
        roundNumber: roundNumber
    }, function(a, b, c) {
        
        if ("success" == b) {
            var d = c.responseText;
            var e = JSON.parse(d); 
            var f = "<option value=''>Select City</option>";
            $.each(e.cities, function(a, b) {
                f += "<option value='" + a + "'>" + b + "</option>";
            });
            $(".modal-body #city_id").html(f);
            if(modal_id == 'mainmatchschedule')
            {
                var h = "<option value=''>Select Match Type</option>";
                if(!$.isEmptyObject(e.scheduleData.sport_name))
                {
                    var sport_name = e.scheduleData.sport_name.toUpperCase();
                    $.each(e.match_types[sport_name], function(a, b) {
                        h += "<option value='" + a + "'>" + b + "</option>";
                    });
                }
                $(".modal-body #main_match_type").html(h);

                var g = "<option value=''>Select Player Type</option>";
                $.each(e.player_types, function(a, b) {
                    g += "<option value='" + a + "'>" + b + "</option>";
                });
                $(".modal-body #main_player_type").html(g);

                var j = "";
                if(e.scheduleData.schedule_type == 'player')
                {
                    j = "<option value=''>Select My Player</option>";
                    j += "<option value='"+e.scheduleData.a_id+"' selected>"+e.team_a_name+"</option>";
                    $(".modal-body #hid_schedule_type").show();
                }
                else
                {
                    j = "<option value=''>Select My Team</option>";
                    $.each(e.managing_teams, function(a, b) {
                        j += "<option value='" + b.id + "'>" + b.name + "</option>";
                    });                    
                }
                $(".modal-body #main_myteam").html(j);

                $(".modal-body #main_schedule_id").val(e.scheduleData.id);
                $(".modal-body #main_scheduletype").val(e.scheduleData.schedule_type);
                $(".modal-body #main_myteam").val(e.scheduleData.a_id);
                $(".modal-body #main_my_team_id").val(e.scheduleData.a_id);
                $(".modal-body #main_sports_id").val(e.scheduleData.sports_id);
                $(".modal-body #main_oppteam").val(e.team_b_name);
                $(".modal-body #main_opp_team_id").val(e.scheduleData.b_id);
                $(".modal-body #main_match_start_date").val(e.scheduleData.match_start_date);
                if(e.scheduleData.match_start_time!='' && e.scheduleData.match_start_time!='00:00:00') {
                    $(".modal-body #main_match_start_time").val(e.scheduleData.match_start_time);
                }
                $(".modal-body #main_venue").val(e.scheduleData.facility_name);
                $(".modal-body #main_facility_id").val(e.scheduleData.facility_id);
                $(".modal-body #main_player_type").val(e.scheduleData.match_category);
                $(".modal-body #main_match_type").val(e.scheduleData.match_type);
                $(".modal-body #main_is_edit").val(1);
            }
            else
            {  
                
                if(e.scheduleData.tournament_group_id!='' && e.scheduleData.tournament_group_id!=null) {
                    $(".modal-body #tournament_group_id").val(e.scheduleData.tournament_group_id);
                }    
                if(e.scheduleData.tournament_id!='' && e.scheduleData.tournament_id!=null) {
                    $(".modal-body #player_type").prop("disabled", true);
                    $(".modal-body #match_type").prop("disabled", true);
                    $(".modal-body #tournament_id").val(e.scheduleData.tournament_id);
                }
                $(".modal-body #schedule_id").val(e.scheduleData.id);
                $(".modal-body #scheduletype").val(e.scheduleData.schedule_type);
                $(".modal-body #myteam").val(e.team_a_name);
                $(".modal-body #my_team_id").val(e.scheduleData.a_id);
                $(".modal-body #sports_id").val(e.scheduleData.sports_id);
                $(".modal-body #oppteam").val(e.team_b_name);
                $(".modal-body #opp_team_id").val(e.scheduleData.b_id);
                $(".modal-body #match_start_date").val(e.scheduleData.match_start_date);
                if(e.scheduleData.match_start_time!='' && e.scheduleData.match_start_time!='00:00:00') {
                    $(".modal-body #match_start_time").val(e.scheduleData.match_start_time);
                }
                $(".modal-body #venue").val(e.scheduleData.facility_name);
                $(".modal-body #facility_id").val(e.scheduleData.facility_id);
                $(".modal-body #player_type").val(e.scheduleData.match_category);
                $(".modal-body #match_type").val(e.scheduleData.match_type);
                $(".modal-body #is_edit").val(1);               
                if(roundNumber!='') {
                    $("#byeDiv").show();
                    $("#byeTextDiv").hide();
                    $('#bye').prop("selectedIndex","0");
                    $(".modal-body #schedule_id").val(e.scheduleData.id);
                    $(".modal-body #myteam").attr("readonly", true); 
                    $(".modal-body #oppteam").attr("readonly", true); 
                    $(".modal-body #myteam, .modal-body #oppteam").focus(function(){
                        $(this).blur();
                    });
                    if(e.team_count<2) {
                        $("#oppTeamDiv").hide();
                        $("#matchStartDatediv").hide();
                        $("#matchStartTimeDiv").hide();
                        $('#bye').prop("selectedIndex","1");
                        var d = new Date();
                        var month = d.getMonth()+1;
                        var day = d.getDate();
                        var year = d.getFullYear();
                        var date = day+'-'+month+'-'+year;
                        $("#oppteam").val(1);
                        $("#match_start_date").val(date);
                        $('#bye').val(2);
                        $("#byeDiv").hide();
                        $("#byeTextDiv").show();
                        
                    }
                    if(roundNumber>1 && e.team_count>1) {
                        $("#byeDiv").hide();
                    }
                }
            }
            $(".modal-body #address").val(e.scheduleData.address);
            $(".modal-body #state_id").val(e.scheduleData.state_id);
            $(".modal-body #city_id").val(e.scheduleData.city_id);
            $(".modal-body #zip").val(e.scheduleData.zip);
            
             setTimeout(function(){ 
                    if(modal_id == 'mainmatchschedule')
                    {
                        $("#mainmatchschedule").modal();
                    }
                    else
                    {
                        $("#myModal").modal();    
                    }
             }, 500);
            
           
        }
    });
}

function showLoading(a, b, c) {
    if (!b) b = 99999;
    if (!c) c = "175px";
    var d = $("#spinner").html();
    $("#" + a).block({
        centerY: false,
        baseZ: b,
        css: {
            top: c,
            cursor: "default"
        },
        overlayCSS: {},
        message: d
    });
}

function hideLoading(a) {
    $("#" + a).unblock();
}

function generateteamsdiv(a, b, c, d, e) {
    var f = "";
    var req_type = b;
    $.post(base_url + "/team/getteams", {
        sport_id: a,
        req_type: b,
        player_tournament_id: c
    }, function(g, h) {
        if ("success" == h) f = g;
        var i = "";
        var j = "";
        var btn_text = 'Save';
        var no_result_text = 'No teams.';
        if(b == 'TEAM_TO_TOURNAMENT')
        {
            btn_text = 'Join';
            no_result_text = 'No teams to join.';
        }
        else if(b == 'TEAM_TO_PLAYER')
        {
            btn_text = 'Add';
            no_result_text = 'No teams to add.';
        }
        if (f.teams.length > 0) {
            // if(b == 'TEAM_TO_TOURNAMENT')
                list = 0;
            i += "<div id='div_teams'>";
            $.each(f.teams, function(a, b) {
                if(req_type == 'TEAM_TO_TOURNAMENT'){
                    if(b.team_status == 0){
                        i += "<div class='section colm colm6 pad-l40'><div class='option-group field'><label class='option block'><input type='checkbox' name='team[]' class='gui-input' value='" + b.id + "'><span class='checkbox'></span>" + b.name + "</label></div></div>";
                        list++;
                    }
                }else{
                    i += "<div class='section colm colm6 pad-l40'><div class='option-group field'><label class='option block'><input type='checkbox' name='team[]' class='gui-input' value='" + b.id + "'><span class='checkbox'></span>" + b.name + "</label></div></div>";
                    list++;
                }
            });
            if(b == 'TEAM_TO_TOURNAMENT'){
                if(list == 0){
                    i += 'Your teams have already joined the tournament';
                }
            }
            i += "</div>";
            if(list > 0)
            {
                j = "<button type='button' name='save_data' id='save_data' class='btn btn-default'>"+btn_text+"</button><button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
            }
        }
        else
        {
             i += "<div id='div_teams'>"+no_result_text+"</div>";
        }
        $("#myTeamsPopUpModal").modal();
        $(".modal-body #hid_flag").val(b);
        $(".modal-body #hid_val").val(c);
        $(".modal-body #hid_spid").val(a);
        if(b == 'TEAM_TO_PLAYER')
        {
            $(".modal-header .modal-title").html("Select teams for " + d);    
        }
        else
        {
            $(".modal-header .modal-title").html("Select your teams to join " + d);    
        }
        $(".modal-body #jsdiv").html(i);
        $(".modal-footer #footer_div").html(j);
        //Check the team checkbox if in teams page.
        if (window.location.href.indexOf("team/") > -1) {
            var href = window.location.href;
            var team_select = href.substr(href.lastIndexOf('/') + 1).match(/\d+/);
            $('#div_teams .gui-input[value='+team_select+']').iCheck('check');
        }                
    });
}

function suggestedWidget(a, b, c, d, g) {
    // alert(a+' b '+b+' c '+c+' d '+d+' g '+g);
    // return false;
    var e = base_url + "/search/suggestedWidget";
    var f = "type=" + a + "&type_id=" + b + "&sport_id=" + c + "&flag_type=" + d + "&city=" + g + "&_token=" + CSRF_TOKEN;
    showLoading("suggested_" + a, 500, "100%");
    $.ajax({
        type: "POST",
        data: f,
        url: e,
        global: false,
        success: function(b) {
            $("#suggested_" + a).html(b);
            hideLoading("suggested_" + a);
            setHeight();
        },
        error: function() {
            hideLoading("suggested_" + a);
            setHeight();
        }
    });
}

function displayStates(a) {
    if (!a) {
        $("#city_id").html("<option value=''>Select City</option>");
        return false;
    }
    $.ajax({
        url: base_url + "/getcities",
        type: "GET",
        data: {
            id: a
        },
        dataType: "json",
        beforeSend: function() {
            $.blockUI({
                width: "50px",
                message: $("#spinner").html()
            });
        },
        success: function(a) {
            $.unblockUI();
            var b = "<option value=''>Select City</option>";
            $.each(a, function(a, c) {
                b += "<option value='" + c["id"] + "'>" + c["city_name"] + "</option>";
            });
            $(".cities").each(function() {
                $(this).html(b);
            });
        }
    });
}

function displayCountries(a) {
    if (!a) {
        $("#country_id").html("<option value=''>Select Country</option>");
        return false;
    }
    $.ajax({
        url: base_url + "/getstates",
        type: "GET",
        data: {
            id: a
        },
        dataType: "json",
        beforeSend: function() {
            $.blockUI({
                width: "50px",
                message: $("#spinner").html()
            });
        },
        success: function(a) {
            $.unblockUI();
            var b = "<option value=''>Select State</option>";
            $.each(a, function(a, c) {
                b += "<option value='" + c["id"] + "'>" + c["state_name"] + "</option>";
            });
            $(".states").each(function() {
                $(this).html(b);
            });
        }
    });
}

function displayOrgGroups(a) {
    if (!a) {
        $("#organization_group_id").html("<option value=''>Select Group</option>");
        return false;
    }
    $.ajax({
        url: base_url + "/get_org_groups_list",
        type: "GET",
        data: {
            orgId: a
        },
        dataType: "json",
        beforeSend: function() {
            $.blockUI({
                width: "50px",
                message: $("#spinner").html()
            });
        },
        success: function(a) {
            $.unblockUI();
            var b = "<option value=''>Select Group</option>";
            $.each(a, function(a, c) {
                b += "<option value='" + c["id"] + "'>" + c["name"] + "</option>";
            });
            $(".org-groups").each(function() {
                $(this).html(b);
            });
        }
    });
}

function subTournamentEdit(id)
{
	 $.ajax({
        url: base_url + '/tournaments/subTournamentEdit',
        type: 'GET',
        data: {id: id},
        dataType: 'html',
        success: function(response) {
            $("#displaytournament").html(response);
           $("#editsubtournament").modal('show');
		    $('.modal .modal-body').css('overflow-y', 'auto'); 
    $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
        }
    });
}
$(window).load(function(){
    //$('form').dontJustLeaveMe();
    var selected = $('#service').find("option:selected").val();
    if(selected == 'marketplace'){
        $('#sportsList').hide();
        $('#marketplaceCategories').show();      
    }else{
        $('#marketplaceCategories').hide();      
        $('#sportsList').show();        
    }    
});
$('#service').on('change', function(){
    var selected = $(this).find("option:selected").val();	
    if(selected == 'marketplace'){
        $('#sportsList').hide();
        $('#marketplaceCategories').show();      
    }else{
        $('#marketplaceCategories').hide();      
        $('#sportsList').show();        
    }
});
$('.modal').on('show.bs.modal', function () {
    $('.alert').hide();
});
$('.modal').on('shown.bs.modal', function () {
    $("input:not(.switch-class)").iCheck({
        checkboxClass: "icheckbox_square-green",
        radioClass: "iradio_square-green",
        increaseArea: "20%"
    });    
    $('.modal .modal-body').css('overflow-y', 'auto'); 
    $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
    $('.modal .modal-body').animate({scrollTop:0},500);
});

$.fn.modal.prototype.constructor.Constructor.DEFAULTS.backdrop = 'static';
$.fn.modal.prototype.constructor.Constructor.DEFAULTS.keyboard = true;

//START OTP  functions
$('#sendOTP').on('click',function(){
    if (window.location.href.indexOf("marketplace/create") > -1 && $('[name=contact_number]').valid()) {  
	$('[name=verificationcode]').val('');	
        var data = {"mobileNumber": $('[name=contact_number]').val(),"time_token":$('[name=time_token]').val()};
        $.ajax({
            url: base_url + '/marketplace/generateOTP',
            type: 'POST',
            dataType: 'json',               
            data: data,
            success: function(response){
                if(response.message == 'OTP SENT SUCCESSFULLY'){
                    //$('#sendOTP').hide();
                    //$('#showVerification').show();
					$('#showVerification').modal('show');
                   // $('[name=contact_number]').attr('readonly','readonly');
                    //show textbox to enter the OTP
                }else{
                    $('#sendOTP').show();
                   // $('#showVerification').hide();
					$('#showVerification').modal('hide');
                    $.alert({
                        title: "Alert!",
                        content: response.message
                    });
                }
            },
            error: function(jqXHR, textStatus, ex) {
                //console.log(textStatus + "," + ex + "," + jqXHR.responseText);
            }
        });
    } 
});
//function verifyOTP()
//{
    $(document.body).on('click', '#verifyOTP' ,function(){ 
	//$('#verifyOTP').on('click',function(){
    if (window.location.href.indexOf("marketplace/create") > -1 && $('[name=contact_number]').valid()) {        
        var data = {"mobileNumber": $('[name=contact_number]').val(),"otp":$('[name=verificationcode]').val(),"time_token":$('[name=time_token]').val()};
        $.ajax({
            url: base_url + '/marketplace/verifyOTP',
            type: 'POST',
            dataType: 'json',               
            data: data,
            success: function(response){
                if(response.message == 'NUMBER VERIFIED SUCCESSFULLY'){
                    $('#sendOTP').hide();
					$('#verifiedOTP').css('display','block');

                    //$('#showVerification').hide();
					$('#showVerification').modal('hide');
					$('[name=contact_number]').attr('readonly','readonly');
					$.alert({
                        title: "Alert!",
                        content: response.message
                    });
					 $( "[name=marketplace_create]" ).submit();
                    //show textbox to enter the OTP
                }else{
                    $('#sendOTP').hide();
					$('#verifiedOTP').css('display','none');
                   // $('#showVerification').show();
				   $('#showVerification').modal('show');
                    $.alert({
                        title: "Alert!",
                        content: response.message
                    });
                }
            },
            error: function(jqXHR, textStatus, ex) {
                //console.log(textStatus + "," + ex + "," + jqXHR.responseText);
            }
        });
    } 
});
//}

//follow/unfollow functionlaity
$(document.body).on('click', '.follow_unfollow_team' ,function(){ 
    var id = $(this).attr('uid');
    var val = $(this).attr('val');
    var flag = $(this).attr('flag');
    follow_unfollow(id,val,flag);
});  


//follow/unfollow functionlaity
$(document.body).on('click', '.follow_unfollow_player' ,function(){ 
    var id = $(this).attr('uid');
    var val = $(this).attr('val');
    var flag = $(this).attr('flag');
    follow_unfollow(id,val,flag);
});  

//follow/unfollow functionlaity
$(document.body).on('click', '.follow_unfollow_tournament' ,function(){ 
    var id = $(this).attr('uid');
    var val = $(this).attr('val');
    var flag = $(this).attr('flag');
    follow_unfollow(id,val,flag);
});  

//function to insert/update follow/unfollow
function follow_unfollow(id,val,flag)
{
    var text = '';
    $.post(base_url+'/search/follow_unfollow',{id:id,val:val,flag:flag},function(response,status){
        if(status=='success' && response.status=='success')
        {
            var inner_val = val.toLowerCase();
            if(flag == 0)
            {
                text = 'Follow';
                $("#follow_unfollow_"+inner_val+"_"+id).attr("flag",1);
                $("#follow_unfollow_"+inner_val+"_a_"+id).removeClass("sj_unfollow");
                $("#follow_unfollow_"+inner_val+"_a_"+id).addClass("sj_follow");
                $("#follow_unfollow_"+inner_val+"_span_"+id).html("<i class='fa fa-check'></i>"+text);    
            }
            else
            {
                text = 'Unfollow';
                $("#follow_unfollow_"+inner_val+"_"+id).attr("flag",0);
                $("#follow_unfollow_"+inner_val+"_a_"+id).removeClass("sj_follow");
                $("#follow_unfollow_"+inner_val+"_a_"+id).addClass("sj_unfollow");
                $("#follow_unfollow_"+inner_val+"_span_"+id).html("<i class='fa fa-remove'></i>"+text);    
            }
            
        }
        
    });
    
}
//END


function autofillsubtournamentdetails(tournamentDetails) {
        $(".modal-body #player_type").val(tournamentDetails['player_type']);
        $(".modal-body #match_type").val(tournamentDetails['match_type']);
        $(".modal-body #address").val(tournamentDetails['address']);
        $(".modal-body #state_id").val(tournamentDetails['state_id']);
        $(".modal-body #zip").val(tournamentDetails['zip']);
        $(".modal-body #player_type").prop("disabled", true);
        $(".modal-body #match_type").prop("disabled", true);
        displayStates(tournamentDetails['state_id']);
        displayCountries(tournamentDetails['country_id']);

        if(scheduletype === "team")
        {
                $("#my_team").html('Select A Team');
                $("#opp_team").html('Select A Team');
        }
        else
        {
                $("#my_team").html('Select A Player');
                $("#opp_team").html('Select A Player');
        }
        setTimeout(function(){ $(".modal-body #city_id").val(tournamentDetails['city_id']); }, 2000);
}
function checkScoreEnterd(match_id)
{
	var succed = 0;
	var jqXHR = $.ajax({
            url: base_url + "/match/checkScoreEnterd",
            type: "POST",
			dataType: 'json',
			async:false,
            data: {
                match_id: match_id
            },
            success: function(response) {
                if (response.isvalid==0) {
					//var succed = 0;
					return false;
                }else
				{
					//var succed = 1;
					return true;
				}
            }
        });
		
jqXHR.done(function (result) {
	console.log(1);
    return result.isvalid;
}).fail(function () {
	console.log(2);
    return result.isvalid;
});		
		//return false;;
}