$(function(){

	// 默认加载审核中的内容
	getList(1);

	// 选项卡切换
	Tabs = function(type, el){
		if(!$(el).hasClass('action')){
			$('.myupload_tabs_wrap span').removeClass('action');
			$(el).addClass('action');
			getList(type)
		}
	}

	// 获取数据
	function getList(type){
		let params = {
			key: user_token,
			member_id:user_member_id,
			status: type
		}
		$.ajax({
			url: api + '/teacherchild/myUpload.html',
			type: 'POST',
			dataType: 'json',
			data: params,
			success: function(response){
				if(response['code'] == 200){
					$('.main_content').html(HTML(response['result']))
				}else {
					console.log(response['message']);
				};
			}
		})
	}

	// HTML模板
	function HTML(data){
		var template = '';
		var img = '';
		for(var i = 0; i < data.length; i++){
			if(data[i]['t_videoimg'] == 1){
				img = '../content/images/teachchild/1.png';
			}else if(data[i]['t_videoimg'] == 2){
				img = '../content/images/teachchild/2.png';
			}else {
				img = '../content/images/teachchild/3.png';
			}
			template += '<div class="content_wrap">'+
				'<div class="img_wrap">'+
					'<img class="img_top" src="'+ img +'">'+
					'<img src="'+ data[i]['t_videoimg'] +'" alt="'+ data[i]['t_url'] +'">'+
				'</div>'+
				'<h3 class="title">'+ data[i]['t_title'] +'</h3>'+
				'<p class="cont">'+ data[i]['t_profile'] +'</p>'+
			'</div>';
		}
		return template;
	}

})
