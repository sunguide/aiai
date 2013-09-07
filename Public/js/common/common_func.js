//给输入号码框绑定键盘Enter时间，并判断电话号码是否符合要求
function keyboards_Enter(id,targetid){
	$("#"+id).keypress(function(e){
		var key = e.which;
		if(key == 13){
			var number = $(this).val();
			var pat = /^(13[0-9]{9})|(15[0-9]{9})|(18[0-9]{9})$/ ;
			if(number == ""){
				$.warn('警告','输入号码为空，请重新输入……');
				return;
			}
			var arr_num = number.split(',');
			var num_err = "";
			var num_rig = "";
			for(var i=0;i<arr_num.length;i++){
				var num_exist=pat.test(arr_num[i]);
				if(num_exist == false){
					var num_err = num_err+arr_num[i]+",";
					continue;
				}
				var num_rig = num_rig+arr_num[i]+",";
			}
			if(num_err != ""){
				$.warn('警告','输入有不合法的号码:'+num_err);
				$("#inputnumber").val(num_err);
				$("#"+targetid).append(num_rig);
				return;
			}
			$(this).val("");
			$("#"+targetid).append(number+",");
		}
	});
}
//点击短信库内容，后插入发送短信界面短信内容事件
function insertMegContent(content){
	$(".modal-table").fadeOut(500,function(){
		$("#sendcontent").val("").val(content);
		var length=$("#sendcontent").val().length;
		statWordNum('',length);
	});
	clearDynamicHtml();
}
//统计短信内容框里的字数
function statWordNum(attr,length){
	if(attr != ''){
		var length = $("#"+attr).val().length;
	}
	if(length>=0 && length<=70){
		$("#word-number i").css("color","green");
	}else if(length>70 && length<=200){
		$("#word-number i").css("color","#00BB00");
	}else if(length>200 && length<=210){
		$("#word-number i").css("color","red");
	}else{
		$("#sendcontent").val($("#sendcontent").val().substr(0,210));
		$("#word-number i").html("210");
		return;
	}
	$("#word-number i").html(length);
}
//我的通讯录里，事件：选中所有的联系人
function selectAllContact(){
	if($("#contact-delete-all").attr("checked") == "checked"){
		$("input[class='contact-delete']").attr("checked","checked");
		return;
	}
	$("input[class='contact-delete']").removeAttr("checked","checked");
}
//关闭对话框之后，清空div:id=dynamic-html中的
function clearDynamicHtml(){
	$("#dynamic-html").empty();
}


//更新我的位置(子节点)
function mySiteChild(parent,id){
	var child = $("#"+id+" span").html();
	$(".head_logo .user_meg .breadcrumb-meg").html("<li><span class='label'>首页</span><span class='divider'>/</span></li><li><span class='label'>"+parent+"</span><span class='divider'>/</span></li><li><span class='label'>"+child+"</span><span class='divider'>/</span></li>");
}

