function isEmpty(s){ return /^\s*$/.test(s); }
if(!isEmpty(HDlogo)){$("#header .logo .link-logo").css("backgroundImage","url("+HDlogo+")")};
if(is_mobile){
	$("h1.entry-title a,h2.entry-title a").bind("touchstart touchend",function(e){
		if(e.type=='touchstart'){
			$(e.target).stop(true,true);
			$(e.target).css({'text-shadow':'#33B5E5 0px 0px 4px'},200);
		}else{
			$(e.target).stop(true,true);
			$(e.target).css({'text-shadow':'#35C5F5 0px 0px 10px'},200);
		}
	});
}else{
	$("h1.entry-title a,h2.entry-title a").bind("mouseover mouseout",function(e){
		if(e.type=='mouseover'){
			$(e.target).stop(true,true);
			$(e.target).css({'text-shadow':'#33B5E5 0px 0px 10px'},200);
		}else{
			$(e.target).stop(true,true);
			$(e.target).css({'text-shadow':'#35C5F5 0px 0px 20px'},200);
		}
	});
}
if(is_mobile){	$("h1.entry-title a,h2.entry-title a").css({'text-shadow':'#35C5F5 0px 0px 8px'},200);}
else{ 			$("h1.entry-title a,h2.entry-title a").css({'text-shadow':'#35C5F5 0px 0px 15px'},200);}
$("#btn-quicknav").toggle(function () {
        $("#header-wrap").addClass("quicknav");
        $("#quicknav").slideDown("normal",function () {init();fixsidebar();});
    },
    function () {
        $("#header-wrap").removeClass("quicknav");
        $("#quicknav").slideUp("normal",function () {init();fixsidebar();});
    }
);
function searchActive() {
    $("#search-container").addClass("active");
	$("#header-wrap").removeClass("quicknav");
    $("#quicknav").slideUp("normal",function () {init();fixsidebar();});
    $("#s").focus().blur(function () {
        $("#search-container").removeClass("active");
		$("#search_filtered").fadeOut();
    });
}
$("#search-container").mouseover(function () {
    searchActive();
});
function keydownSearch(e) {
    if (e.keyCode == 191) {
        e.preventDefault();
        searchActive();
    }
}
$(document).bind("keydown.search", function (e) {
    keydownSearch(e)
});
$("input,textarea").focus(function () {
    $(document).unbind("keydown.search");
}).blur(function () {
        $(document).bind("keydown.search", function (e) {
            keydownSearch(e)
        });
    });
function init() {
    win_height = $(window).height();
    primary_height = $('#primary').height();
    if($('#fixsidebar')[0]){
	        $('#fixsidebar').css({
                'position': 'relative',
                'left': 0,
                'top': 0
            });
        fixsidebar_top = $('#fixsidebar').offset().top;
        fixsidebar_left = $('#fixsidebar').offset().left;
        fixsidebar_height = $('#fixsidebar').height();
        has_fixsidebar=true;
    }else{
        has_fixsidebar=false;
	}
}init();

function fixsidebar() {
    if(has_fixsidebar&&($(window).width()>1200)){
	var h=fixsidebar_top-$(this).scrollTop()-$("#wpadminbar").height();
        if (h>0) {
            $('#fixsidebar').css({
                'position': 'relative',
                'left': 0,
                'top': 0
            });
			$('#fixsidebar ul li ul.blogroll li').css({
                'display': 'list-item',
            });
			$('#fixsidebar>ul').css({
                'max-height':'none'
            });
        } else {
			$('#fixsidebar').css({
                'position': 'fixed',
                'left': fixsidebar_left,
                'top': $("#wpadminbar").height(),
				'max-width': 360
            });
			$('#fixsidebar ul li ul.blogroll li').css({
                'display': 'inline-block'
            });
			$('#fixsidebar>ul').css({
                'max-height':win_height
            });
		}
	}
}fixsidebar();
$(window).resize(function () {init();fixsidebar();});
$(document).scroll(function () {fixsidebar();});
//smart-nav
function smartnav() {
    if (($("#side-nav").length > 0)&&($(window).width()>790)) {
        var side_nav_top = $("#side-nav").offset().top; // gotop button height
        $(document).scroll(function () {
            if ($(this).scrollTop() > side_nav_top) {
                $("#devdoc-nav").addClass("scroll-pane")
            } else {
                $("#devdoc-nav").removeClass("scroll-pane")
            }
        });
        $("#smart-nav").prepend($("#smart-nav-containter").text());
        $(".nav-section-header").click(function () {
            if ($(this).parent().hasClass("expanded")) {
                $(".nav-section").removeClass("expanded");
                $(".nav-section ul").slideUp();
            } else {
                $(".nav-section").removeClass("expanded");
                $(this).parent().addClass("expanded");
                $(".nav-section ul").slideUp();
                $(this).siblings("ul").stop().slideDown();
            }
        });
        if (!$(".nav-section").eq(0).find("li").size()) {
            $(".nav-section").eq(0).remove();
        }
        $(".nav-section-header").eq(0).click();
        if ($("#smart-nav-related li").size()) {
            $("#smart-nav-related").fadeIn()
        }
        $("#respond input,#respond textarea").attr("placeholder", function () {
            return $(this).siblings("label").text()
        });
    }
    if($(window).width()<=790){
	    $(".nav-section ul").slideDown();
        if (!$(".nav-section").eq(0).find("li").size()) {
            $(".nav-section").eq(0).remove();
        }
    }
}smartnav();
//search
function makeAjaxSearch(result) {
    if (result.length == 0) {
        $("#search_filtered").empty().show().append('<li><a href="javascript:vold(0)"><strong>404</strong></a></li>');
    } else {
        $("#search_filtered").empty().show();
        for (var i = 0; i < result.length - 1; i++) $("#search_filtered").append('<li><a href="' + result[i][1] + '">' + result[i][0] + '</a></li>');
    }
}
var delaySearch;
function startSearch() {
    $.ajax({
        type:"GET",
        url:home_url, //这玩意儿来自php,囧
        data:"s=" + $("#s").val(),
        dataType:'json',
        success:function (result) {
            makeAjaxSearch(result);
        }
    });
}
$("#s").keyup(function () {
    if ($("#s").val() != "") {
        if (delaySearch) {
            clearTimeout(delaySearch)
        }
        delaySearch = setTimeout(startSearch, 200);
    } else $("#search_filtered").fadeOut();
});


