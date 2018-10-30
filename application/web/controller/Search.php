<?php

namespace app\web\controller;

use app\admin\model\Athlete;
use think\Controller;
use think\Exception;
use think\Request;

class Search extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        try{
            if($request->isPost()){

            }else{
                $func = model('Search')->getPlayFunc();
                $date = model('Search')->getGameDate();
                $gameName = model('Search')->getGameName();
                $city = model('Search')->getCity();
                $athlete = (new Athlete())->getTotal();
                return $this->fetch('', [
                    'func'  => $func,
                    'date'  => $date,
                    'name'  => $gameName,
                    'city'  => $city,
                    'player'=> $athlete,
                ]);
            }

        }catch (Exception $e){

        }
    }


    public function search(Request $request){
        $data = model('Search')->getList($request);
        return $data;
    }
}
