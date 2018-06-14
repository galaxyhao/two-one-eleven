<?php
/**
 * Created by PhpStorm.
 * User: 91641
 * Date: 2018/6/7
 * Time: 19:07
 */

namespace api\goods\controller;

use api\goods\model\GoodsUserModel;
use api\goods\service\WalletService;
use cmf\controller\RestUserBaseController;
use think\Log;
use think\Request;
use api\goods\model\GoodsModel;

class WalletController extends RestUserBaseController
{
    private $walletService;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->walletService = new WalletService();
    }

    public function getBalance()
    {
        try {
            $userId = $this->getUserId();
            $balance = $this->walletService->getBalance($userId);
        }  catch (\Exception $e) {
            $this->error('余额获取失败');
        }

        $this->success('获取成功',$balance);
    }

}