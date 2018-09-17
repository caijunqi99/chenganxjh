<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:100:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/home\view\default\mall\index\index.html";i:1514855480;s:91:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/home\view\public\mall_top.html";i:1514528299;s:94:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/home\view\public\mall_header.html";i:1514528299;s:94:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/home\view\public\mall_server.html";i:1514528299;s:94:"E:\phpStudy\PHPTutorial\WWW\chenganxjh\public/../application/home\view\public\mall_footer.html";i:1514857891;}*/ ?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo (isset($html_title) && ($html_title !== '')?$html_title:''); ?></title>
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <meta name="keywords" content="<?php echo (isset($seo_keywords) && ($seo_keywords !== '')?$seo_keywords:''); ?>" />
        <meta name="description" content="<?php echo (isset($seo_description) && ($seo_description !== '')?$seo_description:''); ?>" />
        <link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/home/css/common.css">
        <link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/home/css/home_header.css">
        <link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/home/css/home.css">
        <link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery-ui/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/font-awesome/css/font-awesome.min.css">
        <script>
            var SITE_URL = "<?php echo \think\Config::get('url_domain_root'); ?>";
            var SHOP_TEMPLATES_URL = "<?php echo \think\Config::get('url_domain_root'); ?>static/home/"
        </script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery-2.1.4.min.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.validate.min.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/home/js/common.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/common/js/member.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/home/js/compare.js"></script>
        <script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/perfect-scrollbar.min.js"></script>
    </head>
    <body>
        <!--showDialog 方法-->
        <div id="append_parent"></div>
        <div id="ajaxwaitid"></div>
        <div class="public-top">
            <div class="w1200">
                <span class="top-link">
                    您好，欢迎来到 <em><?php echo \think\Config::get('site_name'); ?></em>
                </span>
                <ul class="login-regin">
                    <?php if(\think\Session::get('member_id')): ?>
                    <li class="line"> <a href="<?php echo url('Home/Member/index'); ?>"><?php echo \think\Session::get('member_name'); ?></a></li>
                    <li> <a href="<?php echo url('Home/Login/Logout'); ?>">退出</a></li>
                    <?php else: ?>
                    <li class="line"> <a href="<?php echo url('/Home/Login/login'); ?>">请登录</a></li>
                    <li> <a href="<?php echo url('/Home/Login/register'); ?>">免费注册</a></li>
                    <?php endif; ?>
                </ul>
                <span class="top-link">
                    <strong style="margin-left:50px;">关注<a href="http://www.csdeshang.com" target="_blank">www.csdeshang.com</a> 持续更新</strong>
                    <a target="_blank" href="//shang.qq.com/wpa/qunwpa?idkey=f7ae47b14759703d9235bceceeecfd5d4ffbaa9d17fbcb82732c8ac4ee70b15b">
                        <img border="0" src="//pub.idqqimg.com/wpa/images/group.png" alt="DsMall开源商城官方群" title="DsMall开源商城官方群">
                    </a>
                </span>
                <ul class="quick_list">
                    <li>
                        <span class="outline"></span>
                        <span class="blank"></span>
                        <a href="<?php echo url('/Home/Member/index'); ?>">用户中心<b></b></a>
                        <div class="dropdown-menu">
                            <ol>
                                <li><a href="<?php echo url('Memberorder/index'); ?>">已买到的商品</a></li>
                                <li><a href="<?php echo url('Memberfavorites/fglist'); ?>">我关注的商品</a></li>
                                <li><a href="<?php echo url('Memberfavorites/fslist'); ?>">我关注的店铺</a></li>
                            </ol>
                        </div>
                    </li>
                    <li>
                        <span class="outline"></span>
                        <span class="blank"></span>
                        <a href="<?php echo url('/Home/Seller/index'); ?>">商家中心<b></b></a>
                        <div class="dropdown-menu">
                            <ol>
                                <li><a href="<?php echo url('Showjoinin/index'); ?>">商家入驻</a></li>
                                <li><a href="<?php echo url('Sellerlogin/login'); ?>">商家登录</a></li>
                            </ol>
                        </div>
                    </li>
                    <li>
                        <span class="outline"></span>
                        <span class="blank"></span>
                        <a href="<?php echo url('Memberfavorites/fglist'); ?>">我的收藏<b></b></a>
                        <div class="dropdown-menu">
                            <ol>
                                <li><a href="<?php echo url('Memberfavorites/fglist'); ?>">商品收藏</a></li>
                                <li><a href="<?php echo url('Memberfavorites/fslist'); ?>">店铺收藏</a></li>
                            </ol>
                        </div>
                    </li>
                    <li>
                        <span class="outline"></span>
                        <span class="blank"></span>
                        <a href="javascript:void(0)">客户中心<b></b></a>
                        <div class="dropdown-menu">
                            <ol>
                                <li><a href="<?php echo url('article/index',['ac_id'=>2]); ?>">帮助中心</a></li>
                                <li><a href="<?php echo url('article/index',['ac_id'=>5]); ?>">售后服务</a></li>
                                <li><a href="<?php echo url('article/index',['ac_id'=>6]); ?>">投诉中心</a></li>
                            </ol>
                        </div>
                    </li>
                    <li class="moblie-qrcode">
                        <span class="outline"></span>
                        <span class="blank"></span>
                        <a href="javascript:void(0)">微信端</a>
                        <div class="dropdown-menu">
                            <img src="<?php echo \think\Config::get('url_attach_common'); ?>/<?php echo \think\Config::get('site_logowx'); ?>" width="90" height="90" />
                        </div>
                    </li>
                    <li class="app-qrcode">
                        <span class="outline"></span>
                        <span class="blank"></span>
                        <a href="javascript:void(0)">APP</a>
                        <div class="dropdown-menu">
                            <img width="90" height="90" src="<?php echo \think\Config::get('url_attach_common'); ?>/<?php echo \think\Config::get('site_logowx'); ?>" />
                            <h3>扫描二维码</h3>
                            <p>下载手机客户端</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