//ajax comments
$('#commentform').prepend('<div id="ajax-comment-info" ></div>');
$('#commentform').submit(function () {
    var infodiv = $('#ajax-comment-info');
    infodiv.fadeIn();
    //serialize and store form data in a variable
    var formdata = $('#commentform').serialize();
//    console.log(formdata);
    //Add a status message
    infodiv.html('<div class="ajax-progress">真的是AJAX提交数据中... ... 请耐心等待</div>');
    //Extract action URL from $('#commentform')
    var formurl = $('#commentform').attr('action');
    //Post Form with data
    $.ajax({
        type:'post',
        url:formurl,
        data:formdata,
        error:function (XMLHttpRequest, textStatus, errorThrown) {
            infodiv.html('<div class="ajax-error" >' + XMLHttpRequest.responseText.match(/<p>(.*?)<\/p>/g) + '<br>textStatus:'+ textStatus + '<br>errorThrown:'+errorThrown + '</div>');
        },
        success:function (data, textStatus) {
            if (data == "success") {
                infodiv.html('<div class="ajax-success" >评论成功. textStatus:'+textStatus+'</div>');
                $("#respond").before('<ul class="children"> <li class="comment"> <article class="comment"> <footer> <div class="comment-author vcard"> <strong>You</strong> <span class="says">said:</span></div> </footer> <div class="comment-content"><p>' + $('#commentform').find('textarea[name=comment]').val() + '</p> </div></article> </li> </ul> ');
                $('#commentform').find('textarea[name=comment]').val('');
                setTimeout(function () {
                    $("#ajax-comment-info").fadeOut();
                }, 3000);
            } else {
                infodiv.html('<div class="ajax-error" >服务器脑瘫,八成被 Akismet 君跳大,请歇会儿再试.</div>');
            }
        }
    });
    return false;
});


//thread comment
function moveEnd(id) {
    var obj = document.getElementById(id);
    obj.focus();
    var len = obj.value.length;
    if (document.selection) {
        var sel = obj.createTextRange();
        sel.moveStart('character', len);
        sel.collapse();
        sel.select();
    } else if (typeof obj.selectionStart == 'number' && typeof obj.selectionEnd == 'number') {
        obj.selectionStart = obj.selectionEnd = len;
    }
}
addComment = {moveForm:function (d, f, i, c) {
    var m = this, a, h = m.I(d), b = m.I(i), l = m.I("cancel-comment-reply-link"), j = m.I("comment_parent"), k = m.I("comment_post_ID");
    if (!h || !b || !l || !j) {
        return
    }
    m.respondId = i;
    c = c || false;
    if (!m.I("wp-temp-form-div")) {
        a = document.createElement("div");
        a.id = "wp-temp-form-div";
        a.style.display = "none";
        b.parentNode.insertBefore(a, b)
    }
    h.parentNode.insertBefore(b, h.nextSibling);
    if (k && c) {
        k.value = c
    }
    j.value = f;
    l.style.display = "";
    l.onclick = function () {
        var n = addComment, e = n.I("wp-temp-form-div"), o = n.I(n.respondId);
        if (!e || !o) {
            return
        }
        n.I("comment_parent").value = "0";
        e.parentNode.insertBefore(o, e);
        e.parentNode.removeChild(e);
        this.style.display = "none";
        this.onclick = null;
        return false
    };
    try {
        if ($("#comment").val() == "" || /@/.test($("#comment").val())) {//切换用户的时候@人也要变一下...
            $("#comment").val('@' + $("#" + d).find(".fn").eq(0).text() + ' ');
        }
        moveEnd("comment");
    } catch (g) {
    }
    return false
}, I:function (a) {
    return document.getElementById(a)
}};

$(".morehover").click(function () {
    $(this).toggleClass("hover")
});
$(".morehover").hover(function () {
    $(this).toggleClass("hover")
});