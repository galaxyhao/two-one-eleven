<?php
/**
 * Created by PhpStorm.
 * User: 91641
 * Date: 2018/6/14
 * Time: 21:33
 */

namespace api\goods\controller;


use cmf\controller\RestUserBaseController;

class GroupManageController extends RestUserBaseController
{
    public function myAllGroup()
    {
        $goods = $this->myGoods();
        $mygoods = [];
        $i =0;
        //时间戳判断
        foreach ($goods as $key => $v) {
            if ($v['deadline']<time()) {
                $v['status'] = '已完成';
            }else{
                $v['status'] = '未完成';
            }
            $mygoods[$i]['goods_id'] = $v['goods_id'];
            $mygoods[$i]['status'] = $v['status'];
            $mygoods[$i]['title'] = $v['title'];
            $mygoods[$i]['banner'] = $v['banner'];
            $mygoods[$i]['price'] = $v['price'];
            $i++;
        }
        $this->success($mygoods);
    }
    public function activeGroup()
    {
        $goods = $this->myGoods();
        $mygoods = [];
        $i =0;
        foreach ($goods as $key => $v) {
            if ($v['deadline']>time()) {
                $mygoods[$i]['goods_id'] = $v['goods_id'];
                $mygoods[$i]['title'] = $v['title'];
                $mygoods[$i]['banner'] = $v['banner'];
                $mygoods[$i]['price'] = $v['price'];
            }
            $i++;
        }
        $this->success($mygoods);
    }
    public function successGroup($value='')
    {
        $goods = $this->myGoods();
        $mygoods = [];
        $i =0;
        foreach ($goods as $key => $v) {
            if ($v['deadline']<time()) {
                $mygoods[$i]['goods_id'] = $v['goods_id'];
                $mygoods[$i]['title'] = $v['title'];
                $mygoods[$i]['banner'] = $v['banner'];
                $mygoods[$i]['price'] = $v['price'];
            }
            $i++;
        }
        $this->success($mygoods);
    }
    public function myCreateGroup()
    {
        $goods = $this->myGoods();
        $mygoods = [];
        $i =0;
        foreach ($goods as $key => $v) {
            if ($v['deadline']<time()) {
                $v['status'] = '拼团成功';
            }else{
                $v['status'] = '正在拼团';
            }
            $mygoods[$i]['goods_id'] = $v['goods_id'];
            $mygoods[$i]['status'] = $v['status'];
            $mygoods[$i]['title'] = $v['title'];
            $mygoods[$i]['banner'] = $v['banner'];
            $mygoods[$i]['price'] = $v['price'];
            $mygoods[$i]['deadline'] = $v['deadline'];
            $i++;
        }
        $this->success($mygoods);
    }
    private function myGoods()
    {
        $user_id = $this->getUserId();
        $goodsUser = [];
        //获取用户拼团商品id
        $goodsUser = db('goods_user') ->where(['user_id'=>$user_id])->select();
        if (empty($goodsUser)) {
            $this->success('还没有拼团哦！');
            exit();
        }
        //goodsid列表
        $goods_id ='';
        foreach ($goodsUser as $key => $v) {
            $goods_id.=$v['goods_id'].',';
        }
        //var_dump($goods_id);
        //获取用户所有拼团记录
        $goods = db('goods')->where('id','in',$goods_id)->select();
        return $goods;
    }
}