<div class="header clearfix">
    <div class="w1200">
        <div class="logo">
            <a href="<?php echo \think\Config::get('url_domain_root'); ?>index.php"><img src="<?php echo \think\Config::get('url_attach_common'); ?>/<?php echo \think\Config::get('site_logo'); ?>"/></a>
        </div>
        <div class="top_search">
            <div class="top_search_box">
                <div id="search">
                    <ul class="tab">
                        <li act="store_list" class="current"><span>店铺</span><i class="arrow"></i></li>
                        <li act="store_list" style="display: none;"><span>商品</span></li>
                    </ul>
                </div>
                <div class="form_fields">
                    <form class="search-form" id="search-form" method="get" action="<?php echo url('/Home/Search/goods'); ?>">
                        <input placeholder="请输入您要搜索的店铺关键字" name="keyword" id="keyword" type="text" class="keyword" value="" maxlength="60" />
                        <input type="submit" id="button" value="搜索" class="submit">
                    </form>
                </div>
            </div>
        </div>
        <div class="user_menu">
            <dl class="my-mall">
                <dt><span class="ico"></span>我的商城<i class="arrow"></i></dt>
                <dd>
                    <div class="sub-title">
                        <h4></h4>
                        <a href="<?php echo url('/home/member/index'); ?>" class="arrow">我的用户中心<i></i></a>
                    </div>
                    <div class="user-centent-menu">
                        <ul>
                            <li><a href="<?php echo url('/home/membermessage/message'); ?>">站内消息(<span>0</span>)</a></li>
                            <li><a href="<?php echo url('/home/memberorder/index'); ?>" class="arrow">我的订单<i></i></a></li>
                            <li><a href="<?php echo url('/home/memberconsult/index'); ?>">咨询回复(<span id="member_consult">0</span>)</a></li>
                            <li><a href="<?php echo url('/home/memberfavorites/fglist'); ?>" class="arrow">我的收藏<i></i></a></li>
                            <li><a href="<?php echo url('/home/membervoucher/index'); ?>">代金券(<span id="member_voucher">0</span>)</a></li>
                            <li><a href="<?php echo url('/home/memberpoints/index'); ?>" class="arrow">我的积分<i></i></a></li>
                        </ul>
                    </div>
                    <div class="browse-history">
                        <div class="part-title">
                            <h4>最近浏览的商品</h4>
                            <span style="float:right;"><a href="<?php echo url('/home/membergoodsbrowse/listinfo'); ?>">全部浏览历史</a></span>
                        </div>
                        <ul>
                            <li class="no-goods"><img class="loading" src="<?php echo \think\Config::get('url_domain_root'); ?>static/home/images/loading.gif"></li>
                        </ul>
                    </div>
                </dd>
            </dl>
            <dl class="my-cart">
                <dt><span class="ico"></span>购物车结算<i class="arrow"></i></dt>
                <dd>
                    <div class="sub-title">
                        <h4>最新加入的商品</h4>
                    </div>
                    <div class="incart-goods-box">
                        <div class="incart-goods"><div class="no-order"><span>您的购物车中暂无商品，赶快选择心爱的商品吧！</span></div></div>
                    </div>
                    <div class="checkout"> <span class="total-price"></span><a href="<?php echo url('/home/Cart/index'); ?>" class="btn-cart">结算购物车中的商品</a> </div>
                </dd>
            </dl>
        </div>
    </div>
</div>


<div class="mall_nav">
    <div class="w1200">
        <div class="all_categorys">
            <div class="mt">
                <i></i>
                <h3><a href="<?php echo url('/home/Category/goods'); ?>">所有商品分类</a></h3>
            </div>
            <div class="mc">
                <ul class="menu">
                    <?php if (!empty($header_categories) && is_array($header_categories)) { $i = 0; foreach ($header_categories as $key => $val) { $i++; ?>
                    <li cat_id="<?php echo $val['gc_id'];?>" class="<?php echo $i%2==1 ? 'odd':'even';?>" <?php if($i>11){?>style="display:none;"<?php }?>>
                        <div class="class">
                            <span class="arrow"></span>
                            <span class="iconfont category-ico-<?php echo $i; ?>"></span>
                            <?php if(!empty($val['pic'])) { ?>
                            <span class="ico"><img src="<?php echo $val['pic'];?>"></span>
                            <?php } if (!empty($val['channel_id'])) {?>
                            <h4><a href="<?php echo url('/Home/Channel/index',['id'=>$val['channel_id']]); ?>"><?php echo $val['gc_name'];?></a></h4>
                            <?php }else{?>
                            <h4><a href="<?php echo url('/Home/Search/index',['cate_id'=>$val['gc_id']]); ?>"><?php echo $val['gc_name'];?></a></h4>
                            <?php } ?>
                        </div>
                        <div class="sub-class" cat_menu_id="<?php echo $val['gc_id'];?>">
                            <div class="sub-class-content">
                                <div class="recommend-class">
                                    <?php if (!empty($val['cn_classs']) && is_array($val['cn_classs'])) { foreach ($val['cn_classs'] as $k => $v) { ?>
                                    <span><a href="<?php echo url('/Home/Search/index',['cate_id'=>$v['gc_id']]); ?>" title="<?php echo $v['gc_name']; ?>"><?php echo $v['gc_name'];?></a></span>
                                    <?php } } ?>
                                </div>
                                <?php if (!empty($val['class2']) && is_array($val['class2'])) { foreach ($val['class2'] as $k => $v) { ?>
                                <dl>
                                    <dt>
                                    <?php if (!empty($v['channel_id'])) {?>
                                    <h3><a href="<?php echo url('/Home/Channel/index',['id'=>$v['channel_id']]); ?>"><?php echo $v['gc_name'];?></a></h3>
                                    <?php }else{?>
                                    <h3><a href="<?php echo url('/Home/Search/index',['cate_id'=>$v['gc_id']]); ?>"><?php echo $v['gc_name'];?></a></h3>
                                    <?php } ?>
                                    </dt>
                                    <dd class="goods-class">
                                        <?php if (!empty($v['class3']) && is_array($v['class3'])) { foreach ($v['class3'] as $k3 => $v3) { ?>
                                        <a href="<?php echo url('/Home/Search/index',['cate_id'=>$v3['gc_id']]); ?>"><?php echo $v3['gc_name'];?></a>
                                        <?php } } ?>
                                    </dd>
                                </dl>
                                <?php } } ?>
                            </div>
                            <div class="sub-class-right">
                                <?php if (!empty($val['cn_brands'])) {?>
                                <div class="brands-list">
                                    <ul>
                                        <?php foreach ($val['cn_brands'] as $brand) {?>
                                        <li> <a href="<?php echo url('/Home/Brand/list',['brand'=>$brand['brand_id']]); ?>" title="<?php echo $brand['brand_name'];?>"><?php if ($brand['brand_pic'] != '') {?><img src="<?php echo brandImage($brand['brand_pic']);?>"/><?php }?>
                                                <span><?php echo $brand['brand_name'];?></span>
                                            </a></li>
                                        <?php }?>
                                    </ul>
                                </div>
                                <?php }?>
                                <div class="adv-promotions">
                                    <?php if(isset($val['cn_adv1']) && $val['cn_adv1'] != '') { ?>
                                    <a <?php echo $val['cn_adv1_link'] == '' ? 'href="javascript:;"' : 'target="_blank" href="'.$val['cn_adv1_link'].'"';?>><img src="<?php echo $val['cn_adv1'];?>" data-url="<?php echo $val['cn_adv1'];?>" class="scrollLoading" /></a>
                                    <?php } if(isset($val['cn_adv2']) && $val['cn_adv2'] != '') { ?>
                                    <a <?php echo $val['cn_adv2_link'] == '' ? 'href="javascript:;"' : 'target="_blank" href="'.$val['cn_adv2_link'].'"';?>><img src="<?php echo $val['cn_adv2'];?>" data-url="<?php echo $val['cn_adv2'];?>" class="scrollLoading" /></a>
                                    <?php } ?></div>
                            </div>
                        </div>
                    </li>
                    <?php } } ?>
                </ul>
            </div>
        </div>
        <ul class="site_menu">
            <li><a href="<?php echo url('/Home/Index/index'); ?>" class="current">首页</a></li>
            <?php foreach($navs['middle'] as $nav): ?>
            <li><a href="<?php echo $nav['nav_url']; ?>"><?php echo $nav['nav_title']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<!--左侧导航栏-->

