<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Athlete extends Controller
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        if($request->isAjax()){
            $data = model("Athlete")->getList($request);
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
        //dump($request->param());
        if ($request->isPost()) {
            $file = request()->file('image');
            $info = $file->move('./uploads');
            $path = '/uploads/'.$info->getSaveName();
            $data = $request->except('admin/add_html');
            $data['image'] = $path;
            if (model("Athlete")->inserts($data)) {
                return $this->success('添加成功','/admin/ath/index');
            } else {
                return $this->error('添加失败','/admin/ath/index');
            }
        }
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $arr = model("Athlete")->getOne($id);
        $this->assign('data', $arr);
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
        if(model('Athlete') -> allowField(true) -> save($request -> param(), ['id' => $id])){
            return $this->success('修改成功','/admin/ath/index');
        } else {
            return $this->success('修改失败','/admin/ath/index');
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
        if(model("Athlete")->get($id)->delete()){
            return $this->success('删除成功','/admin/ath/index');
        } else {
            return $this->success('删除失败','/admin/ath/index');
        }
    }
}
