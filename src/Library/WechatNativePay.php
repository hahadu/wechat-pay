<?php
/**
 *  +----------------------------------------------------------------------
 *  | Created by  hahadu (a low phper and coolephp)
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2020. [hahadu] All rights reserved.
 *  +----------------------------------------------------------------------
 *  | SiteUrl: https://github.com/hahadu
 *  +----------------------------------------------------------------------
 *  | Author: hahadu <582167246@qq.com>
 *  +----------------------------------------------------------------------
 *  | Date: 2020/10/10 下午11:20
 *  +----------------------------------------------------------------------
 *  | Description:   WechatPay
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\WechatPay\Library;
use Hahadu\WechatPay\Kernel\WxPayApi;
use Hahadu\WechatPay\Kernel\WxPayData\WxPayBizPayUrl;
use Hahadu\WechatPay\Kernel\config\WxPayConfig;
use Hahadu\WechatPay\Kernel\WxPayData\WxPayUnifiedOrder;
use Exception;


class WechatNativePay
{
    private $config;
    public function __construct($config){
        $this->config = $config;
    }
    /**
     *
     * 生成扫描支付URL,模式一
     * @param BizPayUrlInput $bizUrlInfo
     * @param $productId
     * @return false|string|array
     */
    public function GetPrePayUrl($productId)
    {
        $biz = new WxPayBizPayUrl();
        $biz->SetProduct_id($productId);
        try{
            $values = WxpayApi::bizpayurl($this->config, $biz);
        } catch(Exception $e) {
            return [
                'message'=>$e->getMessage(),
                'code' =>$e->getCode(),
                'file' =>$e->getFile(),
                'line' =>$e->getLine(),
            ];
        }
        $url = "weixin://wxpay/bizpayurl?" . $this->ToUrlParams($values);
        return $url;
    }
    /**
     *
     * 参数数组转换为url参数
     * @param array $urlObj
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     *
     * 生成直接支付url，支付url有效期为2小时,模式二
     * @param UnifiedOrderInput $input
     */
    public function GetPayUrl($input)
    {
        if($input->GetTrade_type() == "NATIVE")
        {
            try{
                $result = WxPayApi::unifiedOrder($this->config, $input);
                return $result;
            } catch(Exception $e) {
                return [
                    'message'=>$e->getMessage(),
                    'code'=>$e->getCode(),
                    'file' =>$e->getFile(),
                    'line'=>$e->getLine(),
                    ];
            }
        }
        return false;
    }
}