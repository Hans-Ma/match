<?php

namespace app\admin\model;

use think\Exception;
use think\Model;
use think\Request;

class Data extends Model
{
    protected $table = "game_data";

    private function setWhere(Request $request){
        $query = $this;
        if ($request->param('opt')) {
            $query = $this->where('mess_id', $request->param('opt'));
        }
        if ($request->param('athlete')) {
            $query = $this->where('user_id', $request->param('athlete'));
        }
        return $query;
    }

    public function getList(Request $request)
    {
        // 起始位置
        $offset = $request->param('start', 0);
        // 获取的记录数
        $limit = $request->param('length', 8);
        // 排序
        $columns = $request->param('columns');
        // 排序的字段索引
        $index = $request->param('order')[0]['column'];
        // 排序的规则
        $orderWay = $request->param('order')[0]['dir'];
        // 字段
        $field = $columns[$index]['data'];
        // 设置排序规则
        try{
            $arr = $this
                ->setWhere($request)
                ->alias('a')
                ->field('a.*')
                ->field('b.office_name situation')
                ->field('c.user_name p1')
                ->field('d.game_name game')
                ->join('office b', 'b.id = a.class', 'left')
                ->join('user c', 'a.user_id = c.id', 'left')
                ->join('message d', 'a.mess_id = d.id', 'left')
                ->order($field, $orderWay)
                ->limit($offset, $limit)
                ->select();
        } catch (Exception $e){
            echo $e->getMessage();
            die();
        }

        $data = [
            'draw'            => $request->post('draw', 0),
            'recordsTotal'    => $this->getCount($request),
            'recordsFiltered' => $this->getCount($request),
            'data'            => $arr,
        ];
        return $data;
    }

    public function insert1($data)
    {
        return $this->save($data);
    }

    public function getTotal()
    {
        try{
            return $this->select();
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function getCount(Request $request){
        return $this->setWhere($request)->count();
    }

    public function getOne($id){
        try{
            return $this->get($id);
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function getTempData(){
        try{
            $data = $this->limit('5')->select();
            return $data;
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function insertAll(array $dataSet = [], $replace = false, $limit = null)
    {
        return parent::insertAll($dataSet, $replace, $limit);
    }

    public function calc($match_id, $user_id){
        try{
            $sql = "SELECT MAX(score_first) a1, MAX(score_last) a2 FROM game_data WHERE user_id = {$user_id} AND mess_id = {$match_id} GROUP BY class ORDER BY class ASC";
            $arr = $this->query($sql);
            $small = "";
            $b1 = $b2 = 0;
            foreach ($arr as $value){
                if($value['a1'] > $value['a2']){
                    $small.= ++$value['a1'] . '-' . $value['a2'] . "&nbsp;&nbsp;&nbsp;";
                    $b1 += 1;
                } else {
                    $small.= $value['a1'] . '-' . ++$value['a2'] ."&nbsp;&nbsp;&nbsp;";
                    $b2 += 1;
                }
            }
            $big = $b1."-".$b2;
            $arr2 = [
                "mess_id" => $match_id,
                "big"     => $big,
                "small"   => $small
            ];
            $result = model('Score') -> allowField(true) -> save($arr2);
            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }
}
