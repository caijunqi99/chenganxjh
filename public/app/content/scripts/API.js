var api = 'http://vip.xiangjianhai.com:8001/index.php/Wap';

var token = '8f2da8b131304055a274e0b17d33a029';

//获取cookie中存储的token,member_id
var user_token = $.cookie('token');
var user_member_id = $.cookie('member_id');
alert(user_token);alert(user_member_id);