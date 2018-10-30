<?php

namespace app\web\model;

use think\Db;
use think\Exception;
use think\Model;

class Detail extends Model
{
    public function getDatas($id)
    {
        try{
            $arr = $this->getUserId($id);
            $score_a = Db::name("game_data")
                ->where('user_id', $arr[0]['user_a'])
                ->where('mess_id', $id)
                ->select();

            $score_b = Db::name("game_data")
                ->where('user_id', $arr[0]['user_b'])
                ->where('mess_id', $id)
                ->select();
            $arr1 = $this->getScores($score_a);
            $arr2 = $this->getScores($score_b);

            $finalArr[0]['get_a'] = $arr1[0];
            $finalArr[0]['get_b'] = $arr2[0];
            $finalArr[0]['lose_a'] = $arr1[1];
            $finalArr[0]['lose_b'] = $arr2[1];
            $finalArr[0]['type'] = '发球';
            $finalArr[1]['get_a'] = $arr1[2];
            $finalArr[1]['get_b'] = $arr2[2];
            $finalArr[1]['lose_a'] = $arr1[3];
            $finalArr[1]['lose_b'] = $arr2[3];
            $finalArr[1]['type'] = '正手';
            $finalArr[2]['get_a'] = $arr1[4];
            $finalArr[2]['get_b'] = $arr2[4];
            $finalArr[2]['lose_a'] = $arr1[5];
            $finalArr[2]['lose_b'] = $arr2[5];
            $finalArr[2]['type'] = '反手';
            $finalArr[3]['get_a'] = $arr1[6];
            $finalArr[3]['get_b'] = $arr2[6];
            $finalArr[3]['lose_a'] = $arr1[7];
            $finalArr[3]['lose_b'] = $arr2[7];
            $finalArr[3]['type'] = '侧身';
            $finalArr[4]['get_a'] = $arr1[8];
            $finalArr[4]['get_b'] = $arr2[8];
            $finalArr[4]['lose_a'] = $arr1[9];
            $finalArr[4]['lose_b'] = $arr2[9];
            $finalArr[4]['type'] = '控制';
            $finalArr[5]['get_a'] = $arr1[10];
            $finalArr[5]['get_b'] = $arr2[10];
            $finalArr[5]['lose_a'] = $arr1[11];
            $finalArr[5]['lose_b'] = $arr2[11];
            $finalArr[5]['type'] = '发抢段';
            $finalArr[6]['get_a'] = $arr1[12];
            $finalArr[6]['get_b'] = $arr2[12];
            $finalArr[6]['lose_a'] = $arr1[13];
            $finalArr[6]['lose_b'] = $arr2[13];
            $finalArr[6]['type'] = '接抢段';
            $finalArr[7]['get_a'] = $arr1[14];
            $finalArr[7]['get_b'] = $arr2[14];
            $finalArr[7]['lose_a'] = $arr1[15];
            $finalArr[7]['lose_b'] = $arr2[15];
            $finalArr[7]['type'] = '转换段';
            $finalArr[8]['get_a'] = $arr1[16];
            $finalArr[8]['get_b'] = $arr2[16];
            $finalArr[8]['lose_a'] = $arr1[17];
            $finalArr[8]['lose_b'] = $arr2[17];
            $finalArr[8]['type'] = '相持段';

            /*$finalArr = [];
            for($j = 0; $j <= 4; $j++){
                for($i = 0; $i <= 11; $i++){
                    if($i % 2 == 0){
                        $finalArr[$j]['get_a'] = $arr1[$i];
                        $finalArr[$j]['get_b'] = $arr2[$i];
                    }else{
                        $finalArr[$j]['lose_a'] = $arr1[$i];
                        $finalArr[$j]['lose_b'] = $arr2[$i];
                    }
                }
            }
            var_dump($finalArr);
            die;*/
            $arrMix = [$finalArr,$arr];
            return $arrMix;
        }catch (Exception $e){

        }
    }

    private function getUserId($id){
        try{
            $data = Db::name("message")
                ->field('user_a')
                ->field('user_b')
                ->where("id", $id)
                ->select();
            return $data;
        }catch (Exception $e){
            echo $e->getMessage();
            die;
        }
    }