//获得手机的归属地信息
function getMobileMeg(url,mobile){
	load("正在查询中，请稍候……");
	$.getJSON(url,{mobile:mobile},function(json){
		if(json.status == 1){
			$("input[name='province']").val(json.data.province);
			$("input[name='corp']").val(json.data.corp);
		}else{
			$.warn("警告","输入手机号码有误！请重新输入……");
		}
	});
}
//控制content的加载不同的模板文件2013/9/1
function _MP(src,parent_name,child_id){
	//load("加载中……");
	mySiteChild(parent_name,child_id);
	$.ajaxload(src);
	//$("#maincontent").load(src);
}
//左侧子菜单的显示方式
function dis_menu(parent_id,child_id,time,value){
	$("#"+parent_id).click(function(){
		mySite(value);
		//将子菜单为block的，隐藏起来
		$(".child-menu").each(function(i){
			if($(this).css("display")=="block"){
				$(this).css("display","none");
				$(this).prev().removeClass("active").find("i").removeClass("icon-chevron-down").toggleClass("icon-chevron-right");
			}
		});
		//未选中项添加className:active
		if($("#"+parent_id).attr("class") == ""){
			$("#"+parent_id).toggleClass('active').find("i").removeClass("icon-chevron-right").toggleClass("icon-chevron-down");
		}
		var display=$("#"+child_id).css("display");
		if(display == "block"){
			$(this).css("display","none")
			return;
		}
		$("#"+child_id).css("display","block")
	});
}
//获取菜单的名字
function getName(site){
	return $("."+site).html();
}
//更新我的位置(父节点)
function mySite(value){
	$(".head_logo .user_meg .breadcrumb-meg").html("<li><span class='label'>首页</span><span class='divider'>/</span></li><li><span class='label'>"+value+"</span><span class='divider'>/</span></li>");
}
//判断是否存在控件div:id=dynamic-html
function checkDiv(){
	if($("#dynamic-html").length == 0){
		$('body').append("<div id='dynamic-html'></div>");
	}
}
//登陆与注册切换
function returnRegLog(id){
	if(id == 'reg'){
		$(".logo_form").fadeOut(500,function(){
			$(".reg_form").fadeIn(500);
		});
		return;
	}
	$(".reg_form").fadeOut(500,function(){
		$(".logo_form").fadeIn(500);
	});
}
//登陆
function login(url,target){
	var account = $("input[name='account']").val();
	var password = $("input[name='password']").val();
	$.getJSON(url,{account:account,password:password},function(json){
		if(json.status == 1){
			$.remind("提示信息","登陆成功，请稍后……");
			setTimeout(function(){window.location=target;},1000)
			
		}else if(json.status == 0){
			$.warn("提示信息","登陆失败，请重新输入……");
		}else{
			$.warn("错误信息","非法操作");
		}
	});
}
//注册
function reg(url){
	var account = $("input[name='reg-account']").val();
	var password = $("input[name='reg-password']").val();
	var repassword = $("input[name='repassword']").val();
	load("正在注册……");
	$.getJSON(url,{account:account,password:password,repassword:repassword},function(json){
		if(json.status == 1){
			$.remind("提示信息","注册成功");
		}else if(json.status == 0){
			$.warn("提示信息","注册失败，请重新输入……");
		}else{
			$.warn("错误信息","非法操作");
		}
	});
}
//检测用户名是否已被注册
function checkRegAccount(name,url){
	load("正在检测用户名是否已经注册……");
	$.getJSON(url,{account:name},function(json){
		$(".reg-account .popover-content").html(json.info);
		$(".reg-account .popover").fadeIn(500);
		if(json.status == 1){
			$(".reg").attr("disabled",true);
			return;
		}
		if(json.status == 0){
			$(".reg").attr("disabled",false);
		}
	});
}
//检测密码
function checkPwd(password){
	if(password == ""){
		$(".reg-password .popover-content").html("密码不能为空");
		$(".reg-password .popover").fadeIn(500);
		$(".reg").attr("disabled",true);
		return;
	}
	if($("input[name='repassword']").val()==""){
		if(password.length<6){
			$(".reg-password .popover-content").html("不能小于6位数");
			$(".reg-password .popover").fadeIn(500);
			$(".reg").attr("disabled",true);
			return;
		}
		$(".reg-password .popover-content").html("输入密码正确");
		$(".reg-password .popover").fadeIn(500);
		$(".reg").attr("disabled",false);
		return;
	}
	if($("input[name='repassword']").val() != password){
		$(".reg-password .popover-content").html("密码不一致");
		$(".reg-password .popover").fadeIn(500);
		$(".reg").attr("disabled",true);
		return;
	}
	$(".reg-password .popover-content").html("密码不一致");
	$(".reg-password .popover").fadeIn(500);
	$(".reg").attr("disabled",true);
}
function checkRePwd(password){
	if(password == ""){
		$(".reg-repassword .popover-content").html("密码不能为空");
		$(".reg-repassword .popover").fadeIn(500);
		$(".reg").attr("disabled",true);
		return;
	}
	if(password.length<6){
		$(".reg-repassword .popover-content").html("不能小于6位数");
		$(".reg-repassword .popover").fadeIn(500);
		$(".reg").attr("disabled",true);
		return;
	}
	if(password != $("input[name='reg-password']").val()){
		$(".reg-repassword .popover-content").html("密码不一致");
		$(".reg-repassword .popover").fadeIn(500);
		$(".reg").attr("disabled",true);
		return;
	}
	$(".reg-password .popover-content").html("输入密码正确");
	$(".reg-repassword .popover-content").html("输入密码正确");
	$(".repassword .popover").fadeIn(500);
			$(".reg").attr("disabled",false);
}
//ajax loading
function load(title){
	$.loading(title);
}
//导出通讯录事件
function exportContact(){
	var Str="";
	$('.contact-meg').each(function(i){
		if($(this).attr("checked") == "checked"){
			Str = Str + "1";
		}else{
			Str = Str + "0";
		}
	});
}

