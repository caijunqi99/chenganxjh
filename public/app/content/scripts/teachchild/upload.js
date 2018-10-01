$(function(){
	// 课程简介
	$("#jianjie").picker({
		title: "请选择",
		cols: [{
			textAlign: 'center',
			values: ['简介1', '简介2', '简介3']
		}],
		onChange: function(p, v, dv) {
			console.log(p, v, dv);
		},
		onClose: function(p, v, d) {
			console.log("close");
		}
	});
	// 选择适用的年纪
	$('#nianji').picker({
		title: "请选择适用的年级",
		cols: [{
			textAlign: 'center',
			values: ['一年级', '二年级', '三年级', '四年级', '五年级', '六年级']
		}],
		onChange: function(p, v, dv) {
			console.log(p, v, dv);
		},
		onClose: function(p, v, d) {
			console.log("close");
		}
	});
	// 选择科目
	$('#kemu').picker({
		title: "请选择适用的年级",
		cols: [{
			textAlign: 'center',
			values: ['英语', '语文', '数学']
		}],
		onChange: function(p, v, dv) {
			console.log(p, v, dv);
		},
		onClose: function(p, v, d) {
			console.log("close");
		}
	});
	// 选择价格
	$('#price').picker({
		title: "请选择适用的年级",
		cols: [{
			textAlign: 'center',
			values: ['100', '200', '300']
		}],
		onChange: function(p, v, dv) {
			console.log(p, v, dv);
		},
		onClose: function(p, v, d) {
			console.log("close");
		}
	})
})
