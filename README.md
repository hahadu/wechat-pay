# wechat-pay
###微信支付（基于官方SDK做PSR4支持）

安装：composer require hahadu/wechat-pay
命名空间对应：
```
Hahadu\WechatPay\Kernel\WxPayData  => lib/WxPay.Data.php
Hahadu\WechatPay\Kernel\WxPayApi   => lib/WxPay.Api.php
Hahadu\WechatPay\Kernel\WxPayException => lib/WxPay.Exception.php
Hahadu\WechatPay\Kernel\WxPayNotify    => lib/WxPay.Notify.php
Hahadu\WechatPay\Kernel\config\WxPayConfigInterface => lib/WxPay.Config.Interface.php
```
使用 
````
use Hahadu\WechatPay\Kernel\config\Config;
Hahadu\WechatPay\Kernel\config\WxPayConfig
//传入配置:
    
        $options = new Config();
        $options->AppId = ‘’; //appid
        $options->MerchantId = ''; //MerchantId
        $options->NotifyUrl = ''; //NotifyUrl
        $options->Key =  ''; //key
        $options->AppSecret = ''; //AppSecret
       // $options->SignType = ''; // SingType 默认值HMAC-SHA256
        $wechatConfig = new WxPayConfig($options);
    
````
扫码支付：
```
use Hahadu\WechatPay\Library\WechatNativePay;
use Hahadu\WechatPay\Kernel\WxPayData\WxPayUnifiedOrder;
       
        $notify = new WechatNativePay($wechatConfig);
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetOut_trade_no("sdkphp123456789".date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id("123456789");
        $result = $notify->GetPayUrl($input);
        $result["code_url"]; //微信支付二维码信息 示例weixin://wxpay/bizpayurl/up?pr=NwY5Mz9&groupid=00
```

JSAPI支付
```puml
use Hahadu\WechatPay\Library\WechatJsApiPay;
use Hahadu\WechatPay\Kernel\WxPayData\WxPayUnifiedOrder;
use Hahadu\WechatPay\Kernel\WxPayApi;
        $tools = new WechatJsApiPay($wechatConfig);
        $openId = $tools->GetOpenid(); //获取微信OpenID
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test"); //订单标题
        $input->SetOut_trade_no("sdkphp".date("YmdHis")); //商户订单号
        $input->SetTotal_fee("1"); //订单金额
        $input->SetTime_start(date("YmdHis")); //订单创建时间
        $input->SetTrade_type("JSAPI"); //支付方式
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($this->wechatConfig, $input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
    
        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();
    
        $result= [
            'jsApiParameters' =>$jsApiParameters,
            'editAddress' => $editAddress,
            'order' => $order,
        ];
```