<div id="vToolbar" class="ds-appbar">
    <div class="ds-appbar-tabs" id="appBarTabs">
        <?php if(\think\Session::get('is_login')): ?>
        <div class="user" nctype="a-barUserInfo">
            <div class="avatar"><img src="<?php echo getMemberAvatar(\think\Session::get('avatar')); ?>"/></div>
            <p>我</p>
        </div>
        <div class="user-info" nctype="barUserInfo" style="display:none;"><i class="arrow"></i>
            <div class="avatar"><img src="<?php echo getMemberAvatar(\think\Session::get('avatar')); ?>"/>
                <div class="frame"></div>
            </div>
            <dl>
                <dt>Hi, <?php echo \think\Session::get('member_name'); ?></dt>
                <dd>当前等级：<strong nctype="barMemberGrade"><?php echo \think\Session::get('level_name'); ?></strong></dd>
                <dd>当前经验值：<strong nctype="barMemberExp"><?php echo \think\Session::get('member_exppoints'); ?></strong></dd>
            </dl>
        </div>
       <?php else: ?>
        <div class="user TA_delay" nctype="a-barLoginBox">
            <div class="avatar TA"><img src="<?php echo getMemberAvatar(\think\Session::get('avatar')); ?>"/></div>
            <p class="TA">未登录</p>
        </div>
        <div class="user-login-box" nctype="barLoginBox" style="display:none;"> <i class="arrow"></i> <a href="javascript:void(0);" class="close-a" nctype="close-barLoginBox" title="关闭">X</a>
            <form id="login_form" method="post" action="<?php echo url('login/login'); ?>" onsubmit="ajaxpost('login_form', '', '', 'onerror')">
                <dl>
                    <dt><strong>登录名</strong></dt>
                    <dd>
                        <input type="text" class="text" tabindex="1" autocomplete="off"  name="user_name" autofocus >
                        <label></label>
                    </dd>
                </dl>
                <dl>
                    <dt><strong>登录密码</strong><a href="<?php echo url('login/forget_password'); ?>" target="_blank">忘记登录密码？</a></dt>
                    <dd>
                        <input tabindex="2" type="password" class="text" name="password" autocomplete="off">
                        <label></label>
                    </dd>
                </dl>
                <?php if(config('captcha_status_login') == '1') { ?>
                <dl>
                    <dt>
                    <strong>验证码</strong>
                    <a href="javascript:void(0);" onclick="javascript:document.getElementById('codeimage').src='<?php echo url('Seccode/makecode'); ?>';">更换验证码</a>
                    </dt>
                    <dd>
                        <input tabindex="3" type="text" name="captcha" autocomplete="off" class="text w130" id="captcha2" maxlength="4" size="10" />
                        <img src='<?php echo url('Seccode/makecode'); ?>' name="codeimage" border="0" id="codeimage" class="vt" />
                        <label></label>
                    </dd>
                </dl>
                <?php } ?>
                <div class="bottom">
                    <input class="btn btn_red" type="submit" value="确认">
                    <input type="hidden" value="<?php echo \think\Request::instance()->param('ref_url'); ?>" name="ref_url">
                    <a href="<?php echo url('login/register'); ?>" target="_blank">注册新用户</a>
                    <?php if($setting_config['qq_isuse'] == 1 || $setting_config['sina_isuse'] == 1 ||$setting_config['weixin_isuse'] == 1): if($setting_config['weixin_isuse'] == 1): ?>
                    <a class="mr20" title="微信账号登录" onclick="ajax_form('weixin_form', '微信账号登录', '<?php echo url('connectwx/index'); ?>', 360);" href="javascript:void(0);">微信</a>
                  <?php endif; if($setting_config['sina_isuse'] == 1): ?>
                    <a class="mr20" title="新浪微博账号登录" href="">新浪微博</a>
                    <?php endif; if($setting_config['qq_isuse'] == 1): ?>
                    <a class="mr20" title="QQ账号登录" href="">QQ账号</a>
                    <?php endif; endif; ?>
                </div>
            </form>
        </div>
        <?php endif; ?>
        <ul class="tools">
            <li><a href="javascript:void(0);" id="chat_show_user" class="chat TA_delay"><div class="tools_img"></div><span class="tit">聊天</span><i id="new_msg" class="new_msg" style="display:none;"></i></a></li>
            <li><a href="javascript:void(0);" id="rtoolbar_cart" class="cart TA_delay"><div class="tools_img"></div><span class="tit">购物车</span><i id="rtoobar_cart_count" class="new_msg" style="display:none;"></i></a></li>
            <li><a href="javascript:void(0);" id="compare" class="compare TA_delay"><div class="tools_img"></div><span class="tit">对比</span></a></li>
            <li><a href="javascript:void(0);" id="gotop" class="gotop TA_delay"><div class="tools_img"></div><span class="tit">顶部</span></a></li>
        </ul>
        <div class="content-box" id="content-compare">
            <div class="top">
                <h3>商品对比</h3>
                <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
            <div id="comparelist"></div>
        </div>
        <div class="content-box" id="content-cart">
            <div class="top">
                <h3>我的购物车</h3>
                <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
            <div id="rtoolbar_cartlist"></div>
        </div>
        <a id="activator" href="javascript:void(0);" class="ds-appbar-hide TA"></a> </div>
    <div class="ds-hidebar" id="ncHideBar">
        <div class="ds-hidebar-bg">
            <?php if(\think\Session::get('is_login')): ?>
            <div class="user-avatar"><img src="<?php echo getMemberAvatar(\think\Session::get('avatar')); ?>"/></div>
            <?php else: ?>
            <div class="user-avatar"><img src="<?php echo getMemberAvatar(\think\Session::get('avatar')); ?>"/></div>
            <?php endif; ?>
            <div class="frame"></div>
            <div class="show"></div>
        </div>
    </div>
</div>




<!--面包屑导航 BEGIN-->
<?php if(!(empty($nav_link_list) || (($nav_link_list instanceof \think\Collection || $nav_link_list instanceof \think\Paginator ) && $nav_link_list->isEmpty()))): ?>
<div class="nch-breadcrumb-layout">
    <div class="nch-breadcrumb w1200"><i class="icon-home"></i>
        <?php foreach($nav_link_list as $nav_link): if(empty($nav_link['link']) || (($nav_link['link'] instanceof \think\Collection || $nav_link['link'] instanceof \think\Paginator ) && $nav_link['link']->isEmpty())): ?>
        <span><?php echo $nav_link['title']; ?></span>
        <?php else: ?>
        <span><a href="<?php echo $nav_link['link']; ?>"><?php echo $nav_link['title']; ?></a></span><span class="arrow">></span>
        <?php endif; endforeach; ?>
    </div>
</div>
<?php endif; ?>
<!--面包屑导航 END-->


