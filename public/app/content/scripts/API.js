var api = 'http://vip.xiangjianhai.com:8001/index.php/Wap';

var upload_url = 'http://vip.xiangjianhai.com:8001/uploads';
//获取cookie中存储的token,member_id

var user_token = $.cookie('token');
var user_member_id = $.cookie('member_id');
if(user_token = ''){
     user_token = '069004d1531c2e3c9d1a50129bcd9cd6';
     user_member_id = 27;
}

