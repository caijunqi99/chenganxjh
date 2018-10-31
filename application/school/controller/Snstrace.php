<?php

namespace app\school\controller;

use think\Lang;

class Snstrace extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'school/lang/zh-cn/snstrace.lang.php');
    }

    /**
     * 动态列表
     */
    public function index()
    {
        $tracelog_model = Model('snstracelog');
        $condition = array();
        //会员名
        if (input('param.search_uname') != '') {
            $condition['trace_membernamelike'] = trim(input('param.search_uname'));
        }
        //内容
        if (input('param.search_content') != '') {
            $condition['trace_contentortitle'] = trim(input('param.search_content'));
        }
        //状态
        if (input('param.search_state') != '') {
            $condition['trace_state'] = "{input('param.search_state')}";
        }
        //发表时间
        if (input('param.search_stime') != '') {
            $condition['stime'] = strtotime(input('param.search_stime'));
        }
        if (input('param.search_etime') != '') {
            $condition['etime'] = strtotime(input('param.search_etime'));
        }
        //分页

        $tracelist = $tracelog_model->getTracelogList($condition, 10);
        if (!empty($tracelist)) {
            foreach ($tracelist as $k => $v) {
                if (!empty($v['trace_title'])) {
                    //替换标题中的siteurl
                    $v['trace_title'] = str_replace("%siteurl%", SHOP_SITE_URL . DS, $v['trace_title']);
                }
                if (!empty($v['trace_content'])) {
                    //替换内容中的siteurl
                    $v['trace_content'] = str_replace("%siteurl%", SHOP_SITE_URL . DS, $v['trace_content']);
                    //将收藏商品和店铺连接剔除
                    $v['trace_content'] = str_replace(lang('admin_snstrace_collectgoods'), "", $v['trace_content']);
                    $v['trace_content'] = str_replace(lang('admin_snstrace_collectstore'), "", $v['trace_content']);
                }
                $tracelist[$k] = $v;
            }
        }
        $this->setAdminCurItem('index');
        $this->assign('tracelist', $tracelist);
        $this->assign('show_page', $tracelog_model->page_info->render());
        return $this->fetch();
    }

    /**
     * 删除动态
     */
    public function tracedel()
    {
        $tid = input('param.t_id');
        if (empty($tid)) {
            $this->error(lang('admin_snstrace_pleasechoose_del'), 'snstrace/index');
        }
        $tid_str = implode("','", $tid);
        //删除动态
        $tracelog_model = Model('snstracelog');
        $result = $tracelog_model->delTracelog(array('trace_id_in' => $tid_str));
        if ($result) {
            //判断是否完全删除
            $tracelog_list = $tracelog_model->getTracelogList(array('traceid_in' => "$tid_str"));
            if (!empty($tracelog_list)) {
                foreach ($tracelog_list as $k => $v) {
                    unset($tid[array_search($v['trace_id'], $tid)]);
                }
            }
            $tid_str = implode("','", $tid);
            //删除动态下的评论
            $comment_model = Model('snscomment');
            $condition = array();
            $condition['comment_originalid_in'] = $tid_str;
            $condition['comment_originaltype'] = "0";
            $comment_model->delComment($condition);
            //更新转帖的原帖删除状态为已经删除
            $tracelog_model->tracelogEdit(array('trace_originalstate' => '1'), array('trace_originalid_in' => "$tid_str"));
            $this->log(lang('ds_del').lang('admin_snstrace_comment'), 1);
            $this->success(lang('ds_common_del_succ'), 'snstrace/index');
        }
        else {
            $this->error(lang('ds_common_del_fail'), 'snstrace/index');
        }
    }

    /**
     * 编辑动态
     */
    public function traceedit()
    {
        $tid = input('param.t_id');
        if (empty($tid)) {
            $this->error(lang('admin_snstrace_pleasechoose_edit'), 'snstrace/index');
        }
        $tid_str = implode("','", $tid);
        $type = input('param.type');
        //删除动态
        $tracelog_model = Model('snstracelog');
        $update_arr = array();
        if ($type == 'hide') {
            $update_arr['trace_state'] = '1';
        }
        else {
            $update_arr['trace_state'] = '0';
        }
        $result = $tracelog_model->tracelogEdit($update_arr, array('traceid_in' => "$tid_str"));
        unset($update_arr);
        if ($result) {
            //判断是否完全修改成功
            $condition = array();
            $condition['traceid_in'] = "$tid_str";
            if ($type == 'hide') {
                $condition['trace_state'] = '1';
            }
            else {
                $condition['trace_state'] = '0';
            }
            $tracelog_list = $tracelog_model->getTracelogList($condition);
            unset($condition);
            $tid_new = array();
            if (!empty($tracelog_list)) {
                foreach ($tracelog_list as $k => $v) {
                    $tid_new[] = $v['trace_id'];
                }
            }
            $tid_str = implode("','", $tid_new);
            //更新转帖的原帖删除状态为已经删除或者为显示
            $update_arr = array();
            if ($type == 'hide') {
                $update_arr['trace_originalstate'] = '1';
            }
            else {
                $update_arr['trace_originalstate'] = '0';
            }
            $tracelog_model->tracelogEdit($update_arr, array('trace_originalid_in' => "$tid_str"));
            $this->log(lang('ds_edit').lang('admin_snstrace_comment'), 1);
            $this->success(lang('ds_common_op_succ'), 'snstrace/tracelist');
        }
        else {
            $this->error(lang('ds_common_op_fail'), 'snstrace/tracelist');
        }
    }

    /**
     * 评论列表
     */
    public function commentlist()
    {
        $comment_model = Model('snscomment');
        //查询评论总数
        $condition = array();
        //会员名
        if (input('param.search_uname') != '') {
            $condition['comment_membername_like'] = trim(input('param.search_uname'));
        }
        //内容
        if (input('param.search_content') != '') {
            $condition['comment_content_like'] = trim(input('param.search_content'));
        }
        //状态
        if (input('param.search_state') != '') {
            $condition['comment_state'] = "{$_GET['search_state']}";
        }
        //发表时间
        if (input('param.search_stime') != '') {
            $condition['stime'] = strtotime(input('param.search_stime'));
        }
        if (input('param.search_etime') != '') {
            $condition['etime'] = strtotime(input('param.search_etime'));
        }
        if (input('param.tid') != '') {
            $condition['comment_originalid'] = input('param.tid');
            $condition['comment_originaltype'] = "0";//原帖类型 0表示动态信息 1表示分享商品
        }
        //评价列表

        $commentlist = $comment_model->getCommentList($condition, 10);
        $this->assign('commentlist', $commentlist);
        $this->assign('show_page', $comment_model->page_info->render());
        $this->setAdminCurItem('commentlist');
        return $this->fetch();
    }

    /**
     * 删除评论
     */
    public function commentdel()
    {
        $cid = $_POST['c_id'];
        if (empty($cid)) {
            $this->error(lang('admin_snstrace_pleasechoose_del'), 'snstrace/commentlist');
        }
        $cid_str = implode("','", $cid);
        //删除评论
        $comment_model = Model('snscomment');
        $result = $comment_model->delComment(array('comment_id_in' => "$cid_str"));
        if ($result) {
            $this->log(lang('ds_del').lang('admin_snstrace_pl'), 1);
            $this->success(lang('ds_common_del_succ'), 'snstrace/commentlist');
        }
        else {
            $this->error(lang('ds_common_del_fail'), 'snstrace/commentlist');
        }
    }

    /**
     * 编辑评论
     */
    public function commentedit()
    {
        $cid = $_POST['c_id'];
        if (empty($cid)) {
            $this->error(lang('admin_snstrace_pleasechoose_edit'), 'snstrace/commentlist');
        }
        $cid_str = implode("','", $cid);
        $type = input('param.type');
        //删除动态
        $comment_model = Model('snscomment');
        $update_arr = array();
        if ($type == 'hide') {
            $update_arr['comment_state'] = '1';
        }
        else {
            $update_arr['comment_state'] = '0';
        }
        $result = $comment_model->commentEdit($update_arr, array('comment_id_in' => "$cid_str"));
        unset($update_arr);
        if ($result) {
            $this->log(lang('ds_edit').lang('admin_snstrace_pl'), 1);
            $this->success(lang('ds_common_op_succ'), 'snstrace/commentlist');
        }
        else {
            $this->error(lang('ds_common_op_fail'), 'snstrace/commentlist');
        }
    }

    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => lang('admin_snstrace_tracelist'), 'url' => url('Snstrace/index')
            ), array(
                'name' => 'commentlist', 'text' => lang('admin_snstrace_commentlist'), 'url' => url('Snstrace/commentlist')
            ),
        );
        return $menu_array;
    }
}