<script>
    $(function() {
	//首页左侧分类菜单
	$(".all_categorys ul.menu").find("li").each(
		function() {
			$(this).hover(
				function() {
				    var cat_id = $(this).attr("cat_id");
					var menu = $(this).find("div[cat_menu_id='"+cat_id+"']");
					menu.show();
					$(this).addClass("hover");					
					var menu_height = menu.height();
					if (menu_height < 60) menu.height(80);
					menu_height = menu.height();
					var li_top = $(this).position().top;
					$(menu).css("top",-li_top + 40);
				},
				function() {
					$(this).removeClass("hover");
				    var cat_id = $(this).attr("cat_id");
					$(this).find("div[cat_menu_id='"+cat_id+"']").hide();
				}
			);
		}
	);

        $(".user_menu dl").hover(function() {
            $(this).addClass("hover");
        }, function() {
            $(this).removeClass("hover");
        });
        $('.user_menu .my-mall').mouseover(function() {
            // 最近浏览的商品
            load_history_information();
            $(this).unbind('mouseover');
        });
        $('.user_menu .my-cart').mouseover(function() {
            // 运行加载购物车
            load_cart_information();
            $(this).unbind('mouseover');
        });
    });
    //返回顶部
    backTop=function (btnId){
        var btn=document.getElementById(btnId);
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        window.onscroll=set;
        btn.onclick=function (){
            //btn.style.opacity="0.5";
            window.onscroll=null;
            this.timer=setInterval(function(){
                scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                scrollTop-=Math.ceil(scrollTop*0.1);
                if(scrollTop==0) clearInterval(btn.timer,window.onscroll=set);
                if (document.documentElement.scrollTop > 0) document.documentElement.scrollTop=scrollTop;
                if (document.body.scrollTop > 0) document.body.scrollTop=scrollTop;
            },10);
        };
        function set(){
            scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            //btn.style.opacity=scrollTop?'1':"0.5";
        }
    };
    backTop('gotop');
    //动画显示边条内容区域
    $(function() {
        $(function() {
            $('#activator').click(function() {
                $('#content-cart').animate({'right': '-250px'});
                $('#content-compare').animate({'right': '-250px'});
                $('#vToolbar').animate({'right': '-60px'}, 300,
                    function() {
                        $('#ncHideBar').animate({'right': '59px'},	300);
                    });
                $('div[nctype^="bar"]').hide();
            });
            $('#ncHideBar').click(function() {
                $('#ncHideBar').animate({
                        'right': '-86px'
                    },
                    300,
                    function() {
                        $('#content-cart').animate({'right': '-250px'});
                        $('#content-compare').animate({'right': '-250px'});
                        $('#vToolbar').animate({'right': '0px'},300);
                    });
            });
        });
        $("#compare").click(function(){
            if ($("#content-compare").css('right') == '-250px') {
                loadCompare(false);
                $('#content-cart').animate({'right': '-250px'});
                $("#content-compare").animate({right:'35px'});
            } else {
                $(".close").click();
                $(".chat-list").css("display",'none');
            }
        });
        $("#rtoolbar_cart").click(function(){
            if ($("#content-cart").css('right') == '-250px') {
                $('#content-compare').animate({'right': '-250px'});
                $("#content-cart").animate({right:'35px'});
                if (!$("#rtoolbar_cartlist").html()) {
                    $("#rtoolbar_cartlist").load("<?php echo url('cart/ajax_load','type=html'); ?>");
                }
            } else {
                $(".close").click();
                $(".chat-list").css("display",'none');
            }
        });
        $(".close").click(function(){
            $(".content-box").animate({right:'-250px'});
        });

        $(".quick-menu dl").hover(function() {
                $(this).addClass("hover");
            },
            function() {
                $(this).removeClass("hover");
            });

        // 右侧bar用户信息
        $('div[nctype="a-barUserInfo"]').click(function(){
            $('div[nctype="barUserInfo"]').toggle();
        });
        // 右侧bar登录
        $('div[nctype="a-barLoginBox"]').click(function(){
            $('div[nctype="barLoginBox"]').toggle();
//            document.getElementById('codeimage').src='';
        });
        $('a[nctype="close-barLoginBox"]').click(function(){
            $('div[nctype="barLoginBox"]').toggle();
        });

        <?php if($cart_goods_num > 0): ?>
            $('#rtoobar_cart_count').html(<?php echo $cart_goods_num; ?>).show();
        <?php endif; ?>
    });
</script>

<link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/home/css/index.css">
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.SuperSlide.2.1.1.js"></script>
<style>
    .mall_nav{border-bottom:none;}
    .mall_nav .all_categorys .mc{display: block;}
</style>
<div class="clear"></div>
<!-- HomeFocusLayout Begin-->
<div class="home-focus-layout">
    <div class="bd">
        <ul id="fullScreenSlides" class="full-screen-slides">
            <?php $ap_id =1;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("10")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 10- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
            <li style="background: url(<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>) center top no-repeat rgb(35, 35, 35); display: none;">
                <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">&nbsp;</a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="hd full-screen-slides-pagination">
        <ul>
            <?php $ap_id =1;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("10")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 10- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
            <li class=""></li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    
  <div class="right-sidebar">
    <div class="right-panel">
      <?php if(\think\Session::get('is_login')): ?>
      <div class="loginBox">
        <div class="exitPanel"> <img class="lazyload"  data-original="<?php echo getMemberAvatar(\think\Session::get('avatar')); ?>" alt="" />
          <div class="message">
            <p class="name">Hi, <a href="<?php echo url('member/index'); ?>"><?php echo session('member_name'); ?></a>[<a href="<?php echo url('login/logout'); ?>">退出登录</a>]</p>
          </div>
          <div class="clear"></div>
        </div>
        <!-- 买家信息 -->
        <div class="txtPanel"> <a href="<?php echo url('memberorder/index',['state_type'=>'state_new']); ?>" class="line">
          <p class="num"><?php echo $member_order_info['order_nopay_count']; ?></p>
          <p class="txt">待付款</p>
          </a> <a target="_blank" href="<?php echo url('memberorder/index'); ?>" class="line">
          <p class="num"><?php echo $member_order_info['order_noreceipt_count']; ?></p>
          <p class="txt">待收货</p>
          </a> <a target="_blank" href="<?php echo url('memberrefund/index'); ?>">
          <p class="num"><?php echo $member_order_info['order_noeval_count']; ?></p>
          <p class="txt">待评价</p>
          </a>
        </div>
      </div>
      <?php else: ?>
      <div class="loginBox">
        <div class="welcomePanel"> <img src="<?php echo getMemberAvatar(\think\Session::get('avatar')); ?>">
          <p>Hi，欢迎来<?php echo $setting_config['site_name']; ?>，请登录</p>
        </div>
        <div class="loginPanel">
          <a href="<?php echo url('login/login'); ?>" rel="nofollow">
            <span class="loginTxt"><img alt="" src="<?php echo config('url_domain_root'); ?>static/home/images/u-me.png">登录</span>
          </a>
          <a href="<?php echo url('login/register'); ?>" rel="nofollow">
            <span class="reigsterTxt"><img alt="" src="<?php echo config('url_domain_root'); ?>static/home/images/u-pencil.png">注册</span>
          </a>
        </div>
      </div>
     <?php endif; ?>
      <ul class="securePanel">
        <li><img alt="买家保障" src="<?php echo config('url_domain_root'); ?>static/home/images/u-promise.png">
          <p>买家保障</p>
        </li>
        <li><img alt="商家认证" src="<?php echo config('url_domain_root'); ?>static/home/images/u-quality.png">
          <p>商家认证</p>
        </li>
        <li><img alt="安全交易" src="<?php echo config('url_domain_root'); ?>static/home/images/u-safe.png">
          <p>安全交易</p>
        </li>
      </ul>
      <div class="clear"></div>
    </div>
  </div>
