<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Match extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        if($request->isAjax()){
            $data = model("Match")->getList($request);
            return $data;
        }else{
            return $this->fetch();
        }
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $arr = model('Athlete')->getTotal();
        $this->assign("arr", $arr);
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->except('admin/add_html');
            if (model("Match")->insert($data)) {
                return $this->success('添加成功','/admin/match/index');
            } else {
                return $this->error('添加失败','/admin/match/index');
            }
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $arr = model("Match")->getOne($id);
        $this->assign('data', $arr);
        $arr2 = model('Athlete')->getTotal();
        $this->assign("arr", $arr2);
        return $this->fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        if(model('Match') -> allowField(true) -> save($request -> param(), ['id' => $id])){
            return $this->success('修改成功','/admin/match/index');
        } else {
            return $this->success('修改失败','/admin/match/index');
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if(model("Match")->get($id)->delete()){
            return $this->success('删除成功','/admin/match/index');
        } else {
            return $this->success('删除失败','/admin/match/index');
        }
    }
}
