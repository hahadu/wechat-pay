# wechat-pay
###微信支付（基于官方SDK做PSR4支持）

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
        $input->SetAttach("test");
        $input->SetOut_trade_no("sdkphp123456789".date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/notify.php");
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id("123456789");
        $result = $notify->GetPayUrl($input);
        $result["code_url"]; //微信支付二维码信息 示例weixin://wxpay/bizpayurl/up?pr=NwY5Mz9&groupid=00
```