</div>
<!--HomeFocusLayout End-->

<div class="jfocus-trigeminybox">
    <a class="limited_time" title="限时打折" href="<?php echo url('Promotion/index'); ?>">
        <div class="clock-wrap">
            <div class="clock">
                <div class="clock-h" id="ClockHours" style="-webkit-transform: rotate(93deg);"></div>
                <div class="clock-m"></div>
                <div class="clock-s"></div>
            </div>
        </div>
    </a>
    <div class="jfocus-trigeminy">
        <div class="bd">
            <div class="tempWrap">
                <ul>
                    <li>
                        <?php $ap_id =2;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("10")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 10- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
                        <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
                            <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
                        </a>
                        <?php endforeach; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>




<div class="home-sale-layout w1200 mt20">
        <div class="hd">
            <ul class="tabs-nav">
                <li class="tabs-selected on"><i class="arrow"></i><h3>商品推荐</h3></li>
                <li class=""><i class="arrow"></i><h3>打折促销</h3></li>
                <li class=""><i class="arrow"></i><h3>最新热卖</h3></li>
                <li class=""><i class="arrow"></i><h3>疯狂抢购</h3></li>
            </ul>
        </div>
        <div class="bd tabs-panel">
            <ul style="display: block;">
                <?php if(!(empty($recommend_list) || (($recommend_list instanceof \think\Collection || $recommend_list instanceof \think\Paginator ) && $recommend_list->isEmpty()))): if(is_array($recommend_list) || $recommend_list instanceof \think\Collection || $recommend_list instanceof \think\Paginator): if( count($recommend_list)==0 ) : echo "" ;else: foreach($recommend_list as $key=>$goods): ?>
                <li>
                    <dl>
                        <dt class="goods-name"><a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>" title="<?php echo $goods['goods_name']; ?>"><?php echo $goods['goods_name']; ?></a></dt>
                        <dd class="goods-thumb">
                            <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>">
                                <img class="lazyload" data-original="<?php echo cthumb($goods['goods_image']); ?>" alt="<?php echo $goods['goods_name']; ?>">
                            </a>
                        </dd>
                        <dd class="goods-price">商城价：<em>￥<?php echo $goods['goods_price']; ?></em></dd>
                    </dl>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
            <ul style="display: none;">
                <?php if(!(empty($promotion_list) || (($promotion_list instanceof \think\Collection || $promotion_list instanceof \think\Paginator ) && $promotion_list->isEmpty()))): if(is_array($promotion_list) || $promotion_list instanceof \think\Collection || $promotion_list instanceof \think\Paginator): if( count($promotion_list)==0 ) : echo "" ;else: foreach($promotion_list as $key=>$goods): ?>
                <li>
                    <dl>
                        <dt class="goods-name"><a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_id']]); ?>" title="<?php echo $goods['goods_name']; ?>"><?php echo $goods['goods_name']; ?></a></dt>
                        <dd class="goods-thumb">
                            <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_id']]); ?>">
                                <img class="lazyload" data-original="<?php echo cthumb($goods['goods_image']); ?>" alt="<?php echo $goods['goods_name']; ?>">
                            </a>
                        </dd>
                        <dd class="goods-price">商城价：<em>￥<?php echo $goods['goods_price']; ?></em></dd>
                    </dl>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
            <ul style="display: none;">
                <?php if(!(empty($new_list) || (($new_list instanceof \think\Collection || $new_list instanceof \think\Paginator ) && $new_list->isEmpty()))): if(is_array($new_list) || $new_list instanceof \think\Collection || $new_list instanceof \think\Paginator): if( count($new_list)==0 ) : echo "" ;else: foreach($new_list as $key=>$goods): ?>
                <li>
                    <dl>
                        <dt class="goods-name"><a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_id']]); ?>" title="<?php echo $goods['goods_name']; ?>"><?php echo $goods['goods_name']; ?></a></dt>
                        <dd class="goods-thumb">
                            <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_id']]); ?>">
                                <img class="lazyload" data-original="<?php echo cthumb($goods['goods_image']); ?>" alt="<?php echo $goods['goods_name']; ?>">
                            </a>
                        </dd>
                        <dd class="goods-price">商城价：<em>￥<?php echo $goods['goods_price']; ?></em></dd>
                    </dl>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
            <ul style="display: none;">
                <?php if(!(empty($groupbuy_list) || (($groupbuy_list instanceof \think\Collection || $groupbuy_list instanceof \think\Paginator ) && $groupbuy_list->isEmpty()))): if(is_array($groupbuy_list) || $groupbuy_list instanceof \think\Collection || $groupbuy_list instanceof \think\Paginator): if( count($groupbuy_list)==0 ) : echo "" ;else: foreach($groupbuy_list as $key=>$goods): ?>
                <li>
                    <dl>
                        <dt class="goods-name"><a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_id']]); ?>" title="<?php echo $goods['goods_name']; ?>"><?php echo $goods['goods_name']; ?></a></dt>
                        <dd class="goods-thumb">
                            <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_id']]); ?>">
                                <img class="lazyload" data-original="<?php echo cthumb($goods['goods_image']); ?>" alt="<?php echo $goods['goods_name']; ?>">
                            </a>
                        </dd>
                        <dd class="goods-price">商城价：<em>￥<?php echo $goods['goods_price']; ?></em></dd>
                    </dl>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
        </div>
</div>
















