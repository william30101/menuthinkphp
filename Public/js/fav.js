/**
 * +关注and取消关注JS组件
 * author Tony
 */
/*
 //该组件调用方式 <start>

 <a class="subassembly4fav" typeid="1" targetid="被关注用户id" href="javascript:;" hidefocus="true">关注</a>
 <script>

 window.onload = function(){
 $("body").trigger("loginStatusLoaded");
 var IsLogined = 1;//是否已经登录
 };

 $("body").bind("loginStatusLoaded", function (e) {
 $.fn.fav();//组件接口	获取状态并绑定操作
 $.fn.fav('status');//组件接口	只获取状态
 $.fn.fav('handle');//组件接口	只绑定操作
 });
 </script>
 //该组件调用方式 <end>
 */
var favDetailInfo;
(function($) {
	//关注组件函数
    var favMethods = {
        init:function(options) {
            $.fn.fav("bind");// 监听
        },
        bind:function(cssselector, callback) {
            if (!cssselector) {
                cssselector = ".subassembly4fav";
            }

            $(cssselector).live('click', function(e) {
                $.fn.fav("addfav", $(this));
            });
        },
        addfav:function(obj) {
            if (!IsLogined) {//如果没有登录
                login();
            } else {
                var targetId = obj.attr("targetid");
                var typeId = obj.attr("typeid");
                if(targetId){
					var onOff = obj.attr('onOff');
                    if (!obj.hasClass('already')) {
                        jQuery.post("/index.php/User/Friend/addFav", {// 关注
                            id : targetId,
                            typeId : typeId,
                            t : jQuery.now()
                        }, function(d) {
                            if (d.status==1) {// 成功
                                obj.addClass("already");
								obj.removeClass("attentIcon1");
								obj.addClass("attentIcon2");
                                obj.html("已关注");
								if(onOff != 'off'){
									confirm_popup("信息提示",d.msg+"<br/><br/>您可以在“我的情缘-<a href='/index.php/User' target='_blank'>我关注的人</a>”里快速找到TA。马上与TA联系，幸福由你把握！",function(){profileSayHi(targetId)},function(){sendmsg(targetId)},"打招呼","发消息");
								}
                            }else{
								alert_popup(d.msg,null,"关注TA");
							}
                        }, 'json');
                    }else{
						if(onOff != 'off'){
							confirm_popup("信息提示","您确定取消关注吗？",function(){
								jQuery.post("/index.php/User/Friend/cancelFav", {// 取消关注
									id : targetId,
									typeId : typeId,
									t : jQuery.now()
								}, function(d) {
									if (d.status) {// 成功
										obj.removeClass("already");
										obj.removeClass("attentIcon2");
										obj.addClass("attentIcon1");
										obj.html("关注Ta");
									}
								}, 'json');
							});
						}
                    }
                }else{
                    alert('无效的参数！');
                }
                return false;
            }
        },
        checkFavStyleStatus:function(obj) {
            jQuery(".subassembly4fav").each(
                function() {
                    if(favDetailInfo){
                        var targetId = jQuery(this).attr('targetid');
                        var text=jQuery(this).html();
                        if (favDetailInfo[obj.connect + targetId] == true
                            && !jQuery(this).hasClass('already')) {
							jQuery(this).removeClass("attentIcon1");
							jQuery(this).addClass("attentIcon2");
                            jQuery(this).addClass("already");
                            //jQuery(this).html("取消"+text);
                            jQuery(this).html("已关注");
                        }
                        jQuery(this).addClass("already_fav");//防止重复加载
                    }
                });

            var type = obj.getType;
            if(type == 'all'){
                $.fn.fav("init");
            }
        },
        getFavStatusInfo:function(obj) {
            var getType = obj.getType;
            if (IsLogined) {//登录后才可操作
                var targetIds=[],typeIds=[];
                jQuery(".subassembly4fav:not('.already_fav')").each(function(i) {
                    targetIds.push(jQuery(this).attr("targetid"));
                    typeIds.push(jQuery(this).attr("typeid"));
                });
                var targetIdsList=targetIds.join(","),typeIdsList=typeIds.join(",");
                $.post("/index.php/User/Friend/getFavStatus", {
                    t : new Date().getTime(),
                    ids : targetIdsList,
                    typeId : typeIdsList
                }, function(d) {
                    if(d != null){
                        if (d.status == true) {
                            favDetailInfo = d.result;
                            $.fn.fav("checkFavStyleStatus", {
                                connect : d.connect,
                                getType : getType
                            }, $(this));
                        }
                    }
                },'json');
            }else{//没有登录也要绑定关注按钮
                $.fn.fav("checkFavStyleStatus", {getType : getType});
            }
        },
        status:function(){//只获取状态
            $.fn.fav("getFavStatusInfo",{});
        },
        All:function(){//获取状态和绑定操作
            $.fn.fav("getFavStatusInfo",{getType : 'all'});
        },
        handle:function(){//只绑定操作
            $.fn.fav("init");
        }
    };
	
	//赞组件函数
	var praiseMethods = {
        init:function(options) {
            $.fn.praise("bind");// 监听
        },
        bind:function(cssselector, callback) {
            if (!cssselector) {
                cssselector = ".subassembly4praise";
            }

            $(cssselector).live('click', function(e) {
                $.fn.praise("addpraise", $(this));
            });
        },
        addpraise:function(obj) {
            if (!IsLogined) {//如果没有登录
                login();
            } else {
                var targetId = obj.attr("targetid");
                var typeId = obj.attr("typeid");
                if(targetId){
					var onOff = obj.attr('onOff');
                    if (!obj.hasClass('already')) {
                        jQuery.post("/index.php/Home/Dynamic/addPraise", {// 赞
                            id : targetId,
                            typeId : typeId,
                            t : jQuery.now()
                        }, function(d) {
                            if (d.status==1) {// 成功
								var num = parseInt(obj.find('span').html())+1;
                                obj.addClass("already");
                                obj.html("已赞(<span class='praiseNum'>"+num+"</span>)");
                            }else{
								alert_popup(d.msg,null,"心情");
							}
                        }, 'json');
                    }
					//else{
//						if(onOff != 'off'){
//							confirm_popup("信息提示","您确定取消赞吗？",function(){
//								jQuery.post("/index.php/Home/Dynamic/cancelPraise", {// 取消赞
//									id : targetId,
//									typeId : typeId,
//									t : jQuery.now()
//								}, function(d) {
//									if (d.status) {// 成功
//										obj.removeClass("already");
//										obj.html("赞");
//									}
//								}, 'json');
//							});
//						}
//                    }
                }else{
                    alert('无效的参数！');
                }
                return false;
            }
        },
        checkPraiseStyleStatus:function(obj) {
            jQuery(".subassembly4praise").each(
                function() {
                    if(praiseDetailInfo){
                        var targetId = jQuery(this).attr('targetid');
                        var text=jQuery(this).html();
                        if (praiseDetailInfo[obj.connect + targetId] == true
                            && !jQuery(this).hasClass('already')) {
                            jQuery(this).addClass("already");
                            //jQuery(this).html("取消"+text);
                            jQuery(this).html("已赞("+praiseNumDetailInfo[obj.connect + targetId]+")");
                        }
                        jQuery(this).addClass("already_praise");//防止重复加载
                    }
                });

            var type = obj.getType;
            if(type == 'all'){
                $.fn.praise("init");
            }
        },
        getPraiseStatusInfo:function(obj) {
            var getType = obj.getType;
            if (IsLogined) {//登录后才可操作
                var targetIds=[],typeIds=[];
                jQuery(".subassembly4praise:not('.already_praise')").each(function(i) {
                    targetIds.push(jQuery(this).attr("targetid"));
                    typeIds.push(jQuery(this).attr("typeid"));
                });
                var targetIdsList=targetIds.join(","),typeIdsList=typeIds.join(",");
                $.post("/index.php/Home/Dynamic/getPraiseStatus", {
                    t : new Date().getTime(),
                    ids : targetIdsList,
                    typeId : typeIdsList
                }, function(d) {
                    if(d != null){
                        if (d.status == true) {
                            praiseDetailInfo = d.result;
							praiseNumDetailInfo = d.praisenum;
                            $.fn.praise("checkPraiseStyleStatus", {
                                connect : d.connect,
                                getType : getType
                            }, $(this));
                        }
                    }
                },'json');
            }else{//没有登录也要绑定赞按钮
                $.fn.fav("checkPraiseStyleStatus", {getType : getType});
            }
        },
        status:function(){//只获取状态
            $.fn.praise("getPraiseStatusInfo",{});
        },
        All:function(){//获取状态和绑定操作
            $.fn.praise("getPraiseStatusInfo",{getType : 'all'});
        },
        handle:function(){//只绑定操作
            $.fn.praise("init");
        }
    };
	
	//调用关注
    $.fn.fav = function (method) {
        if (favMethods[method]) {
            return favMethods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return favMethods.All.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.tooltip');
        }
    };
	//调用赞
	$.fn.praise = function (method) {
        if (praiseMethods[method]) {
            return praiseMethods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return praiseMethods.All.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.tooltip');
        }
    };
})(jQuery);

