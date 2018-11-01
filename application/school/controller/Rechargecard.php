<?php

namespace app\school\controller;
use think\Lang;

class Rechargecard extends AdminControl
{
    const EXPORT_SIZE = 100;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        Lang::load(APP_PATH . 'school/lang/zh-cn/rechargecard.lang.php');
    }

    public function index()
    {
        $model = Model('rechargecard');
        $condition = array();

        if (request()->isGet()) {
            $sn = trim((string)input('param.sn'));
            $batchflag = trim((string)input('param.batchflag'));
            $state = input('param.state');
            if (strlen($sn) > 0) {
                $condition['sn'] = array('like', "%{$sn}%");
            }

            if (strlen($batchflag) > 0) {
                $condition['batchflag'] = array('like', "%{$batchflag}%");
            }

            if ($state == '0' || $state == '1') {
                $condition['state'] = $state;
            }
        }

        $cardList = $model->getRechargeCardList($condition, 20);

        $this->assign('card_list', $cardList);
        $this->assign('show_page', $model->page_info->render());
        $this->setAdminCurItem('index');
        return $this->fetch();
    }

    public function add_card()
    {
        if (!request()->isPost()) {
            $this->setAdminCurItem('add_card');
            return $this->fetch();
        }
        else {
            $denomination = (float)$_POST['denomination'];
            if ($denomination < 0.01) {
                $this->error('面额不能小于0.01');
            }
            if ($denomination > 1000) {
                $this->error('面额不能大于1000');
            }

            $snKeys = array();

            switch ($_POST['type']) {
                case '0':
                    $total = (int)$_POST['total'];
                    if ($total < 1 || $total > 9999) {
                        $this->error('总数只能是1~9999之间的整数');
                    }
                    $prefix = (string)$_POST['prefix'];
                    if (!preg_match('/^[0-9a-zA-Z]{0,16}$/', $prefix)) {
                        $this->error('前缀只能是16字之内字母数字的组合');
                    }
                    while (count($snKeys) < $total) {
                        $snKeys[$prefix . md5(uniqid(mt_rand(), true))] = null;
                    }
                    break;

                case '1':
                    $f = $_FILES['_textfile'];
                    if (!$f || $f['error'] != 0) {
                        $this->error('文件上传失败');
                    }
                    if (!is_uploaded_file($f['tmp_name'])) {
                        $this->error('未找到已上传的文件');
                    }
                    foreach (file($f['tmp_name']) as $sn) {
                        $sn = trim($sn);
                        if (preg_match('/^[0-9a-zA-Z]{1,50}$/', $sn))
                            $snKeys[$sn] = null;
                    }
                    break;

                case '2':
                    foreach (explode("\n", (string)$_POST['manual']) as $sn) {
                        $sn = trim($sn);
                        if (preg_match('/^[0-9a-zA-Z]{1,50}$/', $sn))
                            $snKeys[$sn] = null;
                    }
                    break;

                default:
                    $this->error(lang('param_error'));
                    exit;
            }

            $totalKeys = count($snKeys);
            if ($totalKeys < 1 || $totalKeys > 9999) {
                $this->error('只能在一次操作中增加1~9999个充值卡号');
            }

            if (empty($snKeys)) {
                $this->error('请输入至少一个合法的卡号');
            }

            $snOccupied = 0;
            $model = Model('rechargecard');

            // chunk size = 50
            foreach (array_chunk(array_keys($snKeys), 50) as $snValues) {
                foreach ($model->getOccupiedRechargeCardSNsBySNs($snValues) as $sn) {
                    $snOccupied++;
                    unset($snKeys[$sn]);
                }
            }

            if (empty($snKeys)) {
                $this->error('操作失败，所有新增的卡号都与已有的卡号冲突');
            }

            $batchflag = $_POST['batchflag'];
            $adminName = $this->admin_info['admin_name'];
            $ts = time();

            $snToInsert = array();
            foreach (array_keys($snKeys) as $sn) {
                $snToInsert[] = array(
                    'sn' => $sn, 'denomination' => $denomination, 'batchflag' => $batchflag, 'admin_name' => $adminName,
                    'tscreated' => $ts,
                );
            }

            if (!$model->insertAll($snToInsert)) {
                $this->error('操作失败');
            }

            $countInsert = count($snToInsert);
            $this->log("新增{$countInsert}张充值卡（面额￥{$denomination}，批次标识“{$batchflag}”）");

            $msg = '操作成功';
            if ($snOccupied > 0)
                $msg .= "有 {$snOccupied} 个卡号与已有的未使用卡号冲突";

            $this->error($msg,'rechargecard/index');
        }
    }

    public function del_card()
    {
        if (!(input('param.id'))) {
            $this->error(lang('param_error'));
        }

        Model('rechargecard')->delRechargeCardById(input('param.id'));

        $this->log("删除充值卡（#ID: {input('param.id')}）");

        $this->success('操作成功');
    }

    public function del_card_batch()
    {
        if (empty($_POST['ids']) || !is_array($_POST['ids'])) {
            $this->error(lang('param_error'));
        }

        Model('rechargecard')->delRechargeCardById($_POST['ids']);

        $count = count($_POST['ids']);
        $this->log("删除{$count}张充值卡");

        $this->success('操作成功');
    }

    /**
     * 导出
     */
    public function export_step1()
    {
        $model = Model('rechargecard');
        $condition = array();

        if (request()->isPost()) {
            $sn = trim((string)input('param.sn'));
            $batchflag = trim((string)input('param.batchflag'));
            $state = trim((string)input('param.state'));

            if (strlen($sn) > 0) {
                $condition['sn'] = array('like', "%{$sn}%");
                $this->assign('sn', $sn);
            }

            if (strlen($batchflag) > 0) {
                $condition['batchflag'] = array('like', "%{$batchflag}%");
                $this->assign('batchflag', $batchflag);
            }

            if ($state === '0' || $state === '1') {
                $condition['state'] = $state;
                $this->assign('state', $state);
            }

            if ($condition) {
                $this->assign('form_submit', 'ok');
            }
        }

        if (!is_numeric(input('param.curpage'))) {
            $count = $model->getRechargeCardCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE) {    //显示下载链接
                $page = ceil($count / self::EXPORT_SIZE);
                for ($i = 1; $i <= $page; $i++) {
                    $limit1 = ($i - 1) * self::EXPORT_SIZE + 1;
                    $limit2 = $i * self::EXPORT_SIZE > $count ? $count : $i * self::EXPORT_SIZE;
                    $array[$i] = $limit1 . ' ~ ' . $limit2;
                }
                $this->assign('list', $array);
                $this->assign('murl', url('rechargecard/index'));
                return $this->fetch('excel');

            }
            else {    //如果数量小，直接下载
                $data = $model->getRechargeCardList($condition, self::EXPORT_SIZE);

                $this->createExcel($data);
            }
        }
        else {    //下载
            $limit1 = (input('param.curpage') - 1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;

            $data = $model->getRechargeCardList($condition, 20, "{$limit1},{$limit2}");

            $this->createExcel($data);
        }
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array())
    {
        $excel_obj = new \excel\Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array(
                                 'id' => 's_title', 'Font' => array('FontName' => '宋体', 'Size' => '12', 'Bold' => '1')
                             ));
        //header
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '充值卡卡号');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '批次标识');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '面额(元)');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '发布管理员');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '发布时间');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '领取人');

        //data
        foreach ((array)$data as $k => $v) {
            $tmp = array();
            $tmp[] = array('data' => "\t" . $v['sn']);
            $tmp[] = array('data' => "\t" . $v['batchflag']);
            $tmp[] = array('data' => "\t" . $v['denomination']);
            $tmp[] = array('data' => "\t" . $v['admin_name']);
            $tmp[] = array('data' => "\t" . date('Y-m-d H:i:s', $v['tscreated']));
            if ($v['state'] == 1 && $v['member_id'] > 0 && $v['tsused'] > 0) {
                $tmp[] = array('data' => "\t" . $v['member_name']);
            }
            else {
                $tmp[] = array('data' => "\t-");
            }
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data, CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('充值卡', CHARSET));
        $excel_obj->generateXML($excel_obj->charset('充值卡', CHARSET) . input('param.curpage') . '-' . date('Y-m-d-H', time()));
    }


    protected function getAdminItemList()
    {
        $menu_array = array(
            array(
                'name' => 'index', 'text' => '列表', 'url' => url('Rechargecard/index')
            ), array(
                'name' => 'add_card', 'text' => '新增', 'url' => url('Rechargecard/add_card')
            ),
        );
        return $menu_array;
    }
}