<div class="floor floor1 w1200 style-red">
    <div class="floor-left">
        <div class="title">
            <span>1F</span><h2 title="手机 、 数码 、 通信">数码通信</h2>
        </div>
        <div class="recommend-classes">
            <ul>
                <?php if(is_array($floor1_block['goods_class_list']) || $floor1_block['goods_class_list'] instanceof \think\Collection || $floor1_block['goods_class_list'] instanceof \think\Paginator): if( count($floor1_block['goods_class_list'])==0 ) : echo "" ;else: foreach($floor1_block['goods_class_list'] as $key=>$goods_class): ?>
                <li><a href="<?php echo url('Search/index',['cate_id'=>$goods_class['gc_id']]); ?>" title="<?php echo $goods_class['gc_name']; ?>" ><?php echo $goods_class['gc_name']; ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="left-ads">
            <?php $ap_id =8;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
            <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
                <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="floor-right">
        <ul class="tabs-nav hd">
            <?php if(is_array($floor1_block['goods_list']) || $floor1_block['goods_list'] instanceof \think\Collection || $floor1_block['goods_list'] instanceof \think\Paginator): if( count($floor1_block['goods_list'])==0 ) : echo "" ;else: foreach($floor1_block['goods_list'] as $list_key=>$list): ?>
            <li <?php if($list_key == '0'): ?>class="on"<?php endif; ?>><i class="arrow"></i><h3><?php echo $list['gc_name']; ?></h3></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="goods-list bd">
            <?php if(is_array($floor1_block['goods_list']) || $floor1_block['goods_list'] instanceof \think\Collection || $floor1_block['goods_list'] instanceof \think\Paginator): if( count($floor1_block['goods_list'])==0 ) : echo "" ;else: foreach($floor1_block['goods_list'] as $list_key=>$list): ?>
            <ul <?php if($list_key == '0'): ?>style="display:block"<?php endif; ?>>
                <?php if(!(empty($list['gc_list']) || (($list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator ) && $list['gc_list']->isEmpty()))): if(is_array($list['gc_list']) || $list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator): if( count($list['gc_list'])==0 ) : echo "" ;else: foreach($list['gc_list'] as $key=>$goods): ?>
                <li>
                    <dl>
                        <dt class="goods-name"><a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>" title="<?php echo $goods['goods_name']; ?>"><?php echo $goods['goods_name']; ?></a></dt>
                        <dd class="goods-thumb">
                            <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>">
                                <img class="lazyload" data-original="<?php echo cthumb($goods['goods_image']); ?>" alt="<?php echo $goods['goods_name']; ?>"/>
                            </a>
                        </dd>
                        <dd class="goods-price">
                            <em>¥<?php echo $goods['goods_marketprice']; ?></em>
                            <span class="original">¥<?php echo $goods['goods_price']; ?></span>
                        </dd>
                    </dl>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>

<div class="w1200 floor-banner">
    <?php $ap_id =3;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
    <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
        <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
    </a>
    <?php endforeach; ?>
</div>

<div class="floor floor2 w1200 style-orange">
    <div class="floor-left">
        <div class="title">
            <span>2F</span><h2 title="电视、空调、家电">生活家电</h2>
        </div>
        <div class="recommend-classes">
            <ul>
                <?php if(is_array($floor2_block['goods_class_list']) || $floor2_block['goods_class_list'] instanceof \think\Collection || $floor2_block['goods_class_list'] instanceof \think\Paginator): if( count($floor2_block['goods_class_list'])==0 ) : echo "" ;else: foreach($floor2_block['goods_class_list'] as $key=>$goods_class): ?>
                <li><a href="<?php echo url('Search/index',['cate_id'=>$goods_class['gc_id']]); ?>" title="<?php echo $goods_class['gc_name']; ?>" ><?php echo $goods_class['gc_name']; ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="left-ads">
            <?php $ap_id =9;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
            <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
                <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;"/>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="floor-right">
        <ul class="tabs-nav hd">
            <?php if(is_array($floor2_block['goods_list']) || $floor2_block['goods_list'] instanceof \think\Collection || $floor2_block['goods_list'] instanceof \think\Paginator): if( count($floor2_block['goods_list'])==0 ) : echo "" ;else: foreach($floor2_block['goods_list'] as $list_key=>$list): ?>
            <li <?php if($list_key == '0'): ?>class="on"<?php endif; ?>><i class="arrow"></i><h3><?php echo $list['gc_name']; ?></h3></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="goods-list bd">
            <?php if(is_array($floor2_block['goods_list']) || $floor2_block['goods_list'] instanceof \think\Collection || $floor2_block['goods_list'] instanceof \think\Paginator): if( count($floor2_block['goods_list'])==0 ) : echo "" ;else: foreach($floor2_block['goods_list'] as $list_key=>$list): ?>
            <ul <?php if($list_key == '0'): ?>style="display:block"<?php endif; ?>>
                <?php if(!(empty($list['gc_list']) || (($list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator ) && $list['gc_list']->isEmpty()))): if(is_array($list['gc_list']) || $list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator): if( count($list['gc_list'])==0 ) : echo "" ;else: foreach($list['gc_list'] as $key=>$goods): ?>
                <li>
                    <dl>
                        <dt class="goods-name"><a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>" title="<?php echo $goods['goods_name']; ?>"><?php echo $goods['goods_name']; ?></a></dt>
                        <dd class="goods-thumb">
                            <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>">
                                <img class="lazyload" data-original="<?php echo cthumb($goods['goods_image']); ?>" alt="<?php echo $goods['goods_name']; ?>"/>
                            </a>
                        </dd>
                        <dd class="goods-price">
                            <em>¥<?php echo $goods['goods_marketprice']; ?></em>
                            <span class="original">¥<?php echo $goods['goods_price']; ?></span>
                        </dd>
                    </dl>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
<div class="w1200 floor-banner">
    <?php $ap_id =5;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
    <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
        <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
    </a>
    <?php endforeach; ?>
</div>

<div class="floor floor3 w1200 style-brown">
    <div class="floor-left">
        <div class="title">
            <span>3F</span><h2 title="厨房电器">厨房电器</h2>
        </div>
        <div class="recommend-classes">
            <ul>
                <?php if(is_array($floor3_block['goods_class_list']) || $floor3_block['goods_class_list'] instanceof \think\Collection || $floor3_block['goods_class_list'] instanceof \think\Paginator): if( count($floor3_block['goods_class_list'])==0 ) : echo "" ;else: foreach($floor3_block['goods_class_list'] as $key=>$goods_class): ?>
                <li><a href="<?php echo url('Search/index',['cate_id'=>$goods_class['gc_id']]); ?>" title="<?php echo $goods_class['gc_name']; ?>" ><?php echo $goods_class['gc_name']; ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="left-ads">
            <?php $ap_id =10;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
            <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
                <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="floor-right">
        <ul class="tabs-nav hd">
            <?php if(is_array($floor3_block['goods_list']) || $floor3_block['goods_list'] instanceof \think\Collection || $floor3_block['goods_list'] instanceof \think\Paginator): if( count($floor3_block['goods_list'])==0 ) : echo "" ;else: foreach($floor3_block['goods_list'] as $list_key=>$list): ?>
            <li <?php if($list_key == '0'): ?>class="on"<?php endif; ?>><i class="arrow"></i><h3><?php echo $list['gc_name']; ?></h3></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="goods-list bd">
            <?php if(is_array($floor3_block['goods_list']) || $floor3_block['goods_list'] instanceof \think\Collection || $floor3_block['goods_list'] instanceof \think\Paginator): if( count($floor3_block['goods_list'])==0 ) : echo "" ;else: foreach($floor3_block['goods_list'] as $list_key=>$list): ?>
            <ul <?php if($list_key == '0'): ?>style="display:block"<?php endif; ?>>
                <?php if(!(empty($list['gc_list']) || (($list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator ) && $list['gc_list']->isEmpty()))): if(is_array($list['gc_list']) || $list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator): if( count($list['gc_list'])==0 ) : echo "" ;else: foreach($list['gc_list'] as $key=>$goods): ?>
                <li>
                    <dl>
                        <dt class="goods-name"><a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>" title="<?php echo $goods['goods_name']; ?>"><?php echo $goods['goods_name']; ?></a></dt>
                        <dd class="goods-thumb">
                            <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>">
                                <img class="lazyload" data-original="<?php echo cthumb($goods['goods_image']); ?>" alt="<?php echo $goods['goods_name']; ?>"/>
                            </a>
                        </dd>
                        <dd class="goods-price">
                            <em>¥<?php echo $goods['goods_marketprice']; ?></em>
                            <span class="original">¥<?php echo $goods['goods_price']; ?></span>
                        </dd>
                    </dl>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
