<?php

namespace app\admin\model;
use think\Model;
use think\Request;
use think\Exception;

class Athlete extends Model
{
    protected $table = "user";

    public function getList(Request $request)
    {
        try{
            // 起始位置
            $offset = $request->param('start', 0);
            // 获取的记录数
            $limit = $request->param('length', 6);
            // 排序
            $columns = $request->param('columns');
            // 排序的字段索引
            $index = $request->param('order')[0]['column'];
            // 排序的规则
            $orderWay = $request->param('order')[0]['dir'];
            // 字段
            $field = $columns[$index]['data'];
            $arr = $this
                ->order($field, $orderWay)
                ->limit($offset, $limit)
                ->select();
        } catch (Exception $e){
            $e->getMessage();
            die();
        }
        // 设置排序规则
        $data = [
            'draw'            => $request->post('draw', 0),
            'recordsTotal'    => $this->getTotal()->count(),
            'recordsFiltered' => $this->getTotal()->count(),
            'data'            => $arr,
        ];
        return $data;
    }

    public function inserts($data)
    {
        return $this->save($data);
    }

    public function getTotal()
    {
        return $this->select();
    }

    public function getOne($id){
        return $this->get($id);
    }

}
