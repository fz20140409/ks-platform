/*
 * 会话模块 
 */

'use strict'

YX.fn.session = function() {

}
/**
 * 最近联系人显示
 * @return {void}
 */
YX.fn.buildSessions = function(id) {
	var _this=this
	var data = {
		sessions: this.cache.getSessions(),
		personSubscribes: this.cache.getPersonSubscribes()
	}
	if(!this.sessions) {
		var options = {
			data: data,
			onclickavatar: this.showInfo.bind(this),
			onclickitem: this.openChatBox.bind(this),
			infoprovider: this.infoProvider.bind(this),
		}
		this.sessions = new NIMUIKit.SessionList(options)
		this.sessions.inject($('#sessions').get(0))

	} else {
		//this.sessions.update(data)

		if(readCookie("uid") == "im59f2d38767463") {
			this.sessions.update(data,"&1")
		} else {
			this.sessions.update(data,"all")
		}
		var typeTab = $(".typeTab")
		var span = typeTab.find("span")
		span.on("click", function() {
			$(this).addClass("active").siblings().removeClass("active")
			if($(this).index()==0){
				_this.sessions.update(data,"&1")
			}else if($(this).index()==1){
				_this.sessions.update(data,"&2")
			}else if($(this).index()==2){
				_this.sessions.update(data,"&3")
			}
		})
	}
	//导航上加未读示例  
	this.showUnread()
	this.doPoint()
	//已读回执处理
	this.markMsgRead(id)
}

   	
// 导航上加未读数
YX.fn.showUnread = function() {
	var counts = $("#sessions .panel_count")
	this.totalUnread = 0
	if(counts.length !== 0) {
		if(this.totalUnread !== "99+") {
			for(var i = counts.length - 1; i >= 0; i--) {
				if($(counts[i]).text() === "99+") {
					this.totalUnread = "99+"
					break
				}
				this.totalUnread += parseInt($(counts[i]).text(), 10)
			}
		}
	}
	var $node = $(".m-unread .u-unread")
	$node.text(this.totalUnread)
	this.totalUnread ? $node.removeClass("hide") : $node.addClass("hide")
}