<div class="w1200 floor-banner">
    <?php $ap_id =6;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
    <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
        <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
    </a>
    <?php endforeach; ?>
</div>

<div class="floor floor4 w1200 style-green">
    <div class="floor-left">
        <div class="title">
            <span>4F</span><h2 title="电脑办公">电脑办公</h2>
        </div>
        <div class="recommend-classes">
            <ul>
                <?php if(is_array($floor4_block['goods_class_list']) || $floor4_block['goods_class_list'] instanceof \think\Collection || $floor4_block['goods_class_list'] instanceof \think\Paginator): if( count($floor4_block['goods_class_list'])==0 ) : echo "" ;else: foreach($floor4_block['goods_class_list'] as $key=>$goods_class): ?>
                <li><a href="<?php echo url('Search/index',['cate_id'=>$goods_class['gc_id']]); ?>" title="<?php echo $goods_class['gc_name']; ?>" ><?php echo $goods_class['gc_name']; ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="left-ads">
            <?php $ap_id =11;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
            <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
                <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="floor-right">
        <ul class="tabs-nav hd">
            <?php if(is_array($floor4_block['goods_list']) || $floor4_block['goods_list'] instanceof \think\Collection || $floor4_block['goods_list'] instanceof \think\Paginator): if( count($floor4_block['goods_list'])==0 ) : echo "" ;else: foreach($floor4_block['goods_list'] as $list_key=>$list): ?>
            <li <?php if($list_key == '0'): ?>class="on"<?php endif; ?>><i class="arrow"></i><h3><?php echo $list['gc_name']; ?></h3></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="goods-list bd">
            <?php if(is_array($floor4_block['goods_list']) || $floor4_block['goods_list'] instanceof \think\Collection || $floor4_block['goods_list'] instanceof \think\Paginator): if( count($floor4_block['goods_list'])==0 ) : echo "" ;else: foreach($floor4_block['goods_list'] as $list_key=>$list): ?>
            <ul <?php if($list_key == '0'): ?>style="display:block"<?php endif; ?>>
                <?php if(!(empty($list['gc_list']) || (($list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator ) && $list['gc_list']->isEmpty()))): if(is_array($list['gc_list']) || $list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator): if( count($list['gc_list'])==0 ) : echo "" ;else: foreach($list['gc_list'] as $key=>$goods): ?>
                <li>
                    <dl>
                        <dt class="goods-name"><a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>" title="<?php echo $goods['goods_name']; ?>"><?php echo $goods['goods_name']; ?></a></dt>
                        <dd class="goods-thumb">
                            <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>">
                                <img class="lazyload" data-original="<?php echo cthumb($goods['goods_image']); ?>" alt="<?php echo $goods['goods_name']; ?>"/>
                            </a>
                        </dd>
                        <dd class="goods-price">
                            <em>¥<?php echo $goods['goods_marketprice']; ?></em>
                            <span class="original">¥<?php echo $goods['goods_price']; ?></span>
                        </dd>
                    </dl>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
<div class="w1200 floor-banner">
    <?php $ap_id =7;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
    <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
        <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
    </a>
    <?php endforeach; ?>
</div>


<div class="floor floor5 w1200 style-blue">
    <div class="floor-left">
        <div class="title">
            <span>5F</span><h2 title="五金装修">五金装修</h2>
        </div>
        <div class="recommend-classes">
            <ul>
                <?php if(is_array($floor5_block['goods_class_list']) || $floor5_block['goods_class_list'] instanceof \think\Collection || $floor5_block['goods_class_list'] instanceof \think\Paginator): if( count($floor5_block['goods_class_list'])==0 ) : echo "" ;else: foreach($floor5_block['goods_class_list'] as $key=>$goods_class): ?>
                <li><a href="<?php echo url('Search/index',['cate_id'=>$goods_class['gc_id']]); ?>" title="<?php echo $goods_class['gc_name']; ?>" ><?php echo $goods_class['gc_name']; ?></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <div class="left-ads">
            <?php $ap_id =12;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
            <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
                <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="floor-right">
        <ul class="tabs-nav hd">
            <?php if(is_array($floor5_block['goods_list']) || $floor5_block['goods_list'] instanceof \think\Collection || $floor5_block['goods_list'] instanceof \think\Paginator): if( count($floor5_block['goods_list'])==0 ) : echo "" ;else: foreach($floor5_block['goods_list'] as $list_key=>$list): ?>
            <li <?php if($list_key == '0'): ?>class="on"<?php endif; ?>><i class="arrow"></i><h3><?php echo $list['gc_name']; ?></h3></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="goods-list bd">
            <?php if(is_array($floor5_block['goods_list']) || $floor5_block['goods_list'] instanceof \think\Collection || $floor5_block['goods_list'] instanceof \think\Paginator): if( count($floor5_block['goods_list'])==0 ) : echo "" ;else: foreach($floor5_block['goods_list'] as $list_key=>$list): ?>
            <ul <?php if($list_key == '0'): ?>style="display:block"<?php endif; ?>>
                <?php if(!(empty($list['gc_list']) || (($list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator ) && $list['gc_list']->isEmpty()))): if(is_array($list['gc_list']) || $list['gc_list'] instanceof \think\Collection || $list['gc_list'] instanceof \think\Paginator): if( count($list['gc_list'])==0 ) : echo "" ;else: foreach($list['gc_list'] as $key=>$goods): ?>
                <li>
                    <dl>
                        <dt class="goods-name"><a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>" title="<?php echo $goods['goods_name']; ?>"><?php echo $goods['goods_name']; ?></a></dt>
                        <dd class="goods-thumb">
                            <a target="_blank" href="<?php echo url('goods/index',['goods_id'=>$goods['goods_commonid']]); ?>">
                                <img class="lazyload" data-original="<?php echo cthumb($goods['goods_image']); ?>" alt="<?php echo $goods['goods_name']; ?>"/>
                            </a>
                        </dd>
                        <dd class="goods-price">
                            <em>¥<?php echo $goods['goods_marketprice']; ?></em>
                            <span class="original">¥<?php echo $goods['goods_price']; ?></span>
                        </dd>
                    </dl>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </ul>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
<div class="w1200 floor-banner">
    <?php $ap_id =4;$ad_position = db("advposition")->cache(3600)->column("ap_id,ap_name,ap_width,ap_height","ap_id");$result = db("adv")->where("ap_id=$ap_id  and adv_enabled = 1 and adv_start_date < 1537149600 and adv_end_date > 1537149600 ")->order("adv_sort desc")->cache(3600)->limit("1")->select();
if(!in_array($ap_id,array_keys($ad_position)) && $ap_id)
{
  db("advposition")->insert(array(
         "ap_id"=>$ap_id,
         "ap_name"=>CONTROLLER_NAME."页面自动增加广告位 $ap_id ",
  ));
  delCacheFile("temp"); // 删除缓存
  \think\Cache::clear();  
}


