<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;

class Data extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        if($request->isAjax()){
            $data = model("Data")->getList($request);
            return $data;
        }else{
            $match = model('Match')->getTotalWithAthName();
            $athlete = model('Athlete')->getTotal();
            $data = model('Data')->getTotal();
            return $this->fetch('',[
                'play'  => $data,
                'match' => $match,
                'player'=> $athlete,
            ]);
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
        $arr1 = model('Match')->getTotal();
        $this->assign("arr", $arr);
        $this->assign("game", $arr1);
        $arr3 = Db::table('office')->select();
        $this->assign("office", $arr3);
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

            if (model("Data")->insert1($data)) {
                return $this->success('添加成功','/admin/data/index');
            } else {
                return $this->error('添加失败','/admin/data/index');
            }
        }
    }

    /**
     * 显示指定的资源
     *
     * @return \think\Response
     */
    public function read()
    {
        $id = $_POST['id'];
        $arr = model('Match')->getListByMatchId($id);
        return json($arr);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = model("Data")->getOne($id);
        $player = model('Athlete')->getTotal();
        $match = model('Match')->getTotal();
        $office = Db::table('office')->select();
        return $this->fetch("", [
            "data"   => $data,
            "arr"    => $player,
            "game"   => $match,
            "office" => $office,
        ]);
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
        if(model('Data') -> allowField(true) -> save($request -> param(), ['id' => $id])){
            return $this->success('修改成功','/admin/data/index');
        } else {
            return $this->success('修改失败','/admin/data/index');
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
        if(model("Data")->get($id)->delete()){
            return $this->success('删除成功','/admin/data/index');
        } else {
            return $this->success('删除失败','/admin/data/index');
        }
    }


    // 文件下载
    public function download(){
        $file_name = date('Y-m-d H:i:s').'.xlsx';
        $PHPExcel = new \PHPExcel();
        $PHPSheet = $PHPExcel->getActiveSheet()->mergeCells('A1:G1');
//        $PHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPSheet->setTitle("1");
        $PHPSheet->setCellValue("A1","刘丁硕vs格罗斯（2018年匈牙利公开赛四分之一决赛）");
        $PHPSheet->setCellValue("A2","局数");
        $PHPSheet->setCellValue("B2","总得");
        $PHPSheet->setCellValue("C2","总失");
        $PHPSheet->setCellValue("D3","发接轮次");
        $PHPSheet->setCellValue("E2","拍数");
        $PHPSheet->setCellValue("F2","手段");
        $PHPSheet->setCellValue("G2","得失分");
        $data = model('Data')->getTempData();

        $i = 3;
        foreach($data as $key => $value){
            $PHPSheet->setCellValue('A'.$i,''.$value['class']);
            $PHPSheet->setCellValue('B'.$i,''.$value['score_first']);
            $PHPSheet->setCellValue('C'.$i,''.$value['score_last']);
            $PHPSheet->setCellValue('D'.$i,''.$value['send']);
            $PHPSheet->setCellValue('E'.$i,''.$value['bat_number']);
            $PHPSheet->setCellValue('F'.$i,''.$value['tool']);
            $PHPSheet->setCellValue('G'.$i,''.$value['get_lose']);
            $i++;
        }
        $PHPWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, "Excel2007");
        header('Content-Disposition: attachment;filename=' . $file_name);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $PHPWriter->save("php://output");

    }

    public function upload(){
        $player = model('Athlete')->getTotal();
        $match = model('Match')->getTotal();
        return $this->fetch("", [
            "game"   => $match,
            "arr"    => $player,
        ]);
    }

    /**
     * @param Request $request
     * @return string|\think\response\Redirect
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function addExcel(Request $request)
    {
        if (request()->isPost()) {
            //获取表单上传文件
            $file = request()->file('file');
            $info = $file->validate(['ext' => 'xlsx'])->move('./file');
            if ($info) {
                $path = $info->getSaveName();  //获取文件名
                $file_name = './file/' . $path;//上传文件的地址
                $objReader = \PHPExcel_IOFactory::createReader("Excel2007");
                $obj_PHPExcel = $objReader->load(str_replace('\\', '/', $file_name), $encode = 'utf-8');  //加载文件内容,编码utf-8
                //转换为数组格式
                $excel_array = $obj_PHPExcel->getSheet(0)->toArray();
                array_shift($excel_array);  //删除第一个数组(标题);
                array_shift($excel_array);  //删除第一个数组(标题);
                $city = [];
                $match = $request->post('mess_id');
                $player = $request->post('user_id');
                foreach ($excel_array as $k => $v) {
                    $city[$k]['class'] = $v[0];
                    $city[$k]['mess_id'] = $match;
                    $city[$k]['user_id'] = $player;
                    $city[$k]['score_first'] = $v[1];
                    $city[$k]['score_last'] = $v[2];
                    $city[$k]['send'] = $v[3];
                    $city[$k]['bat_number'] = $v[4];
                    $city[$k]['tool'] = $v[5];
                    $city[$k]['get_lose'] = $v[6];
                }

                if (model("Data")->insertAll($city)) {
                    if ($this->calc($match, $player)) {
                        return $this->success('上传成功','/admin/data/index');
                    }
                } else {
                    return '上传失败';
                }
            } else {
                echo $file->getError();
            }
        }
    }

    private function calc($match_id, $user_id){
        model("Data")->calc($match_id, $user_id);
        return true;
    }

}
