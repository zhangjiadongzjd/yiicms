<?php
namespace common\helps;

class Tools{
    public static function json_data($code=200,$msg='',$data=[],$count=0) {
        $return = [];
        $return['code'] = $code;
        $return['msg'] = $msg;
        $return['count'] = $count;
        $return['data'] = $data;

        return $return;
    }
}