jQuery.extend({
	
//自定义弹窗类
    warn: function(title,meg)
	{
		checkDiv();
		var html="<div class='modal modal-warn' style='display:none'><div class='modal-header'><button aria-hidden='true' data-dismiss='modal' class='close' type='button' onclick='$(\".modal\").fadeOut(500);clearDynamicHtml();'>×</button><h3>"+title+"</h3></div><div class='modal-body'> <p>"+meg+"</p></div><div class='modal-footer'><a class='btn' id='close' onclick='$(\".modal\").fadeOut(500);clearDynamicHtml();'>关闭</a></div></div>";

		$("#dynamic-html").append(html);
		$(".modal-warn").fadeIn(500);
    },
	table:function(title)
	{
		checkDiv();
		var html="<div class='modal modal-table' style='display:none;height:530px;'><div class='modal-header'><button aria-hidden='true' data-dismiss='modal' class='close' type='button' onclick='$(\".modal\").fadeOut(500);clearDynamicHtml();'>×</button><h3>"+title+"</h3></div><div class='modal-body' style='max-height:450px'><include file='Public:holiday_menu'/></div></div>";

		$("#dynamic-html").append(html);
		$(".modal-table").fadeIn(500);
	},
	dialog:function(title,url)
	{
		checkDiv();
		var html="<div class='modal modal-dialog' style='display:none;'><div class='modal-header'><button aria-hidden='true' data-dismiss='modal' class='close' type='button' onclick='$(\".modal\").fadeOut(500);clearDynamicHtml();'>×</button><h3>"+title+"</h3></div><div class='modal-body' style='max-height:540px'></div></div>";

		$("#dynamic-html").append(html);
		if(!url){
			$.warn('错误提示','非法操作');
			return;
		}
		$(".modal-body").load(url,function(){
			$(".modal-dialog").fadeIn(500);
		});
	},
	remind:function(title,meg)
	{
		checkDiv();
		var html="<div class='modal modal-remind' style='display:none;'><div class='modal-header'><h3>"+title+"</h3></div><div class='modal-body' style='max-height:460px'>"+meg+"</div></div>";

		$("#dynamic-html").append(html);
		$(".modal-remind").fadeIn(500).delay(1000).fadeOut(500,function(){
			clearDynamicHtml();
		});
	},
	loading:function(title){
		checkDiv();
		var html="<div class='modal modal-load' style='display:none;width:250px;left:100%;top:2%'><div class='modal-header' style='height:25px'><h5>"+title+"</h5></div><div class='modal-body'> <p align=center><img src='http://127.0.0.1/SMS/Public/img/load.gif'></p></div></div>";
		$("#dynamic-html").append(html);
		$(".modal-load").ajaxStart(function(){
			$(this).fadeIn(500);
		}).ajaxComplete(function(){
			$(this).fadeOut(500,function(){
				clearDynamicHtml();
			});
		});
	},
	dialoga:function(title,url)
	{
		var html="<div class='modal modal-dialoga' style='display:none;'><div class='modal-header'><button aria-hidden='true' data-dismiss='modal' class='close' type='button' onclick='$(\".modal-dialoga\").fadeOut(500);'>×</button><h3>"+title+"</h3></div><div class='modal-body' style='max-height:540px'></div></div>";

		$("#dynamic-html").append(html);
		if(!url){
			$.warn('错误提示','非法操作');
			return;
		}
		$(".modal-dialoga .modal-body").load(url,function(){
			$(".modal-dialoga").fadeIn(500);
		});
	},
	ajaxload:function(url){
	    $("#maincontent").css("display","none").load(url);
	    $(".loading").ajaxStart(function(){
	        $(this).fadeIn(100);
	    }).ajaxComplete(function(){
	        $(this).fadeOut(100,function(){
	            $("#maincontent").fadeIn(100);
	        })
	    });
	}
})
