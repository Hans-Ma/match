<?php

namespace app\web\controller;

use think\Controller;

class Detail extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($id)
    {
        $arr = model('Detail')->getDatas($id);
        /*var_dump($arr[1][0]);
        die;*/
        return $this->fetch('', [
            'details' => $arr[0],
            'user_a'  => $arr[1][0]['user_a'],
            'user_b'  => $arr[1][0]['user_b'],
            'mess_id' => $id
        ]);
    }

    public function read(){
        $id = $_POST['mess_id'];
        $arr = model('Detail')->getDatas($id);
        return json($arr);
    }

}
