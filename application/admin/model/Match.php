<?php

namespace app\admin\model;

use think\Model;
use think\Request;
use think\Exception;

class Match extends Model
{
    protected $table = "message";

    public function getList(Request $request)
    {
        try {
            // 起始位置
            $offset = $request->param('start', 0);
            // 获取的记录数
            $limit = $request->param('length', 10);
            // 排序
            $columns = $request->param('columns');
            // 排序的字段索引
            $index = $request->param('order')[0]['column'];
            // 排序的规则
            $orderWay = $request->param('order')[0]['dir'];
            // 字段
            $field = $columns[$index]['data'];
            // 设置排序规则
            $arr = $this->alias('a')
                ->field('a.*')
                ->field('b.user_name p1')
                ->field('c.user_name p2')
                ->leftJoin('user b', 'a.user_a = b.id')
                ->leftJoin('user c', 'a.user_b = c.id')
                ->order($field, $orderWay)
                ->limit($offset, $limit)
                ->select();
        } catch (Exception $e){
            $e->getMessage();
            die();
        }

        $data = [
            'draw'            => $request->post('draw', 0),
            'recordsTotal'    => $this->getTotal()->count(),
            'recordsFiltered' => $this->getTotal()->count(),
            'data'            => $arr,
        ];

        return $data;
    }

    public function insert($data)
    {
        return $this->save($data);
    }

    public function getTotal()
    {
        return $this->select();
    }

    public function getTotalWithAthName(){
        try {
            $ret = $this->alias('a')
                ->field('a.*')
                ->field('b.user_name p1')
                ->field('c.user_name p2')
                ->leftJoin('user b', 'a.user_a = b.id')
                ->leftJoin('user c', 'a.user_b = c.id')
                ->select();
        } catch (Exception $e){
            $e->getMessage();
            die();
        }
        return $ret;
    }

    public function getListByMatchId($id){
        /*$sql = 'SELECT b.user_name p1, c.user_name p2, b.id id1, c.id id2 ' .
            'FROM `message` a LEFT JOIN `user` b ON a.user_a = b.id ' .
            "LEFT JOIN `user` c ON a.user_b = c.id WHERE a.id = {$id}";
        $ret = $this->query($sql);*/
        try {
            $ret = $this->alias('a')
                ->field('b.id id1')
                ->field('c.id id2')
                ->field('b.user_name p1')
                ->field('c.user_name p2')
                ->where('a.id', $id)
                ->leftJoin('user b', 'b.id = a.user_a')
                ->leftJoin('user c', 'c.id = a.user_b')
                ->order('a.id', 'desc')
                ->select();
        } catch (Exception $e){
            $e->getMessage();
            die();
        }
        return $ret;
    }

    public function getOne($id){
        return $this->get($id);
    }
}
