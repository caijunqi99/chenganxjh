<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/27
 * Time: 9:23
 */

namespace app\school\controller;


use think\Lang;
use think\Validate;

class Snsmember extends AdminControl
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'school/lang/zh-cn/snsmember.lang.php');
    }

    /**
     * 标签列表
     */
    public function index()
    {
        // 实例化模型

        if (request()->isPost()) {
            switch ($_POST['submit_type']) {
                case 'del':
                    $result = db('snsmembertag')->delete(implode(',', $_POST['id']));
                    if ($result) {
                        $this->success(lang('ds_common_op_succ'));
                    }
                    else {
                        $this->error(lang('ds_common_op_fail'));
                    }
                    break;
            }
        }
        $tag_list = db('snsmembertag')->order('mtag_sort asc')->paginate(10,false,['query' => request()->param()]);
        $this->assign('showpage', $tag_list->render());
        $this->assign('tag_list', $tag_list);
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    /**
     * 添加标签
     */
    public function tag_add()
    {
        if (request()->isPost()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $data = [
                'membertag_name' => $_POST["membertag_name"], 'membertag_sort' => $_POST["membertag_sort"],
            ];
            $rule = [
                ['membertag_name', 'require', lang('sns_member_tag_name_not_null')],
                ['membertag_sort', 'require|number', lang('sns_member_tag_sort_is_number')],
            ];

            $error = $obj_validate->check($data, $rule);
            if (!$error) {
                $this->error($obj_validate->getError());
            }
            else {
                /**
                 * 上传图片
                 */
                $default_dir = BASE_UPLOAD_PATH . DS . ATTACH_PATH . '/membertag';
                $img='';
                if (!empty($_FILES['membertag_img']['name'])) {
                    $file = request()->file('membertag_img');
                    $info = $file->validate(['ext' => 'jpg,png,gif'])->move($default_dir);
                    if (!$info) {
                        $this->error($file->getError());
                    }
                    else {
                        $img = $info->getSaveName();
                    }
                }
                $insert = array(
                    'mtag_name' => $_POST['membertag_name'], 'mtag_sort' => intval($_POST['membertag_sort']),
                    'mtag_recommend' => intval($_POST['membertag_recommend']),
                    'mtag_desc' => trim($_POST['membertag_desc']), 'mtag_img' => $img
                );
                $result = db('snsmembertag')->insert($insert);
                if ($result) {
                    $this->log(lang('ds_add').lang('sns_member_tag') . '[' . $_POST['membertag_name'] . ']', 1);
                    $this->success(lang('ds_common_op_succ'), 'snsmember/index');
                }
                else {
                    $this->error(lang('ds_common_op_fail'));
                }
            }
        }
        $this->setAdminCurItem('tag_add');
        return $this->fetch();
    }

    /**
     * 编辑标签
     */
    public function tag_edit()
    {
        // 实例化模型
        if (request()->isPost()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $data = [
                'membertag_name' => $_POST["membertag_name"], 'membertag_sort' => $_POST["membertag_sort"],
            ];
            $rule = [
                ['membertag_name', 'require', lang('sns_member_tag_name_not_null')],
                ['membertag_sort', 'require|number', lang('sns_member_tag_sort_is_number')],
            ];

            $error = $obj_validate->check($data, $rule);
            if (!$error) {
                $this->error($obj_validate->getError());
            }
            else {
                /**
                 * 上传图片
                 */
                $default_dir = BASE_UPLOAD_PATH . DS . ATTACH_PATH . '/membertag';

                $input='';
                if (!empty($_FILES['membertag_img']['name'])) {
                    $file = request()->file('membertag_img');
                    $info = $file->validate(['ext' => 'jpg,png,gif'])->move($default_dir);
                    if (!$info) {
                        $this->error($file->getError());
                    }
                    else {
                        $input = $info->getSaveName();
                    }
                }
                $update = array();
                $update['mtag_id'] = intval($_POST['id']);
                $update['mtag_name'] = trim($_POST['membertag_name']);
                $update['mtag_sort'] = intval($_POST['membertag_sort']);
                $update['mtag_recommend'] = intval($_POST['membertag_recommend']);
                $update['mtag_desc'] = trim($_POST['membertag_desc']);
                $update['mtag_img'] = $input;


                $result = db('snsmembertag')->update($update);
                if ($result) {
                    $this->log(lang('ds_edit').lang('sns_member_tag') . '[' . $_POST['membertag_name'] . ']', 1);
                    $this->success(lang('ds_common_op_succ'), 'snsmember/index');
                }
                else {
                    $this->error(lang('ds_common_op_fail'));
                }
            }
        }
        // 验证
        $mtag_id = intval(input('param.id'));
        if ($mtag_id <= 0) {
            $this->error(lang('param_error'));
        }
        $mtag_info = db('snsmembertag')->find($mtag_id);
        if (empty($mtag_info)) {
            $this->error(lang('param_error'));
        }
        $this->setAdminCurItem('tag_edit');
        //halt($mtag_info);
        $this->assign('mtag_info', $mtag_info);
        return $this->fetch();
    }

    /**
     * 删除标签
     */
    public function tag_del()
    {
        // 验证
        $mtag_id = intval(input('param.id'));
        if ($mtag_id <= 0) {
            $this->error(lang('param_error'));
        }
        $result = db('snsmembertag')->delete($mtag_id);
        if ($result) {
            $this->log(lang('ds_del').lang('sns_member_tag') . '[ID:' . $mtag_id . ']', 1);
            $this->success(lang('ds_common_del_succ'));
        }
        else {
            $this->error(lang('ds_common_del_fail'));
        }
    }

    /**
     * 标签所属会员列表
     */
    public function tag_member()
    {
        // 验证
        $mtag_id = intval(input('param.id'));
        if ($mtag_id <= 0) {
            $this->error(lang('param_error'));
        }
        $count = db('snsmtagmember')->where(array('mtag_id' => $mtag_id))->count();
        $tagmember_list = db('snsmtagmember')->alias('s')->field('s.*,m.member_avatar,m.member_name')->join('__MEMBER__ m','s.member_id=m.member_id','LEFT')->where(array('s.mtag_id' => $mtag_id))->order('s.recommend desc, s.member_id asc')->paginate(10,$count,['query' => request()->param()]);
        $this->assign('tagmember_list', $tagmember_list);
        $this->assign('showpage', $tagmember_list->render());
        $this->setAdminCurItem('tag_member');
        return $this->fetch();
    }

    /**
     * 删除添加标签会员
     */
    public function mtag_del()
    {
        $mtag_id = intval(input('param.id'));
        $member_id = intval(input('param.mid'));
        if ($mtag_id <= 0 || $member_id <= 0) {
            $this->error(lang('miss_argument'));
        }
        // 条件
        $where = array(
            'mtag_id' => $mtag_id, 'member_id' => $member_id
        );
        $result = db('snsmtagmember')->where($where)->delete();
        if ($result) {
            $this->log(lang('ds_del').lang('sns_member_tag') . '[ID:' . $mtag_id . ']', 1);
            $this->success(lang('ds_common_del_succ'));
        }
        else {
            $this->error(lang('ds_common_del_fail'));
        }
    }

    /**
     * ajax修改
     */
    public function ajax()
    {
        // 实例化模型
        switch (input('param.branch')) {
            /**
             * 更新名称、排序、推荐
             */
            case 'membertag_name':
            case 'membertag_sort':
            case 'membertag_recommend':
                $update = array(
                    'mtag_id' => intval(input('param.id')), input('param.column') => input('param.value')
                );
                db('snsmembertag')->update($update);
                echo 'true';
                break;
            /**
             * sns_mtagmember表推荐
             */
            case 'mtagmember_recommend':
                list($where['mtag_id'], $where['member_id']) = explode(',', input('param.id'));
                $update = array(
                    input('param.column') => input('param.value')
                );
                db('snsmtagmember')->where($where)->update($update);
                echo 'true';
                break;
        }
    }

    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => lang('sns_member_tag_manage'), 'url' => url('Snsmember/index')
            ), array(
                'name' => 'tag_add', 'text' => lang('ds_new'), 'url' => url('Snsmember/tag_add')
            ),
        );
        if(request()->action()== 'tag_edit') {
            $menu_array[] = array(
                'name' => 'tag_edit', 'text' => lang('ds_edit'), 'url' => ('#')
            );
        }
        if(request()->action()== 'tag_member') {
            $menu_array[] = array(
                'name' => 'tag_member', 'text' => lang('sns_member_member_list'), 'url' => ('#')
            );
        }
        return $menu_array;
    }
}