    private function getScores($arr){
        $faqiudefen = 0;
        $faqiushifen = 0;
        $zhengshoudefen = 0;
        $zhengshoushifen = 0;
        $fanshoudefen = 0;
        $fanshoushifen = 0;
        $ceshendefen = 0;
        $ceshenshifen = 0;
        $kongzhishifen = 0;
        $kongzhidefen = 0;
        $faqiangdefen = 0;
        $faqiangshifen = 0;
        $jieqiangdefen = 0;
        $jieqiangshifen = 0;
        $zhuanhuandefen = 0;
        $zhuanhuanshifen = 0;
        $xiangchidefen = 0;
        $xiangchishifen = 0;
        foreach($arr as $value){
            if($value['get_lose'] == '得' && $value['tool'] == '发球'){
                $faqiudefen += 1;
            }
            if($value['get_lose'] == '失' && $value['tool'] == '发球'){
                $faqiushifen += 1;
            }
            if($value['get_lose'] == '得' && $value['tool'] == '正手'){
                $zhengshoudefen += 1;
            }
            if($value['get_lose'] == '失' && $value['tool'] == '正手'){
                $zhengshoushifen += 1;
            }
            if($value['get_lose'] == '失' && $value['tool'] == '反手'){
                $fanshoushifen += 1;
            }
            if($value['get_lose'] == '得' && $value['tool'] == '反手'){
                $fanshoudefen += 1;
            }
            if($value['get_lose'] == '得' && $value['tool'] == '侧身'){
                $ceshendefen += 1;
            }
            if($value['get_lose'] == '失' && $value['tool'] == '侧身'){
                $ceshenshifen += 1;
            }
            if($value['get_lose'] == '失' && $value['tool'] == '控制'){
                $kongzhishifen += 1;
            }
            if($value['get_lose'] == '得' && $value['tool'] == '控制'){
                $kongzhidefen += 1;
            }
            if($value['get_lose'] == '得' && ($value['bat_number'] == '发球' || $value['bat_number'] == '第三板')){
                $faqiangdefen += 1;
            }
            if($value['get_lose'] == '失' && ($value['bat_number'] == '发球' || $value['bat_number'] == '第三板')){
                $faqiangshifen += 1;
            }
            if($value['get_lose'] == '得' && ($value['bat_number'] == '接发球' || $value['bat_number'] == '第四板')){
                $jieqiangdefen += 1;
            }
            if($value['get_lose'] == '失' && ($value['bat_number'] == '接发球' || $value['bat_number'] == '第四板')){
                $jieqiangshifen += 1;
            }
            if($value['get_lose'] == '得' && ($value['bat_number'] == '第六板' || $value['bat_number'] == '第五板')){
                $zhuanhuandefen += 1;
            }
            if($value['get_lose'] == '失' && ($value['bat_number'] == '第六板' || $value['bat_number'] == '第五板')){
                $zhuanhuanshifen += 1;
            }
            if($value['get_lose'] == '得' && (!$value['bat_number'] == '第六板' && !$value['bat_number'] == '第五板' && !$value['bat_number'] == '第四板' && !$value['bat_number'] == '第三板' && !$value['bat_number'] == '发球' && !$value['bat_number'] == '接发球')){
                $jieqiangdefen += 1;
            }
            if($value['get_lose'] == '失' && (!$value['bat_number'] == '第六板' && !$value['bat_number'] == '第五板' && !$value['bat_number'] == '第四板' && !$value['bat_number'] == '第三板' && !$value['bat_number'] == '发球' && !$value['bat_number'] == '接发球')){
                $jieqiangshifen += 1;
            }

        }
        return [$faqiudefen,$faqiushifen,$zhengshoudefen,$zhengshoushifen,$fanshoudefen,$fanshoushifen,$ceshendefen,$ceshenshifen,$kongzhidefen,$kongzhishifen,$faqiangdefen,$faqiangshifen,
            $jieqiangdefen,$jieqiangshifen,$zhuanhuandefen,$zhuanhuanshifen,$xiangchidefen,$xiangchishifen];
    }



}