//导入通讯录事件
function importContact(url){
	load("导入中，请稍候……");
	$.ajaxFileUpload({
		url:url,
		secureuri:false,
		fileElementId:'import',
		dataType:'json',
		timeout:10000,
		success:function(data,status){
			alert(data.data);
		}
	});
}
//账户信息里点击编辑事件
function editAccountMeg(url){
	if($(".edit-account-meg").val() == "编辑"){
		$("input[name='account']").attr("disabled",false);
		$(".edit-account-meg").val("保存");
		return;
	}
	$.post(url,{company_name:getInputValueById('company_name'),company_contacter:getInputValueById('company_contacter'),contact_telphone:getInputValueById('contact_telphone'),contact_mobilephone:getInputValueById('contact_mobilephone'),industry:getInputValueById('industry'),customer_manager:getInputValueById('customer_manager'),company_fax:getInputValueById('company_fax'),zip_code:getInputValueById('zip_code'),email:getInputValueById('email'),company_address:getInputValueById('company_address')},function(json){
		if(json.status == 1){
			$.remind("提示",json.info);
		}else{
			$.warn("错误提示",json.info+",或数据没有更新");
		}
	});
}
//企业签名里点击编辑事件
function editComSignature(url){
	if($(".edit-signature").val() == "编辑"){
		$("input[name='signature']").attr('disabled',false);
		$(".edit-signature").val("保存");
		return;
	}
	$.getJSON(url,{signature:getInputValueByName('signature')},function(json){
		if(json.status == 1){
			$.remind("提示",json.info);
		}else{
			$.warn("错误警告",json.info);
		}
	});
}
//获取input中的value(根据name属性)
function getInputValueByName(name){
	var $name = name;
	return $("input[name='"+$name+"']").val();
}
//获取input中的value(根据id属性)
function getInputValueById(id){
	return $("#"+id).val();
}
//datepicker range
function datepickerRange(startId,endId){
	var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
   //  alert(nowTemp.getDate());
    var checkin = $('#'+startId).datepicker({
	format:'yyyy-mm-dd',
    onRender: function(date) {
 //   return date.valueOf() < now.valueOf() ? 'disabled' : '';
    }
    }).on('changeDate', function(ev) {
    if (ev.date.valueOf() > checkout.date.valueOf()) {
    var newDate = new Date(ev.date)
    newDate.setDate(newDate.getDate() + 1);
    checkout.setValue(newDate);
    }
    checkin.hide();
    $('#'+endId)[0].focus();
    }).data('datepicker');
    var checkout = $('#'+endId).datepicker({
	format:'yyyy-mm-dd',
    onRender: function(date) {
    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
    }
    }).on('changeDate', function(ev) {
    checkout.hide();
    }).data('datepicker');
}
//充值提交事件
function chargeOrder(url){
	$.post(url,{amount:getInputValueByName('amount'),remark:$("textarea[name='remark']").val()},function(json){
		if(json.status == 1){
			$.remind("提示信息",json.info);
		}else{
			$.warn("错误提示",json.info);
		}
	});
}
//添加通讯录分则
function addContactGrounp(url){
	$.dialog("添加分组",url);
}
//判断输入框的字符数
function countInputNum(values){
	return values.length;
}
function checkEmail(value){
    //对电子邮件的验证
	var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
	if(!myreg.test(value)){ 
		 return false;
	}else{
		return true;
	}
}