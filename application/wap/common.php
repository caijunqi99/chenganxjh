<?php


function output_data($datas, $extend_data = array(),$codd=1,$isAssoc = 'false') {
    
    $data = array();
    $data['code'] = isset($datas['error'])?'100':'200';
    if ($codd !=1) $data['code'] = '400';
    $data['result']=isset($datas['error'])?array():($isAssoc == 'true'?(object)$datas:$datas);
    $data['message'] = isset($datas['error'])?$datas['error']:'';
    if(!empty($extend_data)) {
        $data = array_merge($data, $extend_data);
    }

    if(!empty($_GET['callback'])) {
        echo $_GET['callback'].'('.json_encode($data).')';die;
    } else {
        echo json_encode($data);die;
    }
}
function output_datas($data,$transfer = 'true'){
    output_data($data, array(),1,$transfer);
}
function _checkAssocArray($arr){
    $index = 0;
    foreach (array_keys($arr) as $key) {
        if ($index++ != $key) return 'false';
    }
    return 'true';
}


function output_error($message, $extend_data = array(),$codd=1) {
    $datas = array('error' => $message);
    output_data($datas, $extend_data,$codd);
}

function mobile_page($page_info) {
    //输出是否有下一页
    $extend_data = array();
    if($page_info==''){
        $extend_data['page_total']=1;
        $extend_data['hasmore'] = false;
    }else {
        $current_page = $page_info->currentPage();
        if ($current_page <= 0) {
            $current_page = 1;
        }
        if ($current_page >= $page_info->lastPage()) {
            $extend_data['hasmore'] = false;
        }
        else {
            $extend_data['hasmore'] = true;
        }
        $extend_data['page_total'] = $page_info->lastPage();
    }
    return $extend_data;
}

function get_server_ip() {
    if (isset($_SERVER)) {
        if($_SERVER['SERVER_ADDR']) {
            $server_ip = $_SERVER['SERVER_ADDR'];
        } else {
            $server_ip = $_SERVER['LOCAL_ADDR'];
        }
    } else {
        $server_ip = getenv('SERVER_ADDR');
    }
    return $server_ip;
}

function http_get($url) {
    return file_get_contents($url);
}

function http_post($url, $param) {
    $postdata = http_build_query($param);

    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context  = stream_context_create($opts);

    return @file_get_contents($url, false, $context);
}

function http_postdata($url, $postdata) {
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context  = stream_context_create($opts);

    return @file_get_contents($url, false, $context);
}
function p($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}