<?php

namespace frontend\components;
use yii\base\Component;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class Sms extends Component
{
    public $app_key; //应用key
    public $app_secret; //应用密码
    public $sign_name; //签名
    public $template_code; //短信模板
    private $_num;  //电话号码
    private $_param=[]; //短信内容


    //设置手机号码
    public function setNum($num){

        $this->_num = $num;
        return $this; //注意返回这个$this的目的,不返回可以对属性进行设置，但是外部无法连续调用,返回这个对象，外部可以连续调用
    }

    //设置短信内容
    public function setParam(array $param){

        $this->_param =$param;
        return $this;
    }

    //设置短信签名
    public function setSign($sign){

        $this->sign_name = $sign;
        return $this;
    }

    //设置短信模板
    public function setTemplate($id){

        $this->template_code = $id;
        return $this;
    }

    //发送短信
    public function send(){
        $client = new Client(new App(['app_key'=>$this->app_key,'app_secret'=>$this->app_secret]));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $req->setRecNum($this->_num)  //短信发送给谁-号码
        ->setSmsParam($this->_param) //设置短信内容
            ->setSmsFreeSignName($this->sign_name) //设置短信签名，必须是已审核的签名
            ->setSmsTemplateCode($this->template_code); //设置短信模板id，必须审核通过

        return $client->execute($req);

    }








/*// 配置信息
        $config = [
        'app_key'    => '24485762',
        'app_secret' => 'b3e205196ab1ad483eca51202acce011',
        // 'sandbox'    => true,  // 是否为沙箱环境，默认false
    ];

    // 使用方法一
$client = new Client(new App($config));
$req    = new AlibabaAliqinFcSmsNumSend;

$code = rand(1000,9999);
$req->setRecNum('18200571481')  //短信发送给谁-号码
->setSmsParam([
'code'=>$code //对应阿里大于模板里面的${code}
])
->setSmsFreeSignName('精彩商城') //设置短信签名，必须是已审核的签名
->setSmsTemplateCode('SMS_71875243'); //设置短信模板id，必须审核通过

$resp = $client->execute($req);
var_dump($resp);
var_dump($code);*/

}