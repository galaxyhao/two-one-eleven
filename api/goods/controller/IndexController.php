<?php
/**
 * Created by PhpStorm.
 * User: 91641
 * Date: 2018/6/7
 * Time: 19:07
 */

namespace api\goods\controller;

use api\goods\model\GoodsUserModel;
use cmf\controller\RestUserBaseController;
use think\exception\DbException;
use think\Log;
use think\Request;
use api\goods\model\GoodsModel;

class IndexController extends RestUserBaseController
{
    private $goodsModel;
    private $goodsUserModel;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->goodsModel = new GoodsModel();
        $this->goodsUserModel = new GoodsUserModel();
    }

    public function imageUpload(Request $request){
        $image = $request->file('file');
        if($image){
            $info = $image->move(ROOT_PATH.'public'.DS.'upload');
            if ($info) {
                $this->success('文件上传成功',$info->getSaveName());
            } else {
                // 上传失败获取错误信息
                $this->error($image->getError());
            }
        }else{
            $this->error('请选择文件');
        }
    }
    public function addGoods(){
        $data = $this->request->param();
        if(empty($data)){
            $this->error('提交数据不能为空');
        }
        $data['owner'] = $this->getUserId();
        $this->goodsModel->save($data);
        $this->success('添加成功');
    }

    public function goodsList(){
        try {
            $list = $this->goodsModel->order('create_time desc')->field('id,title,banner,amount,price')->select();
        }  catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success('获取成功',$list);
    }

    public function goodsDetail(){
        $good_id = $this->request->param('goods_id');
        try {
            $data = $this->goodsModel->where('id', $good_id)->field('id,title,banner,desc,amount,price,rule')->find();
            $count = $this->goodsUserModel->where([
                'goods_id' => $good_id,
                'user_id'  => $this->getUserId()
            ])->count();
            if ($count == 0)
                $data['isJoin'] = false;
            else
                $data['isJoin'] = true;
            $data['avatars'] = $this->getGroupUsers($good_id);
            $data['tips'] = $this->getCurrentDiscount($data['rule'],count($data['avatars']));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('获取成功',$data);
    }

    private function getGroupUsers($good_id){
        $avatars = [];
        try {
            $goods = GoodsModel::get($good_id);
            if (empty($goods))
                return [];
            foreach ($goods->user as $user ){
                $avatars[] = $user->avatar;
            }
            return $avatars;
        } catch (DbException $e) {
            return $e->getMessage().$e->getTraceAsString();
            $this->error('数据库出错');
        }
    }
    private function getCurrentDiscount($rule,$num){
        $rules = json_decode($rule,true);
        $length = count($rules);
        if($num < $rules[0]['num']){
            return "当前参团人数".$num."人,不足最低打折人数";
        }elseif ($num > $rules[$length-1]['num']){
            return "已团".$num.'人,可打'.$rules[$length-1]['discount'].'折';
        }else {
            foreach ($rules as $key => $r) {
                if ($r['num'] > $num) {
                    break;
                }
            }
            return "已团".$num.'人,可打'.$rules[$key-1]['discount'].'折';
        }
    }
}