$c = 1- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && input("get.edit_ad"))
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "adv_code" => "/public/images/not_adv.jpg",
          "adv_link" => config("url_domain_root")."index.php/Admin/Adv/adv_add/ap_id/$ap_id.html",
          "adv_title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
          "ap_id"   =>$ap_id,
      );  
    }
}

foreach($result as $key=>$v):       

    $v["position"] = $ad_position[$v["ap_id"]]; 
    
    if(input("get.edit_ad") && !isset($v["not_adv"]) )
    {
        
        $v["style"] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $v["adv_link"] = config("url_domain_root")."index.php/Admin/Adv/adv_edit/adv_id/".$v['adv_id'].".html";        
        $v["adv_title"] = $ad_position[$v["ap_id"]]["ap_name"]."===".$v["adv_title"];
        $v["target"] = 0;
    }
    ?>
    <a href="<?php echo $v['adv_link']; ?>" target="_blank" title="">
        <img class="lazyload" data-original="<?php echo UPLOAD_SITE_URL; ?>/<?php echo ATTACH_ADV; ?>/<?php echo $v['adv_code']; ?>" style="display: inline;">
    </a>
    <?php endforeach; ?>
</div>



<div class="wrapper mt10"></div>
<div class="index-link wrapper">
  <dl class="website">
    <dt>合作伙伴 | 友情链接<b></b></dt>
    <dd>
      <?php if(!(empty($link_list) || (($link_list instanceof \think\Collection || $link_list instanceof \think\Paginator ) && $link_list->isEmpty()))): if(is_array($link_list) || $link_list instanceof \think\Collection || $link_list instanceof \think\Paginator): $i = 0; $__LIST__ = $link_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>
      <a href="<?php echo $val['link_url']; ?>" target="_blank" title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'],15); ?></a>
      <?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </dd>
  </dl>
</div>
<div class="footer-line"></div>
<!--首页底部保障开始-->

<!--首页底部保障结束-->
<!--StandardLayout Begin-->

<!--StandardLayout End-->




<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.SuperSlide.2.1.1.js"></script>
<script>
  //轮播
   jQuery(".home-focus-layout").slide({mainCell: ".bd ul", autoPlay: true, delayTime: 500,interTime: 5000});
   jQuery(".jfocus-trigeminy").slide({mainCell:".bd li", autoPlay:true,delayTime: 1000,effect:"left",interTime: 5000,vis:4});
   jQuery(".home-sale-layout").slide({autoPlay: false,});
  jQuery(".floor1 .floor-right").slide({mainCell: ".bd", autoPlay: false, interTime: 5000});
  jQuery(".floor2 .floor-right").slide({mainCell: ".bd", autoPlay: false, interTime: 5000});
  jQuery(".floor3 .floor-right").slide({mainCell: ".bd", autoPlay: false, interTime: 5000});
  jQuery(".floor4 .floor-right").slide({mainCell: ".bd", autoPlay: false, interTime: 5000});
  jQuery(".floor5 .floor-right").slide({mainCell: ".bd", autoPlay: false, interTime: 5000});
  function takeCount() {
      setTimeout("takeCount()", 1000);
      $(".time-remain").each(function(){
          var obj = $(this);
          var tms = obj.attr("count_down");
          if (tms>0) {
              tms = parseInt(tms)-1;
              var days = Math.floor(tms / (1 * 60 * 60 * 24));
              var hours = Math.floor(tms / (1 * 60 * 60)) % 24;
              var minutes = Math.floor(tms / (1 * 60)) % 60;
              var seconds = Math.floor(tms / 1) % 60;

              if (days < 0) days = 0;
              if (hours < 0) hours = 0;
              if (minutes < 0) minutes = 0;
              if (seconds < 0) seconds = 0;
              obj.find("[time_id='d']").html(days);
              obj.find("[time_id='h']").html(hours);
              obj.find("[time_id='m']").html(minutes);
              obj.find("[time_id='s']").html(seconds);
              obj.attr("count_down",tms);
          }
      });
  }
  $(function () {

      setTimeout("takeCount()", 1000);
  })
</script>


<div class="server">
    <div class="ensure">
        <a href="#"></a>
        <a href="#"></a>
        <a href="#"></a>
        <a href="#"></a>
    </div>
    <div class="mall_desc">
        <?php if(!(empty($article_list) || (($article_list instanceof \think\Collection || $article_list instanceof \think\Paginator ) && $article_list->isEmpty()))): if(is_array($article_list) || $article_list instanceof \think\Collection || $article_list instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($article_list) ? array_slice($article_list,0,4, true) : $article_list->slice(0,4, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$art): $mod = ($i % 2 );++$i;?>
        <dl> 
            <dt><?php echo $art['ac_name']; ?></dt>
            <dd>
                <?php if(!(empty($art['list']) || (($art['list'] instanceof \think\Collection || $art['list'] instanceof \think\Paginator ) && $art['list']->isEmpty()))): if(is_array($art['list']) || $art['list'] instanceof \think\Collection || $art['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $art['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                <a href="<?php if($list['article_url'] !=''): ?><?php echo $list['article_url']; else: ?><?php echo url('Article/show',['article_id'=>$list['article_id']]); endif; ?>"><?php echo $list['article_title']; ?></a>
                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </dd>
        </dl>
        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
        <dl class="mall_mobile">
            <dt>手机商城</dt>
            <dd>
                <a href="#" class="join">
                    <img src="http://ecmall.etmall.60data.com/data/files/mall/settings/default_qrcode.png" width="105" height="105" alt="手机天猫">
                </a>
            </dd> 
        </dl>
    </div>
</div>






<?php echo getChat(); ?>
<script src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/perfect-scrollbar.min.js"></script>
<link rel="stylesheet" href="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/perfect-scrollbar.min.css">
<div class="footer-info">
    <div class="links w1200">
        <a href="http://www.csdeshang.com/" target="_blank">关于我们</a>|
        <a href="http://www.csdeshang.com/" target="_blank">联系我们</a>|
        <a href="http://www.csdeshang.com/" target="_blank">商家入驻</a>|
        <a href="http://www.csdeshang.com/" target="_blank">营销中心</a>|
        <a href="http://www.csdeshang.com/" target="_blank">手机商城</a>|
        <a href="http://www.csdeshang.com/" target="_blank">友情链接</a>|
        <a href="http://www.csdeshang.com/" target="_blank">销售联盟</a>|
        <a href="http://www.csdeshang.com/" target="_blank">商城社区</a>|
        <a href="http://www.csdeshang.com/" target="_blank">商城公益</a>|
        <a href="http://www.csdeshang.com/" target="_blank">English Site</a>
    </div>
    <p class="copyright">Copyright © 2016-2025 DSMall商城 版权所有 保留一切权利 备案号:苏ICP备12021781号</p>
</div>
<script type="text/javascript" src="<?php echo \think\Config::get('url_domain_root'); ?>static/plugins/jquery.lazyload.min.js"></script>
<script>
    //懒加载
    $("img.lazyload").lazyload({
        placeholder : "<?php echo \think\Config::get('url_domain_root'); ?>static/home/images/loading.gif",
        effect: "fadeIn",
    });
</script>

