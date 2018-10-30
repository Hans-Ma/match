<?php

namespace app\admin\model;
use think\Model;
use think\Request;
use think\Exception;

class Score extends Model
{
    protected $table = "count_score";

    private function setWhere(Request $request)
    {
        $query = $this;
        if ($request->param('mess_id')) {
            $query = $this->where('mess_id', $request->param('mess_id'));
        }
        return $query;
    }

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
                ->setWhere($request)
                ->alias('a')
                ->field('a.*')
                ->field('b.show as show1')
                ->field('b.game_name game_name')
                ->field('b.game_date game_date')
                ->field('b.game_stage game_stage')
                ->field('b.game_project game_project')
                ->field('c.user_name user1')
                ->field('d.user_name user2')
                ->join('message b', 'b.id = a.mess_id', 'left')
                ->join('user c', 'c.id = b.user_a', 'left')
                ->join('user d', 'd.id = b.user_b', 'left')
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
            'recordsTotal'    => $this->getTotal($request)->count(),
            'recordsFiltered' => $this->getTotal($request)->count(),
            'data'            => $arr,
        ];
        return $data;
    }

    public function inserts($data)
    {
        return $this->save($data);
    }

    public function getTotal(Request $request)
    {
        return $this->setWhere($request)->select();
    }

    public function getOne($id){
        return $this->get($id);
    }
}
