<?php

namespace app\web\model;

use think\Model;
use think\Request;
use think\Exception;
use think\Db;

class Search extends Model
{

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
            $arr = $this->setWhere($request)
                ->field('a.id')
                ->field('a.big')
                ->field('a.small')
                ->field('a.mess_id')
                ->field('b.game_name')
                ->field('b.game_date')
                ->field('b.game_project')
                ->field('b.game_stage')
                ->field('c.user_name user_a')
                ->field('d.user_name user_b')
                ->join('message b', 'b.id = a.mess_id', 'left')
                ->join('user c', 'b.user_a = c.id', 'left')
                ->join('user d', 'b.user_b = d.id', 'left')
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

    private function setWhere(Request $request){

        $query = Db::name("count_score")->alias('a');
        if ($request->param('time')) {
            $query->where('b.game_date', $request->param('time'));
        }
        if ($request->param('name')) {
            $query->where('b.game_name', $request->param('name'));
        }
        if ($request->param('city')) {
            $query->where('b.city', $request->param('city'));
        }
        if ($request->param('user_a')) {
            $query->where('b.user_a', $request->param('user_a'));
        }
        if ($request->param('user_b')) {
            $query->where('b.user_b', $request->param('user_b'));
        }
        if ($request->param('hand')) {
            $query->where('c.hand|d.hand', $request->param('hand'));
        }
        if ($request->param('bat')) {
            $query->where('c.bat|d.bat', $request->param('bat'));
        }
        if ($request->param('play')) {
            $query->where('c.play|d.play', $request->param('play'));
        }
        return $query;
    }

    public function getPlayFunc()
    {
        try{
            return Db::name('user')
                ->alias('a')
                ->field('a.play')
                ->group('a.play')
                ->select();
        }catch (Exception $e){
            echo $e->getMessage();
            die();
        }
    }

    public function getGameDate()
    {
        try{
            return Db::name('message')
                ->alias('a')
                ->field('a.game_date')
                ->group('a.game_date')
                ->select();
        }catch (Exception $e){
            echo $e->getMessage();
            die();
        }
    }

    public function getGameName()
    {
        try{
            return Db::name('message')
                ->alias('a')
                ->field('a.game_name')
                ->group('a.game_name')
                ->select();
        }catch (Exception $e){
            echo $e->getMessage();
            die();
        }
    }

    public function getCity()
    {
        try{
            return Db::name('message')
                ->alias('a')
                ->field('a.city')
                ->group('a.city')
                ->select();
        }catch (Exception $e){
            echo $e->getMessage();
            die();
        }
    }

    public function getCount(Request $request){
        return $this->setWhere($request)
            ->join('message b', 'b.id = a.mess_id', 'left')
            ->join('user c', 'b.user_a = c.id', 'left')
            ->join('user d', 'b.user_b = d.id', 'left')
            ->count();
    }

    public function getOne($id){
        try{
            return $this->get($id);
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }

}
