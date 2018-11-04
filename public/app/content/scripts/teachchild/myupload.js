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
<<<<<<< HEAD
			member_id: user_member_id,
=======
			member_id:user_member_id,
>>>>>>> c8d2bd1f2a128bd3848a1233feccd35d611e063d
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
					$.toast(response['message'],'forbidden');return false;
				};
			}
		})
	}

	// HTML模板
	function HTML(data){
		var template = '';
		var img = '';
		for(var i = 0; i < data.length; i++){
			if(data[i]['t_audit'] == 1){
				img = '../content/images/teachchild/1.png';//审核中
                template += '<div class="content_wrap" >';
			}else if(data[i]['t_audit'] == 2){
				img = '../content/images/teachchild/2.png';//失败
                template += '<div class="content_wrap" >';
			}else if(data[i]['t_audit'] == 3 && data[i]['t_price'] == 0 ){
				img = '../content/images/teachchild/3.png';//通过免费
                template += '<div class="content_wrap" onclick="videoClick('+data[i]['t_id']+')">';
			}else if(data[i]['t_audit'] == 3 && data[i]['t_price'] != 0 ){
                img = '../content/images/teachchild/4.png';//通过付费
                template += '<div class="content_wrap" onclick="videoClick('+data[i]['t_id']+')">';
            }else if(data[i]['t_audit'] == 4){
                img = '../content/images/teachchild/5.png';//下架
                template += '<div class="content_wrap" onclick="videoClick('+data[i]['t_id']+')">';
			}
            template += 	'<div class="img_wrap">'+
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