/**
 *打招呼
 *author Tony
 */
function muchSayHi(userid){

	if(!loginStatus){
		login();
		return false;
	}
	var isMust = false;
	var msg = '';
	if(userid){
		if(checkInformationComplete(userid,'muchSayHi('+userid+')')){
			$.ajax({
				type: 'POST',
				async:false,
				url: '/index.php/Api/Public/ajaxGetMustCond',
				data: {'targetid':userid},
				dataType: 'json',
				success: function(data) {
					isMust = data.must;
					msg = data.msg;
				}
			});
			if(isMust==-1){
				login();
				return false;
			}
			if(isMust>0){
			   return alert_popup(msg,null,"打招呼"),void 0;
			
			}else{
				newSayHi(userid);
	//		   $.post('/index.php/Home/UserHome/getSayHiTpl',{'id':userid},function(html){
	//			   //$.blockUI(html);
	//			   alertHtmlTpl(html,50,40),void 0;			   
	//		   },'json');
			}
		}
	}
}

/**
 *发信息
 *author Tony
 */
function muchSendMsg(userid){
	if(!loginStatus){
		login();
		return false;
	}
	var isMust = false;
	var msg = '';
	if(userid){
		if(checkInformationComplete(userid,'muchSendMsg('+userid+')')){
			$.ajax({
				type: 'POST',
				async:false,
				url: '/index.php/Api/Public/ajaxGetMustCond',
				data: {'targetid':userid},
				dataType: 'json',
				success: function(data) {
					isMust = data.must;
					msg = data.msg;
				}
			});
			if(isMust==-1){
			   login();
			return false;
			
			}
			if(isMust>0){
			   return alert_popup(msg,null,"发信息"),void 0;
			
			}else{
			   window.location.href='/user/send-msg-'+userid+'.html';
			}
		}
	}
}
